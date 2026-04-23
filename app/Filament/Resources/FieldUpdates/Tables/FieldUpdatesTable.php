<?php

namespace App\Filament\Resources\FieldUpdates\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FieldUpdatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('field.name')
                    ->label('Field')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('field.tenant.name')
                    ->label('Tenant')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('agent.name')
                    ->label('Agent')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('stage')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'planted' => 'info',
                        'growing' => 'warning',
                        'ready' => 'success',
                        'harvested' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('notes')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->notes),
                TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('stage')
                    ->options([
                        'planted' => 'Planted',
                        'growing' => 'Growing',
                        'ready' => 'Ready',
                        'harvested' => 'Harvested',
                    ]),
                SelectFilter::make('agent')
                    ->relationship('agent', 'name')
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
