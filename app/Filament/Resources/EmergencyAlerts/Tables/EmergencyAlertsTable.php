<?php

namespace App\Filament\Resources\EmergencyAlerts\Tables;

use App\Models\EmergencyAlert;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EmergencyAlertsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('severity')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'critical' => 'danger',
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

                TextColumn::make('user.name')
                    ->label('Reported By')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'acknowledged' => 'info',
                        'in_progress' => 'primary',
                        'resolved' => 'success',
                        'dismissed' => 'gray',
                        default => 'gray',
                    }),

                TextColumn::make('location')
                    ->label('GPS')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Reported At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('severity')
                    ->options([
                        'critical' => 'Critical',
                        'high' => 'High',
                        'medium' => 'Medium',
                        'low' => 'Low',
                    ]),

                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'acknowledged' => 'Acknowledged',
                        'in_progress' => 'In Progress',
                        'resolved' => 'Resolved',
                        'dismissed' => 'Dismissed',
                    ]),

                SelectFilter::make('type')
                    ->options([
                        'medical' => 'Medical',
                        'security' => 'Security',
                        'equipment' => 'Equipment',
                        'weather' => 'Weather',
                        'pest_outbreak' => 'Pest Outbreak',
                        'other' => 'Other',
                    ]),
            ])
            ->recordActions([
                Action::make('acknowledge')
                    ->icon('heroicon-o-check')
                    ->color('info')
                    ->requiresConfirmation()
                    ->visible(fn (EmergencyAlert $record): bool => $record->status === 'pending')
                    ->action(fn (EmergencyAlert $record) => $record->acknowledge(auth()->user())),

                Action::make('resolve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (EmergencyAlert $record): bool => in_array($record->status, ['pending', 'acknowledged', 'in_progress']))
                    ->action(fn (EmergencyAlert $record) => $record->resolve()),

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
