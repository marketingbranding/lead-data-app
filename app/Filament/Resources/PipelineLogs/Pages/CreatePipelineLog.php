<?php

namespace App\Filament\Resources\PipelineLogs\Pages;

use App\Filament\Actions\HasCreateTutupAction;
use App\Filament\Resources\PipelineLogs\PipelineLogResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePipelineLog extends CreateRecord
{
    use HasCreateTutupAction;

    protected static string $resource = PipelineLogResource::class;
}
