<?php

namespace App\Filament\Resources\Proyeks\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProyekForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_proyek')
                    ->label('Nama Proyek')
                    ->required()
                    ->maxLength(255),
                Select::make('cabang_id')
                    ->label('Cabang')
                    ->relationship('cabang', 'nama')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->required(),
            ]);
    }
}
