<?php

namespace App\Filament\Resources\Fields\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FieldForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('crop_type')
                    ->required(),
                DatePicker::make('planting_date')
                    ->required(),
                Select::make('current_stage')
                    ->options(['planted' => 'Planted', 'growing' => 'Growing', 'ready' => 'Ready', 'harvested' => 'Harvested'])
                    ->default('planted')
                    ->required(),
                Select::make('agent_id')
                    ->relationship('agent', 'name')
                    ->required(),
                Select::make('tenant_id')
                    ->relationship('tenant', 'name')
                    ->required(),
            ]);
    }
}
