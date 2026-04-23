<?php

namespace App\Filament\Resources\Payrolls\Tables;

use App\Models\Payroll;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class PayrollsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('period')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Employee')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('payment_type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        'salary' => 'primary',
                        'wages' => 'info',
                        'bonus' => 'success',
                        'overtime' => 'warning',
                        'commission' => 'secondary',
                        'deduction' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('base_amount')
                    ->label('Base')
                    ->money('KES')
                    ->toggleable(),

                TextColumn::make('bonus_amount')
                    ->label('Bonus')
                    ->money('KES')
                    ->toggleable(),

                TextColumn::make('deductions')
                    ->money('KES')
                    ->toggleable(),

                TextColumn::make('net_amount')
                    ->label('Net Amount')
                    ->money('KES')
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'info',
                        'paid' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('payment_date')
                    ->date()
                    ->sortable()
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
                        'approved' => 'Approved',
                        'paid' => 'Paid',
                        'cancelled' => 'Cancelled',
                    ]),

                SelectFilter::make('payment_type')
                    ->options([
                        'salary' => 'Salary',
                        'wages' => 'Wages',
                        'bonus' => 'Bonus',
                        'overtime' => 'Overtime',
                        'commission' => 'Commission',
                    ]),

                SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->label('Employee')
                    ->preload(),
            ])
            ->recordActions([
                Action::make('approve')
                    ->icon('heroicon-o-check')
                    ->color('info')
                    ->requiresConfirmation()
                    ->visible(fn (Payroll $record): bool => $record->status === 'pending')
                    ->action(fn (Payroll $record) => $record->approve()),

                Action::make('mark_paid')
                    ->label('Mark as Paid')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Payroll $record): bool => $record->status === 'approved')
                    ->action(fn (Payroll $record) => $record->markAsPaid()),

                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('approve_selected')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check')
                        ->color('info')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each(fn (Payroll $record) => $record->approve())),

                    BulkAction::make('mark_paid_selected')
                        ->label('Mark as Paid')
                        ->icon('heroicon-o-banknotes')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each(fn (Payroll $record) => $record->markAsPaid())),

                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
