<?php

namespace App\Filament\Resources\Cabangs\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CabangForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->label('Nama Cabang')
                    ->required()
                    ->maxLength(100),
                TextInput::make('urutan')
                    ->label('Urutan')
                    ->required()
                    ->numeric()
                    ->minValue(0),
            ]);
    }
}
