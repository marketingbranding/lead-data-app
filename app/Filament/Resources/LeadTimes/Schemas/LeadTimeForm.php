<?php

namespace App\Filament\Resources\LeadTimes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LeadTimeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tahap_awal')
                    ->required()
                    ->maxLength(50),
                TextInput::make('tahap_tujuan')
                    ->required()
                    ->maxLength(50),
                TextInput::make('proses')
                    ->required()
                    ->maxLength(100),
                TextInput::make('target_hari_kerja')
                    ->required()
                    ->numeric()
                    ->minValue(1),
            ]);
    }
}
