<?php

namespace App\Filament\Agent\Resources\FieldUpdates\Pages;

use App\Filament\Agent\Resources\FieldUpdates\FieldUpdateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFieldUpdates extends ListRecords
{
    protected static string $resource = FieldUpdateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
