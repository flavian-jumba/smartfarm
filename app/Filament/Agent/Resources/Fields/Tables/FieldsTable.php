<?php

namespace App\Filament\Agent\Resources\Fields\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FieldsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('crop_type')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('planting_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('current_stage')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'planted' => 'info',
                        'growing' => 'warning',
                        'ready' => 'success',
                        'harvested' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('updates_count')
                    ->label('Updates')
                    ->counts('updates')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('current_stage')
                    ->options([
                        'planted' => 'Planted',
                        'growing' => 'Growing',
                        'ready' => 'Ready',
                        'harvested' => 'Harvested',
                    ]),
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
