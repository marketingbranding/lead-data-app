<?php

namespace App\Filament\Resources\ProsesBanks\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;
use Illuminate\Support\HtmlString;

class ProsesBankForm
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
                TextInput::make('id_berkas')
                    ->maxLength(20),
                TextInput::make('no_sp3k')
                    ->required()
                    ->maxLength(50)
                    ->live(onBlur: true),
                Select::make('jenis_respon')
                    ->required()
                    ->live()
                    ->searchable()
                    ->options([
                        'Approved' => 'Approved',
                        'Approved Tenor' => 'Approved Tenor',
                        'Approved Turun Plafond' => 'Approved Turun Plafond',
                        'Reject' => 'Reject',
                        'Revisi' => 'Revisi',
                        'CASH' => 'CASH',
                    ]),
                TextInput::make('approved_plafond')
                    ->required()
                    ->numeric()
                    ->live(onBlur: true)
                    ->mask(RawJs::make('$money($input, ".", ",")'))
                    ->dehydrateStateUsing(fn ($state) => (int) preg_replace('/[^0-9]/', '', $state)),
                TextInput::make('approved_tenor')
                    ->maxLength(20),
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
                TextInput::make('kategori_revisi')
                    ->maxLength(100),
                Textarea::make('detail_revisi'),
                Textarea::make('keterangan'),
                Placeholder::make('status_data')
                    ->label('Status Data')
                    ->content(fn ($get) => new HtmlString(
                        !blank($get('no_sp3k')) && !blank($get('jenis_respon')) && !blank($get('approved_plafond'))
                            ? '<span style="color:#16a34a;font-weight:bold">Data Lengkap</span>'
                            : '<span style="color:#dc2626;font-weight:bold">Data Belum Lengkap</span>'
                    ))
                    ->columnSpanFull(),
            ]);
    }
}
