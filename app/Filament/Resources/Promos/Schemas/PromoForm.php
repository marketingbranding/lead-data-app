<?php

namespace App\Filament\Resources\Promos\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PromoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_promo')
                    ->label('ID Promo')
                    ->required()
                    ->maxLength(50),
                TextInput::make('nama_promo')
                    ->label('Nama Promo')
                    ->required()
                    ->maxLength(200),
                DatePicker::make('tanggal_mulai')
                    ->label('Tanggal Mulai'),
                DatePicker::make('tanggal_selesai')
                    ->label('Tanggal Selesai'),
                Textarea::make('keterangan')
                    ->rows(3),
            ]);
    }
}
