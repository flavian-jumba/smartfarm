<?php

namespace App\Filament\Resources\Messages\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Message')
                    ->schema([
                        Select::make('receiver_id')
                            ->relationship('receiver', 'name')
                            ->label('To')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('subject')
                            ->maxLength(255),

                        Textarea::make('body')
                            ->label('Message')
                            ->required()
                            ->rows(6),
                    ]),

                Section::make('Options')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('type')
                                    ->options([
                                        'text' => 'Regular Message',
                                        'alert' => 'Alert',
                                        'report' => 'Report',
                                        'request' => 'Request',
                                        'broadcast' => 'Broadcast',
                                    ])
                                    ->default('text')
                                    ->required(),

                                Select::make('priority')
                                    ->options([
                                        'low' => 'Low',
                                        'normal' => 'Normal',
                                        'high' => 'High',
                                        'urgent' => 'Urgent',
                                    ])
                                    ->default('normal')
                                    ->required(),
                            ]),

                        Select::make('tenant_id')
                            ->relationship('tenant', 'name')
                            ->required(),
                    ]),

                Section::make('Attachment')
                    ->schema([
                        FileUpload::make('attachment_path')
                            ->label('Attachment')
                            ->directory('message-attachments')
                            ->maxSize(10240),
                    ]),
            ]);
    }
}
