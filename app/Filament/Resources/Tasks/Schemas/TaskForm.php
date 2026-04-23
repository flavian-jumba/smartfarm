<?php

namespace App\Filament\Resources\Tasks\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TaskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Task Details')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('description')
                            ->rows(3),

                        Grid::make(2)
                            ->schema([
                                Select::make('type')
                                    ->options([
                                        'planting' => 'Planting',
                                        'watering' => 'Watering',
                                        'fertilizing' => 'Fertilizing',
                                        'pest_control' => 'Pest Control',
                                        'harvesting' => 'Harvesting',
                                        'maintenance' => 'Maintenance',
                                        'inspection' => 'Inspection',
                                        'other' => 'Other',
                                    ])
                                    ->required(),

                                Select::make('priority')
                                    ->options([
                                        'low' => 'Low',
                                        'medium' => 'Medium',
                                        'high' => 'High',
                                        'urgent' => 'Urgent',
                                    ])
                                    ->default('medium')
                                    ->required(),
                            ]),

                        DatePicker::make('due_date')
                            ->required()
                            ->native(false),
                    ]),

                Section::make('Assignment')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('assigned_to')
                                    ->relationship('assignee', 'name')
                                    ->label('Assign To')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Select::make('field_id')
                                    ->relationship('field', 'name')
                                    ->label('Field')
                                    ->searchable()
                                    ->preload(),
                            ]),

                        Select::make('tenant_id')
                            ->relationship('tenant', 'name')
                            ->required(),
                    ]),

                Section::make('GPS Verification')
                    ->description('Set target location for task completion verification')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('target_latitude')
                                    ->label('Target Latitude')
                                    ->numeric()
                                    ->step(0.00000001),

                                TextInput::make('target_longitude')
                                    ->label('Target Longitude')
                                    ->numeric()
                                    ->step(0.00000001),

                                TextInput::make('gps_tolerance_meters')
                                    ->label('Tolerance (meters)')
                                    ->numeric()
                                    ->default(100)
                                    ->helperText('Distance allowed from target'),
                            ]),
                    ]),

                Section::make('Status & Completion')
                    ->schema([
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'in_progress' => 'In Progress',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                                'overdue' => 'Overdue',
                            ])
                            ->default('pending')
                            ->required(),

                        Textarea::make('completion_notes')
                            ->label('Completion Notes')
                            ->rows(2),

                        Toggle::make('gps_verified')
                            ->label('GPS Verified')
                            ->disabled(),

                        FileUpload::make('completion_image_path')
                            ->label('Completion Photo')
                            ->image()
                            ->directory('task-completions')
                            ->maxSize(5120),
                    ])
                    ->visibleOn('edit'),
            ]);
    }
}
