<?php

namespace App\Filament\Resources\Sales\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SaleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nik_sales')
                    ->label('NIK Sales')
                    ->required()
                    ->maxLength(50),
                TextInput::make('nama_sales')
                    ->label('Nama Sales')
                    ->required()
                    ->maxLength(100),
                TextInput::make('nik_koordinator')
                    ->label('NIK Koordinator')
                    ->maxLength(50),
                TextInput::make('nama_koordinator')
                    ->label('Nama Koordinator')
                    ->maxLength(100),
                TextInput::make('cabang')
                    ->maxLength(100),
                Select::make('status')
                    ->options([
                        'Aktif' => 'Aktif',
                        'OUT' => 'OUT',
                        'OJT' => 'OJT',
                    ])
                    ->default('Aktif')
                    ->native(false),
            ]);
    }
}
