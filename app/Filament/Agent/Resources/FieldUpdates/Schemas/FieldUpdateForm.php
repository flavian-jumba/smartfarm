<?php

namespace App\Filament\Agent\Resources\FieldUpdates\Schemas;

use App\Models\Field;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class FieldUpdateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Only show fields belonging to the agent's tenant
                Select::make('field_id')
                    ->label('Field')
                    ->options(fn () => Field::query()
                        ->where('tenant_id', Auth::user()->tenant_id)
                        ->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Select::make('stage')
                    ->options([
                        'planted' => 'Planted',
                        'growing' => 'Growing',
                        'ready' => 'Ready',
                        'harvested' => 'Harvested',
                    ])
                    ->required(),
                Textarea::make('notes')
                    ->rows(4)
                    ->columnSpanFull(),
                // Auto-fill agent_id with the logged-in agent
                Hidden::make('agent_id')
                    ->default(fn () => Auth::id()),
            ]);
    }
}
