<?php

namespace App\Filament\Resources\Tasks\Tables;

use App\Models\Task;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TasksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'urgent' => 'danger',
                        'high' => 'warning',
                        'medium' => 'info',
                        'low' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('title')
                    ->searchable()
                    ->limit(40),

                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst(str_replace('_', ' ', $state)))
                    ->color('primary'),

                TextColumn::make('assignee.name')
                    ->label('Assigned To')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('field.name')
                    ->label('Field')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('due_date')
                    ->date()
                    ->sortable()
                    ->color(fn (Task $record): string => $record->is_overdue ? 'danger' : 'gray'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'in_progress' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        'overdue' => 'warning',
                        default => 'gray',
                    }),

                IconColumn::make('gps_verified')
                    ->label('GPS')
                    ->boolean()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        'overdue' => 'Overdue',
                    ]),

                SelectFilter::make('priority')
                    ->options([
                        'urgent' => 'Urgent',
                        'high' => 'High',
                        'medium' => 'Medium',
                        'low' => 'Low',
                    ]),

                SelectFilter::make('type')
                    ->options([
                        'planting' => 'Planting',
                        'watering' => 'Watering',
                        'fertilizing' => 'Fertilizing',
                        'pest_control' => 'Pest Control',
                        'harvesting' => 'Harvesting',
                        'maintenance' => 'Maintenance',
                        'inspection' => 'Inspection',
                        'other' => 'Other',
                    ]),

                SelectFilter::make('assignee')
                    ->relationship('assignee', 'name')
                    ->preload(),

                SelectFilter::make('field')
                    ->relationship('field', 'name')
                    ->preload(),
            ])
            ->recordActions([
                Action::make('start')
                    ->icon('heroicon-o-play')
                    ->color('info')
                    ->visible(fn (Task $record): bool => $record->status === 'pending')
                    ->action(fn (Task $record) => $record->start()),

                Action::make('complete')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Task $record): bool => in_array($record->status, ['pending', 'in_progress']))
                    ->action(fn (Task $record) => $record->complete()),

                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('due_date', 'asc');
    }
}
