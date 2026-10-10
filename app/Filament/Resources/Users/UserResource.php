<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Pages\ViewUser;
use App\Filament\Resources\Users\RelationManagers\UserOrdersRelationManager;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Filament\Resources\Users\Widgets\CustomerActivityChart;
use App\Models\User;
use App\Support\Analytics\CustomerInsights;
use App\Support\Analytics\TimeBuckets;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static string|UnitEnum|null $navigationGroup = 'Customers';

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('Users');
    }

    public static function getModelLabel(): string
    {
        return __('User');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Users');
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Account'))
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('email')->placeholder('—'),
                        TextEntry::make('phone_number')->label(__('Phone'))->placeholder('—'),
                        TextEntry::make('is_admin')->label(__('Admin user'))->badge(),
                        TextEntry::make('roles.name')
                            ->label(__('Roles'))
                            ->badge()
                            ->placeholder('—'),
                        TextEntry::make('email_verified_at')->dateTime()->placeholder('—'),
                        TextEntry::make('phone_verified_at')->dateTime()->placeholder('—'),
                        TextEntry::make('created_at')->dateTime(),
                    ]),
                Section::make(__('Customer insights — visits'))
                    ->description(__('Where and when this customer opens the shop (sessions while logged in). Times in :tz.', ['tz' => TimeBuckets::timezone()]))
                    ->columns(2)
                    ->schema([
                        TextEntry::make('insights_locations')
                            ->label(__('Opens the shop from'))
                            ->state(fn (User $record): array => CustomerInsights::for($record)->locations())
                            ->listWithLineBreaks()
                            ->bulleted()
                            ->placeholder(__('Not located yet')),
                        TextEntry::make('insights_devices')
                            ->label(__('Devices'))
                            ->state(fn (User $record): array => CustomerInsights::for($record)->devices())
                            ->badge()
                            ->placeholder('—'),
                        TextEntry::make('insights_sessions')
                            ->label(__('Sessions'))
                            ->state(fn (User $record): int => CustomerInsights::for($record)->sessionsCount()),
                        TextEntry::make('insights_usual_browsing')
                            ->label(__('Usually browses'))
                            ->state(fn (User $record): ?string => CustomerInsights::for($record)->usualBrowsingTime())
                            ->placeholder('—'),
                        TextEntry::make('insights_first_seen')
                            ->label(__('First visit'))
                            ->state(fn (User $record): string => CustomerInsights::formatTime(CustomerInsights::for($record)->firstSeen())),
                        TextEntry::make('insights_last_seen')
                            ->label(__('Last visit'))
                            ->state(fn (User $record): string => CustomerInsights::formatTime(CustomerInsights::for($record)->lastSeen())),
                        TextEntry::make('insights_top_viewed')
                            ->label(__('Most viewed products'))
                            ->state(fn (User $record): array => CustomerInsights::for($record)->mostViewedProducts())
                            ->listWithLineBreaks()
                            ->bulleted()
                            ->placeholder('—')
                            ->columnSpanFull(),
                    ]),
                Section::make(__('Customer insights — purchases'))
                    ->description(__('Cancelled orders are excluded from totals and purchase times.'))
                    ->columns(3)
                    ->schema([
                        TextEntry::make('insights_orders')
                            ->label(__('Orders'))
                            ->state(fn (User $record): int => CustomerInsights::for($record)->purchaseSummary()['orders']),
                        TextEntry::make('insights_spent')
                            ->label(__('Total spent'))
                            ->state(fn (User $record): string => CustomerInsights::for($record)->purchaseSummary()['spent']),
                        TextEntry::make('insights_average')
                            ->label(__('Average order'))
                            ->state(fn (User $record): string => CustomerInsights::for($record)->purchaseSummary()['average']),
                        TextEntry::make('insights_first_order')
                            ->label(__('First order'))
                            ->state(fn (User $record): string => CustomerInsights::formatTime(CustomerInsights::for($record)->purchaseSummary()['first'])),
                        TextEntry::make('insights_last_order')
                            ->label(__('Last order'))
                            ->state(fn (User $record): string => CustomerInsights::formatTime(CustomerInsights::for($record)->purchaseSummary()['last'])),
                        TextEntry::make('insights_cancelled')
                            ->label(__('Cancelled orders'))
                            ->state(fn (User $record): int => CustomerInsights::for($record)->purchaseSummary()['cancelled']),
                        TextEntry::make('insights_usual_purchase')
                            ->label(__('Usually orders'))
                            ->state(fn (User $record): ?string => CustomerInsights::for($record)->usualPurchaseTime())
                            ->placeholder('—')
                            ->columnSpanFull(),
                        TextEntry::make('insights_top_bought')
                            ->label(__('Most purchased products'))
                            ->state(fn (User $record): array => CustomerInsights::for($record)->mostPurchasedProducts())
                            ->listWithLineBreaks()
                            ->bulleted()
                            ->placeholder('—')
                            ->columnSpan(2),
                        TextEntry::make('insights_recent')
                            ->label(__('Recent purchases'))
                            ->state(fn (User $record): array => CustomerInsights::for($record)->recentPurchases())
                            ->listWithLineBreaks()
                            ->bulleted()
                            ->placeholder('—'),
                    ]),
            ]);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Toggle::make('is_admin')
                    ->label(__('Admin panel access'))
                    ->helperText(__('Allows login to /admin. Assign at least one role below for permissions.')),
                Select::make('roles')
                    ->label(__('Roles'))
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->helperText(__('Super admin has all permissions. Other roles limit catalog, orders, content, or analytics.')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            UserOrdersRelationManager::class,
        ];
    }

    public static function getWidgets(): array
    {
        return [
            CustomerActivityChart::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'view' => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
