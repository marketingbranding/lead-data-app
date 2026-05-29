<?php

namespace App\Filament\Resources\MonitoringJalans\Pages;

use App\Filament\Resources\MonitoringJalans\MonitoringJalanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMonitoringJalans extends ListRecords
{
    protected static string $resource = MonitoringJalanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
