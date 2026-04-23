<?php

namespace App\Filament\Resources\Revenues\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RevenuesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('revenue_date')
                    ->date()
                    ->sortable(),

                TextColumn::make('title')
                    ->searchable()
                    ->limit(30),

                TextColumn::make('source')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst(str_replace('_', ' ', $state)))
                    ->color(fn (string $state): string => match ($state) {
                        'harvest_sale' => 'success',
                        'livestock_sale' => 'info',
                        'equipment_rental' => 'warning',
                        'subsidy' => 'primary',
                        default => 'gray',
                    }),

                TextColumn::make('field.name')
                    ->label('Field')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('quantity_display')
                    ->label('Quantity')
                    ->toggleable(),

                TextColumn::make('amount')
                    ->money('KES')
                    ->sortable(),

                TextColumn::make('buyer_name')
                    ->label('Buyer')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('recordedBy.name')
                    ->label('Recorded By')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('source')
                    ->options([
                        'harvest_sale' => 'Harvest Sale',
                        'livestock_sale' => 'Livestock Sale',
                        'equipment_rental' => 'Equipment Rental',
                        'subsidy' => 'Government Subsidy',
                        'other' => 'Other',
                    ]),

                SelectFilter::make('field')
                    ->relationship('field', 'name')
                    ->preload(),

                Filter::make('this_month')
                    ->query(fn (Builder $query) => $query->whereMonth('revenue_date', now()->month))
                    ->label('This Month'),

                Filter::make('this_year')
                    ->query(fn (Builder $query) => $query->whereYear('revenue_date', now()->year))
                    ->label('This Year'),
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
            ->defaultSort('revenue_date', 'desc');
    }
}
