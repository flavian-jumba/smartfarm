<?php

namespace App\Filament\Resources\WorkLogs\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class WorkLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Work Log Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->label('Agent')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                DatePicker::make('log_date')
                                    ->required()
                                    ->native(false)
                                    ->default(now()),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Select::make('field_id')
                                    ->relationship('field', 'name')
                                    ->label('Field')
                                    ->searchable()
                                    ->preload(),

                                Select::make('task_id')
                                    ->relationship('task', 'title')
                                    ->label('Related Task')
                                    ->searchable()
                                    ->preload(),
                            ]),

                        Select::make('tenant_id')
                            ->relationship('tenant', 'name')
                            ->required(),
                    ]),

                Section::make('Time Tracking')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TimePicker::make('check_in_time')
                                    ->label('Check In')
                                    ->native(false),

                                TimePicker::make('check_out_time')
                                    ->label('Check Out')
                                    ->native(false),

                                TextInput::make('hours_worked')
                                    ->label('Hours Worked')
                                    ->numeric()
                                    ->step(0.01)
                                    ->disabled(),
                            ]),
                    ]),

                Section::make('Check-In Location')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('check_in_latitude')
                                    ->label('Latitude')
                                    ->numeric()
                                    ->step(0.00000001),

                                TextInput::make('check_in_longitude')
                                    ->label('Longitude')
                                    ->numeric()
                                    ->step(0.00000001),
                            ]),
                    ])
                    ->collapsible(),

                Section::make('Check-Out Location')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('check_out_latitude')
                                    ->label('Latitude')
                                    ->numeric()
                                    ->step(0.00000001),

                                TextInput::make('check_out_longitude')
                                    ->label('Longitude')
                                    ->numeric()
                                    ->step(0.00000001),
                            ]),
                    ])
                    ->collapsible(),

                Section::make('Activities & Notes')
                    ->schema([
                        Textarea::make('activities_performed')
                            ->label('Activities Performed')
                            ->rows(4),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('weather_conditions')
                                    ->label('Weather'),

                                Select::make('status')
                                    ->options([
                                        'checked_in' => 'Checked In',
                                        'checked_out' => 'Checked Out',
                                        'approved' => 'Approved',
                                        'rejected' => 'Rejected',
                                    ])
                                    ->default('checked_in')
                                    ->required(),
                            ]),

                        Textarea::make('notes')
                            ->rows(2),
                    ]),
            ]);
    }
}
