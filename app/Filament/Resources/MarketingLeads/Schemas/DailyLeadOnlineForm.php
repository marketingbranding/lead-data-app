<?php

namespace App\Filament\Resources\MarketingLeads\Schemas;

use App\Models\Campaign;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DailyLeadOnlineForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('campaign_id')
                    ->label('ID Kampanye (Online)')
                    ->required()
                    ->searchable()
                    ->options(fn () => Campaign::where('status', 'Berlangsung')
                        ->where('kategori_promosi', 'Online')
                        ->when(auth()->user()?->cabang_id, fn ($q, $v) => $q->where('cabang_id', $v))
                        ->get()
                        ->mapWithKeys(fn ($c) => [$c->id => "{$c->campaign_id} - {$c->proyek?->nama_proyek} ({$c->sumber_promosi})"])
                    ),
                DatePicker::make('tanggal_laporan')
                    ->label('Tanggal Laporan')
                    ->required()
                    ->default(now()),
                TextInput::make('klik_tautan')
                    ->label('Klik Tautan')
                    ->required()
                    ->numeric()
                    ->minValue(0),
                TextInput::make('lead_masuk')
                    ->label('Lead Masuk')
                    ->required()
                    ->numeric()
                    ->minValue(0),
                TextInput::make('respon')
                    ->label('Respon')
                    ->required()
                    ->numeric()
                    ->minValue(0),
                TextInput::make('tahap_diskusi')
                    ->label('Tahap Diskusi')
                    ->required()
                    ->numeric()
                    ->minValue(0),
                TextInput::make('cek_lokasi')
                    ->label('Cek Lokasi')
                    ->required()
                    ->numeric()
                    ->minValue(0),
                TextInput::make('closing_utj')
                    ->label('Closing (UTJ)')
                    ->required()
                    ->numeric()
                    ->minValue(0),
            ]);
    }
}
