<?php

namespace App\Filament\Agents\Resources\FieldUpdates\Pages;

use App\Filament\Agents\Resources\FieldUpdates\FieldUpdateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFieldUpdates extends ListRecords
{
    protected static string $resource = FieldUpdateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Submit New Update'),
        ];
    }
}
