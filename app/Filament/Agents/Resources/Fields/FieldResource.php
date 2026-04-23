<?php

namespace App\Filament\Agents\Resources\Fields;

use App\Filament\Agents\Resources\Fields\Pages\ListFields;
use App\Filament\Agents\Resources\Fields\Pages\ViewField;
use App\Filament\Agents\Resources\Fields\Tables\FieldsTable;
use App\Models\Field;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class FieldResource extends Resource
{
    protected static ?string $model = Field::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMap;

    protected static \UnitEnum|string|null $navigationGroup = 'Field Operations';

    protected static ?string $navigationLabel = 'My Fields';

    protected static ?string $modelLabel = 'Field';

    protected static ?string $pluralModelLabel = 'My Fields';

    protected static ?int $navigationSort = 1;

    public static function table(Table $table): Table
    {
        return FieldsTable::configure($table);
    }

    /**
     * Scope queries to only show fields assigned to the current agent
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('agent_id', Auth::id());
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
            'view' => ViewField::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Agents cannot create fields, only admin can assign
    }
}
