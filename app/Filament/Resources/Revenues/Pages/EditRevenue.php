<?php

namespace App\Filament\Resources\Revenues\Pages;

use App\Filament\Resources\Revenues\RevenueResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRevenue extends EditRecord
{
    protected static string $resource = RevenueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
