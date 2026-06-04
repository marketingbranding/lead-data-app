<?php

namespace App\Filament\Resources\Proyeks\Pages;

use App\Filament\Actions\HasCreateTutupAction;
use App\Filament\Resources\Proyeks\ProyekResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProyek extends CreateRecord
{
    use HasCreateTutupAction;

    protected static string $resource = ProyekResource::class;
}
