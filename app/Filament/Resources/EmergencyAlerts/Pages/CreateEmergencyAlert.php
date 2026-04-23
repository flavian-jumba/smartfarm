<?php

namespace App\Filament\Resources\EmergencyAlerts\Pages;

use App\Filament\Resources\EmergencyAlerts\EmergencyAlertResource;
use App\Models\User;
use App\Notifications\EmergencyAlertNotification;
use Filament\Resources\Pages\CreateRecord;

class CreateEmergencyAlert extends CreateRecord
{
    protected static string $resource = EmergencyAlertResource::class;

    protected function afterCreate(): void
    {
        // Notify all admins
        $admins = User::where('role', 'admin')
            ->where('tenant_id', $this->record->tenant_id)
            ->get();

        foreach ($admins as $admin) {
            $admin->notify(new EmergencyAlertNotification($this->record));
        }
    }
}
