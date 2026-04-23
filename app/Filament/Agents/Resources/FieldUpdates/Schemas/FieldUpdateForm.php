<?php

namespace App\Filament\Agents\Resources\FieldUpdates\Schemas;

use App\Models\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class FieldUpdateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('field_id')
                ->label('Field')
                ->options(fn () => Field::query()
                    ->where('agent_id', Auth::id())
                    ->pluck('name', 'id'))
                ->searchable()
                ->preload()
                ->required()
                ->helperText('Select the field you are updating'),

            Select::make('stage')
                ->options([
                    'preparation' => 'Preparation',
                    'planting' => 'Planting',
                    'growing' => 'Growing',
                    'harvesting' => 'Harvesting',
                    'post-harvest' => 'Post-Harvest',
                ])
                ->required()
                ->native(false)
                ->helperText('Current stage of the field'),

            Textarea::make('notes')
                ->label('Notes')
                ->rows(4)
                ->placeholder('Add any observations, issues, or notes about the field...')
                ->maxLength(65535)
                ->columnSpanFull(),
        ]);
    }
}
