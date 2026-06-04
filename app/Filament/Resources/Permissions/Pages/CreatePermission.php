<?php

namespace App\Filament\Resources\Permissions\Pages;

use App\Filament\Actions\HasCreateTutupAction;
use App\Filament\Resources\Permissions\PermissionResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePermission extends CreateRecord
{
    use HasCreateTutupAction;

    protected static string $resource = PermissionResource::class;
}
