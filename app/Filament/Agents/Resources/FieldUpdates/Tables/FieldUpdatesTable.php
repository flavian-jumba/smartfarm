<?php

namespace App\Filament\Agents\Resources\FieldUpdates\Tables;

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

                TextColumn::make('stage')
                    ->badge()
                    ->colors([
                        'gray' => 'preparation',
                        'info' => 'planting',
                        'warning' => 'growing',
                        'success' => 'harvesting',
                        'primary' => 'post-harvest',
                    ])
                    ->sortable(),

                TextColumn::make('notes')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->notes)
                    ->wrap(),

                TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('stage')
                    ->options([
                        'preparation' => 'Preparation',
                        'planting' => 'Planting',
                        'growing' => 'Growing',
                        'harvesting' => 'Harvesting',
                        'post-harvest' => 'Post-Harvest',
                    ]),

                SelectFilter::make('field_id')
                    ->label('Field')
                    ->relationship('field', 'name'),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
