<?php

namespace App\Filament\Agent\Resources\FieldUpdates\Pages;

use App\Filament\Agent\Resources\FieldUpdates\FieldUpdateResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFieldUpdate extends EditRecord
{
    protected static string $resource = FieldUpdateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
