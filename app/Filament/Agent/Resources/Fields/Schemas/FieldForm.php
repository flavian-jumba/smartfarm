<?php

namespace App\Filament\Agent\Resources\Fields\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class FieldForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('crop_type')
                    ->required()
                    ->maxLength(255),
                DatePicker::make('planting_date')
                    ->required()
                    ->default(now()),
                Select::make('current_stage')
                    ->options([
                        'planted' => 'Planted',
                        'growing' => 'Growing',
                        'ready' => 'Ready',
                        'harvested' => 'Harvested',
                    ])
                    ->default('planted')
                    ->required(),
                // Auto-fill agent_id with the logged-in agent
                Hidden::make('agent_id')
                    ->default(fn () => Auth::id()),
                // Auto-fill tenant_id with the agent's tenant
                Hidden::make('tenant_id')
                    ->default(fn () => Auth::user()->tenant_id),
            ]);
    }
}
