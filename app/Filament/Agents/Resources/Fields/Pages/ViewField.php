<?php

namespace App\Filament\Agents\Resources\Fields\Pages;

use App\Filament\Agents\Resources\Fields\FieldResource;
use App\Filament\Agents\Resources\FieldUpdates\FieldUpdateResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewField extends ViewRecord
{
    protected static string $resource = FieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('submitUpdate')
                ->label('Submit Update')
                ->icon(Heroicon::OutlinedPencilSquare)
                ->color('success')
                ->url(fn () => FieldUpdateResource::getUrl('create', ['field_id' => $this->record->id])),
        ];
    }
}
