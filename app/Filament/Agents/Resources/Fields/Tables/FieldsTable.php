<?php

namespace App\Filament\Agents\Resources\Fields\Tables;

use App\Filament\Agents\Resources\FieldUpdates\FieldUpdateResource;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
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
                    ->label('Field Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('crop_type')
                    ->label('Crop')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),
                TextColumn::make('planting_date')
                    ->label('Planted')
                    ->date()
                    ->sortable(),
                TextColumn::make('current_stage')
                    ->label('Stage')
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
                TextColumn::make('updates_max_created_at')
                    ->label('Last Update')
                    ->max('updates', 'created_at')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('No updates yet'),
            ])
            ->filters([
                SelectFilter::make('current_stage')
                    ->label('Stage')
                    ->options([
                        'planted' => 'Planted',
                        'growing' => 'Growing',
                        'ready' => 'Ready',
                        'harvested' => 'Harvested',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('submitUpdate')
                    ->label('Update')
                    ->icon(Heroicon::OutlinedPencilSquare)
                    ->color('success')
                    ->url(fn ($record) => FieldUpdateResource::getUrl('create', ['field_id' => $record->id])),
            ])
            ->defaultSort('current_stage', 'asc')
            ->striped();
    }
}
