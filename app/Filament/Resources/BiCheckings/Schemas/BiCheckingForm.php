<?php

namespace App\Filament\Resources\BiCheckings\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class BiCheckingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Placeholder::make('id_kavling')
                    ->label('Kavling')
                    ->content(fn ($record, $get) => new HtmlString(
                        e($record?->kavling?->id_kavling ?? $get('id_kavling') ?? '-')
                    ))
                    ->columnSpanFull(),
                TextInput::make('no_ktp')
                    ->required()
                    ->maxLength(20)
                    ->live(onBlur: true),
                DatePicker::make('tanggal_slik')
                    ->required()
                    ->live(onBlur: true),
                Select::make('hasil_slik')
                    ->required()
                    ->live()
                    ->options([
                        'OK' => 'OK',
                        'KOL 1' => 'KOL 1',
                        'KOL 2' => 'KOL 2',
                        'KOL 5' => 'KOL 5',
                    ]),
                Textarea::make('keterangan'),
                Placeholder::make('status_data')
                    ->label('Status Data')
                    ->content(fn ($get) => new HtmlString(
                        !blank($get('no_ktp')) && !blank($get('tanggal_slik')) && !blank($get('hasil_slik'))
                            ? '<span style="color:#16a34a;font-weight:bold">Data Lengkap</span>'
                            : '<span style="color:#dc2626;font-weight:bold">Data Belum Lengkap</span>'
                    ))
                    ->columnSpanFull(),
            ]);
    }
}
