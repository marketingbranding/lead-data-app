<?php

namespace App\Filament\Resources\MonitoringJalans\Pages;

use App\Filament\Resources\MonitoringJalans\MonitoringJalanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMonitoringJalan extends EditRecord
{
    protected static string $resource = MonitoringJalanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
