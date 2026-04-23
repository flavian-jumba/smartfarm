<?php

namespace App\Filament\Resources\Payrolls\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Schemas\Schema;

class PayrollForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Employee & Period')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->label('Employee')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                TextInput::make('period')
                                    ->required()
                                    ->placeholder('e.g., April 2026, Week 16')
                                    ->helperText('Payment period'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Select::make('payment_type')
                                    ->options([
                                        'salary' => 'Monthly Salary',
                                        'wages' => 'Daily Wages',
                                        'bonus' => 'Bonus',
                                        'overtime' => 'Overtime',
                                        'commission' => 'Commission',
                                        'deduction' => 'Deduction',
                                    ])
                                    ->default('salary')
                                    ->required(),

                                Select::make('tenant_id')
                                    ->relationship('tenant', 'name')
                                    ->required(),
                            ]),
                    ]),

                Section::make('Payment Details')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('base_amount')
                                    ->label('Base Amount')
                                    ->required()
                                    ->numeric()
                                    ->prefix('KES')
                                    ->minValue(0)
                                    ->live()
                                    ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateNet($get, $set)),

                                TextInput::make('bonus_amount')
                                    ->label('Bonus')
                                    ->numeric()
                                    ->prefix('KES')
                                    ->default(0)
                                    ->minValue(0)
                                    ->live()
                                    ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateNet($get, $set)),

                                TextInput::make('deductions')
                                    ->label('Deductions')
                                    ->numeric()
                                    ->prefix('KES')
                                    ->default(0)
                                    ->minValue(0)
                                    ->live()
                                    ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateNet($get, $set)),
                            ]),

                        TextInput::make('net_amount')
                            ->label('Net Amount')
                            ->required()
                            ->numeric()
                            ->prefix('KES')
                            ->disabled()
                            ->dehydrated(),
                    ]),

                Section::make('Status & Payment')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'approved' => 'Approved',
                                        'paid' => 'Paid',
                                        'cancelled' => 'Cancelled',
                                    ])
                                    ->default('pending')
                                    ->required(),

                                DatePicker::make('payment_date')
                                    ->label('Payment Date')
                                    ->native(false),
                            ]),

                        Textarea::make('notes')
                            ->rows(2),
                    ]),
            ]);
    }

    private static function calculateNet(Get $get, Set $set): void
    {
        $base = (float) ($get('base_amount') ?? 0);
        $bonus = (float) ($get('bonus_amount') ?? 0);
        $deductions = (float) ($get('deductions') ?? 0);

        $set('net_amount', $base + $bonus - $deductions);
    }
}
