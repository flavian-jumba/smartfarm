<?php

namespace App\Filament\Agent\Resources\Fields\Pages;

use App\Filament\Agent\Resources\Fields\FieldResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditField extends EditRecord
{
    protected static string $resource = FieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
