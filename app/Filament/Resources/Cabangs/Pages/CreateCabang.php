<?php

namespace App\Filament\Resources\Cabangs\Pages;

use App\Filament\Actions\HasCreateTutupAction;
use App\Filament\Resources\Cabangs\CabangResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCabang extends CreateRecord
{
    use HasCreateTutupAction;

    protected static string $resource = CabangResource::class;
}
