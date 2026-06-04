<?php

namespace App\Filament\Resources\BiCheckings\Pages;

use App\Filament\Actions\HasCreateTutupAction;
use App\Filament\Resources\BiCheckings\BiCheckingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBiChecking extends CreateRecord
{
    use HasCreateTutupAction;

    protected static string $resource = BiCheckingResource::class;
}
