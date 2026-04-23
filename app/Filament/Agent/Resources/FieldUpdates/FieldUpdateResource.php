<?php

namespace App\Filament\Agent\Resources\FieldUpdates;

use App\Filament\Agent\Resources\FieldUpdates\Pages\CreateFieldUpdate;
use App\Filament\Agent\Resources\FieldUpdates\Pages\EditFieldUpdate;
use App\Filament\Agent\Resources\FieldUpdates\Pages\ListFieldUpdates;
use App\Filament\Agent\Resources\FieldUpdates\Schemas\FieldUpdateForm;
use App\Filament\Agent\Resources\FieldUpdates\Tables\FieldUpdatesTable;
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

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static \UnitEnum|string|null $navigationGroup = 'Field Management';

    protected static ?string $navigationLabel = 'Submit Updates';

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
     * Scope queries to updates on fields belonging to the agent's tenant
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('field', fn (Builder $query) => $query->where('tenant_id', Auth::user()->tenant_id));
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
