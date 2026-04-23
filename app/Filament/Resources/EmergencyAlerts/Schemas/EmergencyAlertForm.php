<?php

namespace App\Filament\Resources\EmergencyAlerts\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EmergencyAlertForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Alert Details')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('description')
                            ->required()
                            ->rows(4),

                        Grid::make(2)
                            ->schema([
                                Select::make('type')
                                    ->options([
                                        'medical' => 'Medical Emergency',
                                        'security' => 'Security Issue',
                                        'equipment' => 'Equipment Failure',
                                        'weather' => 'Weather Alert',
                                        'pest_outbreak' => 'Pest Outbreak',
                                        'other' => 'Other',
                                    ])
                                    ->required(),

                                Select::make('severity')
                                    ->options([
                                        'low' => 'Low',
                                        'medium' => 'Medium',
                                        'high' => 'High',
                                        'critical' => 'Critical',
                                    ])
                                    ->default('high')
                                    ->required(),
                            ]),
                    ]),

                Section::make('Location')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('latitude')
                                    ->numeric()
                                    ->step(0.00000001),

                                TextInput::make('longitude')
                                    ->numeric()
                                    ->step(0.00000001),
                            ]),
                    ]),

                Section::make('Attachments')
                    ->schema([
                        FileUpload::make('image_path')
                            ->label('Photo/Evidence')
                            ->image()
                            ->directory('emergency-alerts')
                            ->maxSize(5120),
                    ]),

                Section::make('Status & Resolution')
                    ->schema([
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'acknowledged' => 'Acknowledged',
                                'in_progress' => 'In Progress',
                                'resolved' => 'Resolved',
                                'dismissed' => 'Dismissed',
                            ])
                            ->default('pending')
                            ->required(),

                        Textarea::make('resolution_notes')
                            ->label('Resolution Notes')
                            ->rows(3),
                    ])
                    ->visibleOn('edit'),

                Section::make('Assignment')
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Reported By')
                            ->required(),

                        Select::make('tenant_id')
                            ->relationship('tenant', 'name')
                            ->required(),
                    ]),
            ]);
    }
}
