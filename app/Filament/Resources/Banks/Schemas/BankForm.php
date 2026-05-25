<?php

namespace App\Filament\Resources\Banks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BankForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('bank')
                    ->required()
                    ->maxLength(100),
                TextInput::make('kc_unit')
                    ->label('KC/Unit')
                    ->maxLength(100),
            ]);
    }
}
