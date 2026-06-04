<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Actions\HasCreateTutupAction;
use App\Filament\Resources\Roles\RoleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRole extends CreateRecord
{
    use HasCreateTutupAction;

    protected static string $resource = RoleResource::class;
}
