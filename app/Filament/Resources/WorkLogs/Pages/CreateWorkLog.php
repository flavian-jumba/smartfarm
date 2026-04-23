<?php

namespace App\Filament\Resources\WorkLogs\Pages;

use App\Filament\Resources\WorkLogs\WorkLogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWorkLog extends CreateRecord
{
    protected static string $resource = WorkLogResource::class;
}
