<?php

namespace App\Filament\Resources\Expenses\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ExpenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Expense Details')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('description')
                            ->rows(3),

                        Grid::make(2)
                            ->schema([
                                Select::make('expense_category_id')
                                    ->relationship('category', 'name')
                                    ->label('Category')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->createOptionForm([
                                        TextInput::make('name')->required(),
                                        TextInput::make('description'),
                                    ]),

                                DatePicker::make('expense_date')
                                    ->required()
                                    ->native(false)
                                    ->default(now()),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('amount')
                                    ->required()
                                    ->numeric()
                                    ->prefix('KES')
                                    ->minValue(0),

                                TextInput::make('vendor')
                                    ->label('Vendor/Supplier'),
                            ]),
                    ]),

                Section::make('Assignment')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('field_id')
                                    ->relationship('field', 'name')
                                    ->label('Field (Optional)')
                                    ->searchable()
                                    ->preload()
                                    ->helperText('Associate with a specific field'),

                                Select::make('tenant_id')
                                    ->relationship('tenant', 'name')
                                    ->required(),
                            ]),
                    ]),

                Section::make('Receipt')
                    ->schema([
                        FileUpload::make('receipt_path')
                            ->label('Receipt/Invoice')
                            ->image()
                            ->directory('expense-receipts')
                            ->maxSize(5120),
                    ]),

                Section::make('Approval')
                    ->schema([
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->default('pending')
                            ->required(),
                    ])
                    ->visibleOn('edit'),
            ]);
    }
}
