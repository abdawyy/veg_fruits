<?php

namespace Tests\Feature;

use App\Models\SiteVisitor;
use App\Services\Analytics\VisitorGeoLocator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

final class EgyptVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_cloudflare_edge_ip_does_not_replace_the_egyptian_visitor(): void
    {
        Http::fake([
            'https://ipwho.is/41.33.10.30' => Http::response([
                'success' => true,
                'country' => 'Egypt',
                'country_code' => 'EG',
                'region' => 'Cairo',
                'city' => 'Cairo',
            ]),
        ]);

        $this->withServerVariables(['REMOTE_ADDR' => '10.0.0.8'])
            ->withHeaders([
                'CF-Ray' => 'abc-CAI',
                'CF-Connecting-IP' => '41.33.10.30',
            ])
            ->get('/')
            ->assertOk();

        $visitor = SiteVisitor::query()->sole();
        $this->assertSame('Egypt', $visitor->country);
        $this->assertSame('Cairo', $visitor->city);
        Http::assertSent(fn ($request): bool => str_contains($request->url(), '41.33.10.30'));
    }

    public function test_search_bots_are_not_counted_as_visitors(): void
    {
        $this->withHeader('User-Agent', 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)')
            ->get('/')
            ->assertOk();

        $this->assertSame(0, SiteVisitor::query()->count());
    }

    public function test_storefront_is_marked_for_egypt_and_lists_pages_for_search(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('geo.region" content="EG"', false)
            ->assertSee('hreflang="ar-EG"', false)
            ->assertSee('hreflang="en-EG"', false);

        $this->withHeader('Accept-Language', 'ar-EG,ar;q=0.9')
            ->get('/')
            ->assertOk()
            ->assertSee('dir="rtl"', false);

        $this->get(route('store.sitemap'))
            ->assertOk()
            ->assertSee(route('store.shop'), false)
            ->assertSee(route('store.fruits'), false);
    }

    public function test_client_ip_prefers_the_cloudflare_visitor_address(): void
    {
        $request = request()->duplicate();
        $request->headers->set('CF-Ray', 'abc');
        $request->headers->set('CF-Connecting-IP', '197.44.1.2');
        $request->server->set('REMOTE_ADDR', '104.16.0.1');

        $this->assertSame('197.44.1.2', app(VisitorGeoLocator::class)->clientIp($request));
    }
}
