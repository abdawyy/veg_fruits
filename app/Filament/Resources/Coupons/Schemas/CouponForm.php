<?php

namespace App\Filament\Resources\Coupons\Schemas;

use App\Models\Coupon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CouponForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Coupon'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('code')->required()->maxLength(32)->unique(ignoreRecord: true)->dehydrateStateUsing(fn ($s) => strtoupper(trim((string) $s))),
                        Select::make('type')
                            ->options([
                                Coupon::TYPE_PERCENT => __('Percent'),
                                Coupon::TYPE_FIXED => __('Fixed amount'),
                            ])
                            ->required(),
                        TextInput::make('value')->numeric()->required(),
                        TextInput::make('min_subtotal')->numeric()->label(__('Min. subtotal'))->nullable(),
                        TextInput::make('max_uses')->numeric()->label(__('Max uses'))->nullable(),
                        TextInput::make('used_count')->numeric()->disabled()->dehydrated(false),
                        DateTimePicker::make('starts_at')->nullable(),
                        DateTimePicker::make('ends_at')->nullable(),
                        Toggle::make('is_active')->default(true),
                    ]),
            ]);
    }
}
