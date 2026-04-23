<?php

namespace App\Filament\Resources\WorkLogs;

use App\Filament\Resources\WorkLogs\Pages\CreateWorkLog;
use App\Filament\Resources\WorkLogs\Pages\EditWorkLog;
use App\Filament\Resources\WorkLogs\Pages\ListWorkLogs;
use App\Filament\Resources\WorkLogs\Schemas\WorkLogForm;
use App\Filament\Resources\WorkLogs\Tables\WorkLogsTable;
use App\Models\WorkLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WorkLogResource extends Resource
{
    protected static ?string $model = WorkLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static \UnitEnum|string|null $navigationGroup = 'Task Management';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Daily Work Logs';

    public static function form(Schema $schema): Schema
    {
        return WorkLogForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkLogsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWorkLogs::route('/'),
            'create' => CreateWorkLog::route('/create'),
            'edit' => EditWorkLog::route('/{record}/edit'),
        ];
    }
}
