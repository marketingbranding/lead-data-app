<?php

namespace App\Filament\Resources\Psjbs\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;
use Illuminate\Support\HtmlString;

class PsjbForm
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
                DatePicker::make('tanggal_psjb')
                    ->required()
                    ->live(onBlur: true),
                Select::make('nama_koordinator')
                    ->label('Nama Koordinator')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->options(fn () => \App\Models\Sales::whereNotNull('nama_koordinator')->where('nama_koordinator', '!=', '')->distinct()->pluck('nama_koordinator', 'nama_koordinator'))
                    ->required()
                    ->live(),
                Select::make('nama_sales')
                    ->label('Nama Sales')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->options(function ($get) {
                        $query = \App\Models\Sales::query();
                        if ($get('nama_koordinator')) {
                            $query->where('nama_koordinator', $get('nama_koordinator'));
                        }
                        return $query->pluck('nama_sales', 'nama_sales');
                    })
                    ->required()
                    ->live(),
                TextInput::make('harga_unit')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->live(onBlur: true)
                    ->mask(RawJs::make('$money($input, ".", ",")'))
                    ->dehydrateStateUsing(fn ($state) => (int) preg_replace('/[^0-9]/', '', $state)),
                DatePicker::make('tanggal_utj'),
                TextInput::make('utj')
                    ->numeric()
                    ->prefix('Rp')
                    ->mask(RawJs::make('$money($input, ".", ",")'))
                    ->dehydrateStateUsing(fn ($state) => (int) preg_replace('/[^0-9]/', '', $state)),
                DatePicker::make('tanggal_dp_klt'),
                TextInput::make('dp')
                    ->numeric()
                    ->prefix('Rp')
                    ->mask(RawJs::make('$money($input, ".", ",")'))
                    ->dehydrateStateUsing(fn ($state) => (int) preg_replace('/[^0-9]/', '', $state)),
                TextInput::make('klt')
                    ->numeric()
                    ->prefix('Rp')
                    ->mask(RawJs::make('$money($input, ".", ",")'))
                    ->dehydrateStateUsing(fn ($state) => (int) preg_replace('/[^0-9]/', '', $state)),
                TextInput::make('detail_klt')
                    ->maxLength(255),
                Select::make('cara_pembayaran')
                    ->required()
                    ->live()
                    ->searchable()
                    ->options([
                        'Cash' => 'Cash',
                        'Cash Bertahap' => 'Cash Bertahap',
                        'Cash Promo' => 'Cash Promo',
                        'KPR Indent' => 'KPR Indent',
                        'KPR Platinum' => 'KPR Platinum',
                        'KPR Non Subsidi' => 'KPR Non Subsidi',
                        'FLPP' => 'FLPP',
                        'BP2BT' => 'BP2BT',
                        'TAPERA' => 'TAPERA',
                        'KPR SUBSIDI BANK' => 'KPR SUBSIDI BANK',
                    ]),
                Select::make('id_promo')
                    ->label('Promo')
                    ->relationship('promo', 'nama_promo')
                    ->searchable()
                    ->preload(),
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
                        !blank($get('tanggal_psjb')) && !blank($get('nama_koordinator')) && !blank($get('nama_sales')) && !blank($get('harga_unit')) && !blank($get('cara_pembayaran'))
                            ? '<span style="color:#16a34a;font-weight:bold">Data Lengkap</span>'
                            : '<span style="color:#dc2626;font-weight:bold">Data Belum Lengkap</span>'
                    ))
                    ->columnSpanFull(),
            ]);
    }
}
