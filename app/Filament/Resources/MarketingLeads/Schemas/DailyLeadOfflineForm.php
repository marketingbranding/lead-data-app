<?php

namespace App\Filament\Resources\MarketingLeads\Schemas;

use App\Models\Campaign;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DailyLeadOfflineForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('campaign_id')
                    ->label('ID Kampanye (Offline)')
                    ->required()
                    ->searchable()
                    ->options(fn () => Campaign::where('status', 'Berlangsung')
                        ->where('kategori_promosi', 'Offline')
                        ->when(auth()->user()?->cabang_id, fn ($q, $v) => $q->where('cabang_id', $v))
                        ->get()
                        ->mapWithKeys(fn ($c) => [$c->id => "{$c->campaign_id} - {$c->proyek?->nama_proyek} ({$c->sumber_promosi})"])
                    ),
                DatePicker::make('tanggal_laporan')
                    ->label('Tanggal Laporan')
                    ->required()
                    ->default(now()),
                TextInput::make('lead_didapat')
                    ->label('Lead Didapat (Masuk)')
                    ->required()
                    ->numeric()
                    ->minValue(0),
                TextInput::make('kunjungan_lokasi')
                    ->label('Kunjungan Lokasi')
                    ->numeric()
                    ->minValue(0),
                TextInput::make('closing_utj')
                    ->label('Closing (UTJ)')
                    ->numeric()
                    ->minValue(0),
            ]);
    }
}
