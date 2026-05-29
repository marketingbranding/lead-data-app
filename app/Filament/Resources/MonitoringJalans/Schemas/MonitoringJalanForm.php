<?php

namespace App\Filament\Resources\MonitoringJalans\Schemas;

use App\Models\Cabang;
use App\Models\Proyek;
use App\Rules\MondayOnly;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MonitoringJalanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                DatePicker::make('periode')
                    ->label('Periode')
                    ->required()
                    ->default(now()->startOfWeek())
                    ->rules([new MondayOnly()])
                    ->helperText('Pilih tanggal Senin pada minggu laporan'),
                Select::make('cabang_id')
                    ->label('Cabang')
                    ->required()
                    ->searchable()
                    ->options(fn (): array => Cabang::pluck('nama', 'id')->toArray()),
                Select::make('proyek_id')
                    ->label('Proyek')
                    ->required()
                    ->searchable()
                    ->options(fn (): array => Proyek::pluck('nama_proyek', 'id')->toArray()),
                TextInput::make('total_konsumen_survey')
                    ->label('Total Konsumen Survey')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
                TextInput::make('konsumen_insiden_jalan')
                    ->label('Konsumen Mengalami Insiden Jalan')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
                TextInput::make('batal_beli_karena_jalan')
                    ->label('Batal Beli Karena Kondisi Jalan')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
                Textarea::make('catatan_lapangan')
                    ->label('Catatan Lapangan')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }
}
