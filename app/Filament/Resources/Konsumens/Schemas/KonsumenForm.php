<?php

namespace App\Filament\Resources\Konsumens\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class KonsumenForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('id_kavling')
                    ->label('Kavling')
                    ->relationship('kavling', 'id_kavling')
                    ->searchable()
                    ->preload()
                    ->optionsLimit(9999)
                    ->native(false),
                TextInput::make('no_ktp')
                    ->label('No. KTP')
                    ->required()
                    ->maxLength(50)
                    ->live(onBlur: true),
                TextInput::make('nama_konsumen')
                    ->label('Nama Konsumen')
                    ->required()
                    ->maxLength(200)
                    ->live(onBlur: true),
                DatePicker::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->required()
                    ->live(onBlur: true),
                TextInput::make('pekerjaan')
                    ->required()
                    ->maxLength(100)
                    ->live(onBlur: true),
                TextInput::make('detail_pekerjaan')
                    ->label('Detail Pekerjaan')
                    ->maxLength(200),
                TextInput::make('umur')
                    ->numeric(),
                Textarea::make('alamat')
                    ->required()
                    ->rows(3)
                    ->live(onBlur: true),
                TextInput::make('kelurahan')
                    ->required()
                    ->maxLength(100)
                    ->live(onBlur: true),
                TextInput::make('kecamatan')
                    ->required()
                    ->maxLength(100)
                    ->live(onBlur: true),
                TextInput::make('kabupaten_kota')
                    ->label('Kabupaten/Kota')
                    ->required()
                    ->maxLength(100)
                    ->live(onBlur: true),
                TextInput::make('no_hp')
                    ->label('No. HP')
                    ->required()
                    ->maxLength(30)
                    ->live(onBlur: true),
                TextInput::make('nama_kondar')
                    ->label('Nama Kondar')
                    ->maxLength(100),
                TextInput::make('no_hp_kondar')
                    ->label('No. HP Kondar')
                    ->maxLength(30),
                Select::make('status_cash')
                    ->label('Skema Pembayaran')
                    ->options([
                        'TIDAK' => 'KPR',
                        'YA' => 'Cash',
                    ])
                    ->native(false),
                Placeholder::make('status_data')
                    ->label('Status Data')
                    ->content(fn ($get) => new HtmlString(
                        !blank($get('nama_konsumen')) && !blank($get('no_ktp')) && !blank($get('no_hp'))
                        && !blank($get('pekerjaan')) && !blank($get('tanggal_lahir')) && !blank($get('alamat'))
                        && !blank($get('kelurahan')) && !blank($get('kecamatan')) && !blank($get('kabupaten_kota'))
                            ? '<span style="color:#16a34a;font-weight:bold">Data Lengkap</span>'
                            : '<span style="color:#dc2626;font-weight:bold">Data Belum Lengkap</span>'
                    ))
                    ->columnSpanFull(),
                Textarea::make('keterangan')
                    ->rows(3),
            ]);
    }
}
