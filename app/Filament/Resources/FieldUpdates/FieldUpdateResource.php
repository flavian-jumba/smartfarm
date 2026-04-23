<?php

namespace App\Filament\Resources\FieldUpdates;

use App\Filament\Resources\FieldUpdates\Pages\CreateFieldUpdate;
use App\Filament\Resources\FieldUpdates\Pages\EditFieldUpdate;
use App\Filament\Resources\FieldUpdates\Pages\ListFieldUpdates;
use App\Filament\Resources\FieldUpdates\Schemas\FieldUpdateForm;
use App\Filament\Resources\FieldUpdates\Tables\FieldUpdatesTable;
use App\Models\FieldUpdate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FieldUpdateResource extends Resource
{
    protected static ?string $model = FieldUpdate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static \UnitEnum|string|null $navigationGroup = 'Field Management';

    protected static ?string $navigationLabel = 'Field Updates';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return FieldUpdateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FieldUpdatesTable::configure($table);
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
            'index' => ListFieldUpdates::route('/'),
            'create' => CreateFieldUpdate::route('/create'),
            'edit' => EditFieldUpdate::route('/{record}/edit'),
        ];
    }
}
