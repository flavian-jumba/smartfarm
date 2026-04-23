<?php

namespace App\Filament\Resources\Revenues\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RevenueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Revenue Details')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('description')
                            ->rows(3),

                        Grid::make(2)
                            ->schema([
                                Select::make('source')
                                    ->options([
                                        'harvest_sale' => 'Harvest Sale',
                                        'livestock_sale' => 'Livestock Sale',
                                        'equipment_rental' => 'Equipment Rental',
                                        'subsidy' => 'Government Subsidy',
                                        'other' => 'Other',
                                    ])
                                    ->required(),

                                DatePicker::make('revenue_date')
                                    ->required()
                                    ->native(false)
                                    ->default(now()),
                            ]),
                    ]),

                Section::make('Amount Details')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('quantity')
                                    ->numeric()
                                    ->minValue(0)
                                    ->step(0.01),

                                TextInput::make('unit')
                                    ->placeholder('kg, bags, pieces...'),

                                TextInput::make('unit_price')
                                    ->numeric()
                                    ->prefix('KES')
                                    ->minValue(0),
                            ]),

                        TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->prefix('KES')
                            ->minValue(0)
                            ->helperText('Total amount received'),
                    ]),

                Section::make('Buyer Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('buyer_name')
                                    ->label('Buyer Name'),

                                TextInput::make('buyer_contact')
                                    ->label('Buyer Contact')
                                    ->tel(),
                            ]),
                    ])
                    ->collapsible(),

                Section::make('Assignment')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('field_id')
                                    ->relationship('field', 'name')
                                    ->label('Field')
                                    ->searchable()
                                    ->preload(),

                                Select::make('tenant_id')
                                    ->relationship('tenant', 'name')
                                    ->required(),
                            ]),
                    ]),

                Section::make('Documentation')
                    ->schema([
                        FileUpload::make('receipt_path')
                            ->label('Receipt/Documentation')
                            ->image()
                            ->directory('revenue-receipts')
                            ->maxSize(5120),
                    ]),
            ]);
    }
}
