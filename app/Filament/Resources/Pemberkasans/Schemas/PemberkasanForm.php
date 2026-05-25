<?php

namespace App\Filament\Resources\Pemberkasans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;
use Illuminate\Support\HtmlString;

class PemberkasanForm
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
                Select::make('tipe_pemberkasan')
                    ->required()
                    ->options([
                        'registrasi' => 'Registrasi',
                        'CASH' => 'CASH',
                    ])
                    ->live(),
                DatePicker::make('tanggal_terima_bank')
                    ->required(fn ($get) => $get('tipe_pemberkasan') !== 'CASH')
                    ->hidden(fn ($get) => $get('tipe_pemberkasan') === 'CASH')
                    ->live(onBlur: true),
                TextInput::make('bank')
                    ->required(fn ($get) => $get('tipe_pemberkasan') !== 'CASH')
                    ->maxLength(100)
                    ->hidden(fn ($get) => $get('tipe_pemberkasan') === 'CASH')
                    ->live(onBlur: true),
                TextInput::make('kc_unit')
                    ->maxLength(100)
                    ->hidden(fn ($get) => $get('tipe_pemberkasan') === 'CASH'),
                TextInput::make('request_plafond')
                    ->numeric()
                    ->hidden(fn ($get) => $get('tipe_pemberkasan') === 'CASH')
                    ->mask(RawJs::make('$money($input, ".", ",")'))
                    ->dehydrateStateUsing(fn ($state) => (int) preg_replace('/[^0-9]/', '', $state)),
                TextInput::make('request_tenor')
                    ->numeric()
                    ->hidden(fn ($get) => $get('tipe_pemberkasan') === 'CASH'),
                TextInput::make('lead_time_hari')
                    ->numeric()
                    ->disabled(),
                Placeholder::make('status')
                    ->label('Status')
                    ->content(fn ($record) => new HtmlString(
                        match ($record?->status) {
                            'terlambat' => '<span style="color:#dc2626;font-weight:bold">Terlambat</span>',
                            'ontime' => '<span style="color:#16a34a;font-weight:bold">Ontime</span>',
                            default => '<span style="color:#6b7280">-</span>',
                        }
                    ))
                    ->columnSpanFull(),
                Textarea::make('keterangan'),
                Placeholder::make('status_data')
                    ->label('Status Data')
                    ->content(fn ($get) => new HtmlString(
                        (function () use ($get) {
                            if (blank($get('tipe_pemberkasan'))) return false;
                            if ($get('tipe_pemberkasan') === 'CASH') return true;
                            return !blank($get('tanggal_terima_bank')) && !blank($get('bank'));
                        })()
                            ? '<span style="color:#16a34a;font-weight:bold">Data Lengkap</span>'
                            : '<span style="color:#dc2626;font-weight:bold">Data Belum Lengkap</span>'
                    ))
                    ->columnSpanFull(),
            ]);
    }
}
