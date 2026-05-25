<?php

namespace App\Filament\Resources\Kavlings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;

class KavlingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_kavling')
                    ->required()
                    ->maxLength(50),
                Select::make('cabang_id')
                    ->label('Cabang')
                    ->relationship('cabang', 'nama')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->default(fn () => auth()->user()?->cabang_id)
                    ->disabled(fn () => auth()->user()?->hasRole('admin-cabang'))
                    ->required()
                    ->live(),
                Select::make('proyek_id')
                    ->label('Proyek')
                    ->relationship('proyek', 'nama_proyek', fn ($query, $get) => $query->where('cabang_id', $get('cabang_id')))
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->required(),
                TextInput::make('kode_kavling')
                    ->required()
                    ->maxLength(20),
                TextInput::make('luas_bangunan_m2')
                    ->label('Luas Bangunan (m2)')
                    ->numeric()
                    ->step(0.01),
                TextInput::make('luas_tanah_m2')
                    ->label('Luas Tanah (m2)')
                    ->numeric()
                    ->step(0.01),
                TextInput::make('progres_bangun')
                    ->label('Progres Bangun')
                    ->maxLength(20),
                TextInput::make('harga')
                    ->numeric()
                    ->step(1000)
                    ->prefix('Rp')
                    ->mask(RawJs::make('$money($input, ".", ",")'))
                    ->dehydrateStateUsing(fn ($state) => (int) preg_replace('/[^0-9]/', '', $state)),
                Select::make('status_kavling')
                    ->label('Status Kavling')
                    ->options([
                        'Tersedia' => 'Tersedia',
                        'Dipesan' => 'Dipesan',
                        'Terjual' => 'Terjual',
                    ])
                    ->default('Tersedia')
                    ->disabled()
                    ->native(false),
            ]);
    }
}
