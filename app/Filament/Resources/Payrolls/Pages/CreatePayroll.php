<?php

namespace App\Filament\Resources\Payrolls\Pages;

use App\Filament\Resources\Payrolls\PayrollResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePayroll extends CreateRecord
{
    protected static string $resource = PayrollResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['processed_by'] = auth()->id();

        // Calculate net amount if not set
        if (empty($data['net_amount'])) {
            $data['net_amount'] = ($data['base_amount'] ?? 0) + ($data['bonus_amount'] ?? 0) - ($data['deductions'] ?? 0);
        }

        return $data;
    }
}
