<?php

namespace App\Filament\Resources\Expenses\Tables;

use App\Models\Expense;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ExpensesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('expense_date')
                    ->date()
                    ->sortable(),

                TextColumn::make('title')
                    ->searchable()
                    ->limit(30),

                TextColumn::make('category.name')
                    ->label('Category')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('field.name')
                    ->label('Field')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('amount')
                    ->money('KES')
                    ->sortable(),

                TextColumn::make('vendor')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('recordedBy.name')
                    ->label('Recorded By')
                    ->toggleable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),

                ImageColumn::make('receipt_path')
                    ->label('Receipt')
                    ->circular()
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
                        'rejected' => 'Rejected',
                    ]),

                SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->preload(),

                SelectFilter::make('field')
                    ->relationship('field', 'name')
                    ->preload(),

                Filter::make('this_month')
                    ->query(fn (Builder $query) => $query->whereMonth('expense_date', now()->month))
                    ->label('This Month'),

                Filter::make('this_year')
                    ->query(fn (Builder $query) => $query->whereYear('expense_date', now()->year))
                    ->label('This Year'),
            ])
            ->recordActions([
                Action::make('approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Expense $record): bool => $record->status === 'pending')
                    ->action(fn (Expense $record) => $record->approve(auth()->user())),

                Action::make('reject')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Expense $record): bool => $record->status === 'pending')
                    ->action(fn (Expense $record) => $record->reject()),

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
                        ->action(fn (Collection $records) => $records->each(fn (Expense $record) => $record->approve(auth()->user()))),
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('expense_date', 'desc');
    }
}
