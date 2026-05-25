<?php

namespace App\Filament\Resources\PipelineLogs\Pages;

use App\Filament\Resources\PipelineLogs\PipelineLogResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPipelineLog extends EditRecord
{
    protected static string $resource = PipelineLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
