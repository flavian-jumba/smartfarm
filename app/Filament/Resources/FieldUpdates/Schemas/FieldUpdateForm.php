<?php

namespace App\Filament\Resources\FieldUpdates\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class FieldUpdateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('field_id')
                    ->relationship('field', 'name')
                    ->required(),
                Select::make('agent_id')
                    ->relationship('agent', 'name')
                    ->required(),
                Select::make('stage')
                    ->options(['planted' => 'Planted', 'growing' => 'Growing', 'ready' => 'Ready', 'harvested' => 'Harvested'])
                    ->required(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
