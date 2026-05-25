<?php

namespace App\Filament\Resources\Expenses\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;

class ExpenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_kavling')
                    ->maxLength(50),
                TextInput::make('nama_pengeluaran')
                    ->required()
                    ->maxLength(200),
                Select::make('kategori')
                    ->searchable()
                    ->options([
                        'operasional' => 'Operasional',
                        'marketing' => 'Marketing',
                        'administrasi' => 'Administrasi',
                        'lainnya' => 'Lainnya',
                    ]),
                TextInput::make('jumlah')
                    ->numeric()
                    ->required()
                    ->mask(RawJs::make('$money($input, ".", ",")'))
                    ->dehydrateStateUsing(fn ($state) => (int) preg_replace('/[^0-9]/', '', $state)),
                DatePicker::make('tanggal'),
                Textarea::make('keterangan'),
                TextInput::make('bukti')
                    ->maxLength(255),
            ]);
    }
}
