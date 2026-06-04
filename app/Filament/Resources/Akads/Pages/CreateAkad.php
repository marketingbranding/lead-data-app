<?php

namespace App\Filament\Resources\Akads\Pages;

use App\Filament\Actions\HasCreateTutupAction;
use App\Filament\Resources\Akads\AkadResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAkad extends CreateRecord
{
    use HasCreateTutupAction;

    protected static string $resource = AkadResource::class;
}
