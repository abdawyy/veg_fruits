<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Pages\ViewUser;
use App\Filament\Resources\Users\RelationManagers\UserOrdersRelationManager;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
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
