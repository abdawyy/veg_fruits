<?php

namespace App\Support\Analytics;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductView;
use App\Models\SitePageView;
use App\Models\SiteVisitor;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use WeakMap;

/** Behaviour summary for one customer: where/when they browse, what they view, when and what they buy. */
final class CustomerInsights
{
    /** @var WeakMap<User, self>|null Shared per model instance, so one page render runs each query once. */
    private static ?WeakMap $instances = null;

    /** @var array<string, mixed> */
    private array $memo = [];

    private function __construct(private readonly User $user) {}

    public static function for(User $user): self
    {
        self::$instances ??= new WeakMap;

        return self::$instances[$user] ??= new self($user);
    }

    /* ---------------------------------------------------------------- Visits */

    public function sessionsCount(): int
    {
        return $this->remember('sessions', fn () => $this->visitors()->count());
    }

    public function firstSeen(): ?Carbon
    {
        return $this->remember('first_seen', fn () => self::toCarbon($this->visitors()->min('first_seen_at')));
    }

    public function lastSeen(): ?Carbon
    {
        return $this->remember('last_seen', fn () => self::toCarbon($this->visitors()->max('last_seen_at')));
    }

    /** @return list<string> Most frequent locations first, e.g. "Cairo, Egypt (4 sessions)" */
    public function locations(int $limit = 3): array
    {
        return $this->remember('locations', function () use ($limit): array {
            if (! Schema::hasColumn('site_visitors', 'country')) {
                return [];
            }

            return $this->visitors()
                ->whereNotNull('geo_resolved_at')
                ->select(['city', 'region', 'country', DB::raw('COUNT(*) as sessions')])
                ->groupBy('city', 'region', 'country')
                ->orderByDesc('sessions')
                ->limit($limit)
                ->get()
                ->map(fn (SiteVisitor $v): string => ($v->location_label ?? __('Unknown')).' ('.trans_choice(':count session|:count sessions', (int) $v->sessions).')')
                ->all();
        });
    }

    /** @return list<string> e.g. "mobile (5)" */
    public function devices(): array
    {
        return $this->remember('devices', fn (): array => $this->visitors()
            ->whereNotNull('device_type')
            ->select(['device_type', DB::raw('COUNT(*) as sessions')])
            ->groupBy('device_type')
            ->orderByDesc('sessions')
            ->get()
            ->map(fn (SiteVisitor $v): string => $v->device_type.' ('.(int) $v->sessions.')')
            ->all());
    }

    /** @return array<int, int> Page views + product views per hour (reporting timezone) */
    public function browsingByHour(): array
    {
        return $this->remember('browse_hour', fn (): array => self::sum(
            Schema::hasTable('site_page_views') ? TimeBuckets::countByHour($this->pageViews(), 'visited_at') : array_fill(0, 24, 0),
            Schema::hasTable('product_views') ? TimeBuckets::countByHour($this->productViews(), 'visited_at') : array_fill(0, 24, 0),
        ));
    }

    /** @return array<int, int> */
    public function browsingByWeekday(): array
    {
        return $this->remember('browse_weekday', fn (): array => self::sum(
            Schema::hasTable('site_page_views') ? TimeBuckets::countByWeekday($this->pageViews(), 'visited_at') : array_fill_keys(TimeBuckets::WEEKDAY_ORDER, 0),
            Schema::hasTable('product_views') ? TimeBuckets::countByWeekday($this->productViews(), 'visited_at') : array_fill_keys(TimeBuckets::WEEKDAY_ORDER, 0),
        ));
    }

    public function usualBrowsingTime(): ?string
    {
        return self::describePattern($this->browsingByHour(), $this->browsingByWeekday());
    }

    /* --------------------------------------------------------- Product views */

    /** @return list<string> e.g. "Mango — 12 views (last 2026-10-01 20:15)" */
    public function mostViewedProducts(int $limit = 5): array
    {
        return $this->remember('top_viewed', function () use ($limit): array {
            if (! Schema::hasTable('product_views')) {
                return [];
            }

            $rows = $this->productViews()
                ->select(['product_id', DB::raw('COUNT(*) as views'), DB::raw('MAX(visited_at) as last_viewed_at')])
                ->groupBy('product_id')
                ->orderByDesc('views')
                ->limit($limit)
                ->get();

            $products = Product::query()->whereIn('id', $rows->pluck('product_id'))->get()->keyBy('id');

            return $rows->map(function (ProductView $row) use ($products): string {
                $name = self::productName($products->get($row->product_id)) ?? '#'.$row->product_id;
                $last = self::toCarbon($row->getRawOriginal('last_viewed_at') ?? $row->last_viewed_at);

                return $name.' — '.trans_choice(':count view|:count views', (int) $row->views)
                    .($last ? ' ('.__('last').' '.self::formatTime($last).')' : '');
            })->all();
        });
    }

    /* ------------------------------------------------------------- Purchases */

    /**
     * @return array{orders: int, spent: string, average: string, first: ?Carbon, last: ?Carbon, cancelled: int}
     */
    public function purchaseSummary(): array
    {
        return $this->remember('purchase_summary', function (): array {
            $paid = $this->orders();
            $count = (clone $paid)->count();
            $spent = (float) (clone $paid)->sum('total');

            return [
                'orders' => $count,
                'spent' => self::money($spent),
                'average' => $count > 0 ? self::money($spent / $count) : '—',
                'first' => self::toCarbon((clone $paid)->min('created_at')),
                'last' => self::toCarbon((clone $paid)->max('created_at')),
                'cancelled' => Order::query()->where('user_id', $this->user->getKey())->where('status', OrderStatus::Cancelled)->count(),
            ];
        });
    }

    /** @return array<int, int> */
    public function purchasesByHour(): array
    {
        return $this->remember('buy_hour', fn (): array => TimeBuckets::countByHour($this->orders(), 'created_at'));
    }

    /** @return array<int, int> */
    public function purchasesByWeekday(): array
    {
        return $this->remember('buy_weekday', fn (): array => TimeBuckets::countByWeekday($this->orders(), 'created_at'));
    }

    public function usualPurchaseTime(): ?string
    {
        return self::describePattern($this->purchasesByHour(), $this->purchasesByWeekday());
    }

    /** @return list<string> e.g. "Tomato — 6.50 kg in 3 orders" */
    public function mostPurchasedProducts(int $limit = 5): array
    {
        return $this->remember('top_bought', function () use ($limit): array {
            $rows = OrderItem::query()
                ->whereNotNull('product_id')
                ->whereIn('order_id', $this->orders()->select('id'))
                ->select(['product_id', 'unit', DB::raw('SUM(quantity) as qty'), DB::raw('COUNT(DISTINCT order_id) as orders_count')])
                ->groupBy('product_id', 'unit')
                ->orderByDesc('orders_count')
                ->orderByDesc('qty')
                ->limit($limit)
                ->get();

            $products = Product::query()->whereIn('id', $rows->pluck('product_id'))->get()->keyBy('id');

            return $rows->map(function (OrderItem $row) use ($products): string {
                $name = self::productName($products->get($row->product_id)) ?? '#'.$row->product_id;
                $qty = rtrim(rtrim(number_format((float) $row->qty, 2, '.', ''), '0'), '.');

                return $name.' — '.$qty.' '.($row->unit ?: '').' '.trans_choice('in :count order|in :count orders', (int) $row->orders_count);
            })->all();
        });
    }

    /** @return list<string> e.g. "2026-10-01 19:45 — 245.00 EGP — delivered (AD-1234)" */
    public function recentPurchases(int $limit = 5): array
    {
        return $this->remember('recent', fn (): array => Order::query()
            ->where('user_id', $this->user->getKey())
            ->latest('created_at')
            ->limit($limit)
            ->get(['id', 'reference', 'status', 'total', 'created_at'])
            ->map(fn (Order $o): string => self::formatTime($o->created_at)
                .' — '.self::money((float) $o->total)
                .' — '.($o->status?->value ?? '—')
                .' ('.$o->reference.')')
            ->all());
    }

    /* --------------------------------------------------------------- Helpers */

    private function visitors(): Builder
    {
        return SiteVisitor::query()->where('user_id', $this->user->getKey());
    }

    private function pageViews(): Builder
    {
        return SitePageView::query()->whereIn('session_id', $this->visitors()->select('session_id'));
    }

    private function productViews(): Builder
    {
        $userId = $this->user->getKey();

        return ProductView::query()->where(function (Builder $q) use ($userId): void {
            $q->where('user_id', $userId)
                ->orWhereIn('session_id', SiteVisitor::query()->where('user_id', $userId)->select('session_id'));
        });
    }

    private function orders(): Builder
    {
        return Order::query()
            ->where('user_id', $this->user->getKey())
            ->where('status', '!=', OrderStatus::Cancelled);
    }

    /**
     * @template T
     *
     * @param  callable(): T  $callback
     * @return T
     */
    private function remember(string $key, callable $callback): mixed
    {
        if (! array_key_exists($key, $this->memo)) {
            $this->memo[$key] = $callback();
        }

        return $this->memo[$key];
    }

    /**
     * @param  array<int, int>  $byHour
     * @param  array<int, int>  $byWeekday
     */
    private static function describePattern(array $byHour, array $byWeekday): ?string
    {
        $hour = TimeBuckets::peak($byHour);
        $day = TimeBuckets::peak($byWeekday);

        if ($hour === null && $day === null) {
            return null;
        }

        $parts = [];
        if ($day !== null) {
            $parts[] = TimeBuckets::weekdayName($day);
        }
        if ($hour !== null) {
            $parts[] = TimeBuckets::hourRangeLabel($hour);
        }

        return implode(', ', $parts).' ('.TimeBuckets::timezone().')';
    }

    /**
     * @param  array<int, int>  $a
     * @param  array<int, int>  $b
     * @return array<int, int>
     */
    private static function sum(array $a, array $b): array
    {
        foreach ($b as $key => $value) {
            $a[$key] = ($a[$key] ?? 0) + $value;
        }

        return $a;
    }

    private static function productName(?Product $product): ?string
    {
        if (! $product) {
            return null;
        }

        return $product->getTranslation('name', app()->getLocale()) ?: $product->getTranslation('name', 'en') ?: null;
    }

    public static function formatTime(?Carbon $moment): string
    {
        return $moment ? $moment->copy()->setTimezone(TimeBuckets::timezone())->format('Y-m-d H:i') : '—';
    }

    private static function money(float $amount): string
    {
        return number_format($amount, 2).' EGP';
    }

    private static function toCarbon(mixed $value): ?Carbon
    {
        if ($value instanceof Carbon) {
            return $value;
        }

        if (! is_string($value) || $value === '') {
            return null;
        }

        return Carbon::parse($value, config('app.timezone'));
    }
}
