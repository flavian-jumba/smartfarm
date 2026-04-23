<?php

namespace App\Filament\Agent\Resources\Fields;

use App\Filament\Agent\Resources\Fields\Pages\CreateField;
use App\Filament\Agent\Resources\Fields\Pages\EditField;
use App\Filament\Agent\Resources\Fields\Pages\ListFields;
use App\Filament\Agent\Resources\Fields\Schemas\FieldForm;
use App\Filament\Agent\Resources\Fields\Tables\FieldsTable;
use App\Models\Field;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class FieldResource extends Resource
{
    protected static ?string $model = Field::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMap;

    protected static \UnitEnum|string|null $navigationGroup = 'Field Management';

    protected static ?string $navigationLabel = 'My Fields';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return FieldForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FieldsTable::configure($table);
    }

    /**
     * Scope queries to the agent's tenant
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('tenant_id', Auth::user()->tenant_id);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFields::route('/'),
            'create' => CreateField::route('/create'),
            'edit' => EditField::route('/{record}/edit'),
        ];
    }
}
