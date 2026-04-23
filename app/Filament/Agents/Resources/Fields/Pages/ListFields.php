<?php

namespace App\Filament\Agents\Resources\Fields\Pages;

use App\Filament\Agents\Resources\Fields\FieldResource;
use Filament\Resources\Pages\ListRecords;

class ListFields extends ListRecords
{
    protected static string $resource = FieldResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
