<?php

namespace App\Filament\Auth\Pages;

use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;

class Login extends BaseLogin
{
    public function getHeading(): string | Htmlable | null
    {
        return 'OASIS';
    }

    public function getSubheading(): string | Htmlable | null
    {
        return 'Online & Offline Sales Integration System';
    }
}
