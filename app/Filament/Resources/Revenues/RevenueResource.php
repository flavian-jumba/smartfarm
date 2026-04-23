<?php

namespace App\Filament\Resources\Revenues;

use App\Filament\Resources\Revenues\Pages\CreateRevenue;
use App\Filament\Resources\Revenues\Pages\EditRevenue;
use App\Filament\Resources\Revenues\Pages\ListRevenues;
use App\Filament\Resources\Revenues\Schemas\RevenueForm;
use App\Filament\Resources\Revenues\Tables\RevenuesTable;
use App\Models\Revenue;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RevenueResource extends Resource
{
    protected static ?string $model = Revenue::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    protected static \UnitEnum|string|null $navigationGroup = 'Financial';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return RevenueForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RevenuesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRevenues::route('/'),
            'create' => CreateRevenue::route('/create'),
            'edit' => EditRevenue::route('/{record}/edit'),
        ];
    }
}
