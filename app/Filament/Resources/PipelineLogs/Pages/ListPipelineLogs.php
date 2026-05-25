<?php

namespace App\Filament\Resources\PipelineLogs\Pages;

use App\Filament\Actions\HasExportImport;
use App\Filament\Resources\PipelineLogs\PipelineLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPipelineLogs extends ListRecords
{
    use HasExportImport;

    protected static string $resource = PipelineLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getExportImportActions(),
            CreateAction::make(),
        ];
    }
}
