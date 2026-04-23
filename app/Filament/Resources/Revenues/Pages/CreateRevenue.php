<?php

namespace App\Filament\Resources\Revenues\Pages;

use App\Filament\Resources\Revenues\RevenueResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRevenue extends CreateRecord
{
    protected static string $resource = RevenueResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['recorded_by'] = auth()->id();

        return $data;
    }
}
