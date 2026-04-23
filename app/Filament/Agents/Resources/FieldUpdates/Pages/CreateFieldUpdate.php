<?php

namespace App\Filament\Agents\Resources\FieldUpdates\Pages;

use App\Filament\Agents\Resources\FieldUpdates\FieldUpdateResource;
use App\Models\Field;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateFieldUpdate extends CreateRecord
{
    protected static string $resource = FieldUpdateResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['agent_id'] = Auth::id();

        // Update the field's current stage
        if (isset($data['field_id']) && isset($data['stage'])) {
            Field::where('id', $data['field_id'])->update([
                'current_stage' => $data['stage'],
            ]);
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Update Submitted')
            ->body('Your field update has been recorded successfully.');
    }
}
