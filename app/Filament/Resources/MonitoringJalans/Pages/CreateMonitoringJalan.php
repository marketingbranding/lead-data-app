<?php

namespace App\Filament\Resources\MonitoringJalans\Pages;

use App\Filament\Actions\HasCreateTutupAction;
use App\Filament\Resources\MonitoringJalans\MonitoringJalanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMonitoringJalan extends CreateRecord
{
    use HasCreateTutupAction;

    protected static string $resource = MonitoringJalanResource::class;
}
