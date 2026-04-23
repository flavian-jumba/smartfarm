<?php

namespace App\Filament\Resources\WorkLogs\Tables;

use App\Models\WorkLog;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class WorkLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('log_date')
                    ->date()
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Agent')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('field.name')
                    ->label('Field')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('check_in_time')
                    ->label('Check In')
                    ->time('H:i'),

                TextColumn::make('check_out_time')
                    ->label('Check Out')
                    ->time('H:i'),

                TextColumn::make('formatted_hours')
                    ->label('Hours'),

                TextColumn::make('weather_conditions')
                    ->label('Weather')
                    ->toggleable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'checked_in' => 'info',
                        'checked_out' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'checked_in' => 'Checked In',
                        'checked_out' => 'Checked Out',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),

                SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->label('Agent')
                    ->preload(),

                SelectFilter::make('field')
                    ->relationship('field', 'name')
                    ->preload(),

                Filter::make('today')
                    ->query(fn ($query) => $query->whereDate('log_date', today()))
                    ->label('Today'),

                Filter::make('this_week')
                    ->query(fn ($query) => $query->whereBetween('log_date', [now()->startOfWeek(), now()->endOfWeek()]))
                    ->label('This Week'),
            ])
            ->recordActions([
                Action::make('approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (WorkLog $record): bool => $record->status === 'checked_out')
                    ->action(fn (WorkLog $record) => $record->approve(auth()->user())),

                Action::make('reject')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (WorkLog $record): bool => $record->status === 'checked_out')
                    ->action(fn (WorkLog $record) => $record->reject()),

                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('approve_selected')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each(fn (WorkLog $record) => $record->approve(auth()->user()))),
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('log_date', 'desc');
    }
}
