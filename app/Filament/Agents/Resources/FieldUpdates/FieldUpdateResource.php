<?php

namespace App\Filament\Agents\Resources\FieldUpdates;

use App\Filament\Agents\Resources\FieldUpdates\Pages\CreateFieldUpdate;
use App\Filament\Agents\Resources\FieldUpdates\Pages\ListFieldUpdates;
use App\Filament\Agents\Resources\FieldUpdates\Pages\ViewFieldUpdate;
use App\Filament\Agents\Resources\FieldUpdates\Schemas\FieldUpdateForm;
use App\Filament\Agents\Resources\FieldUpdates\Tables\FieldUpdatesTable;
use App\Models\FieldUpdate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class FieldUpdateResource extends Resource
{
    protected static ?string $model = FieldUpdate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static \UnitEnum|string|null $navigationGroup = 'Field Operations';

    protected static ?string $navigationLabel = 'My Updates';

    protected static ?string $modelLabel = 'Field Update';

    protected static ?string $pluralModelLabel = 'My Updates';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return FieldUpdateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FieldUpdatesTable::configure($table);
    }

    /**
     * Scope queries to only show updates submitted by the current agent
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
            'index' => ListFieldUpdates::route('/'),
            'create' => CreateFieldUpdate::route('/create'),
            'view' => ViewFieldUpdate::route('/{record}'),
        ];
    }

    public static function canEdit($record): bool
    {
        // Can only edit updates within 24 hours
        return $record->created_at->diffInHours(now()) < 24;
    }

    public static function canDelete($record): bool
    {
        return false;
    }
}
