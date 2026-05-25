<?php

namespace App\Filament\Resources\PipelineLogs\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PipelineLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_kavling')
                    ->required()
                    ->maxLength(50),
                Select::make('tahap_asal')
                    ->searchable()
                    ->options([
                        'PSJB' => 'PSJB',
                        'Pemberkasan' => 'Pemberkasan',
                        'Proses Bank' => 'Proses Bank',
                        'PPJB Dev' => 'PPJB Dev',
                        'Akad' => 'Akad',
                        'BAST' => 'BAST',
                    ]),
                Select::make('tahap_tujuan')
                    ->searchable()
                    ->options([
                        'PSJB' => 'PSJB',
                        'Pemberkasan' => 'Pemberkasan',
                        'Proses Bank' => 'Proses Bank',
                        'PPJB Dev' => 'PPJB Dev',
                        'Akad' => 'Akad',
                        'BAST' => 'BAST',
                    ]),
                DatePicker::make('tanggal_masuk'),
                DatePicker::make('tanggal_keluar'),
                TextInput::make('lead_time_hari')
                    ->numeric(),
                Select::make('status')
                    ->searchable()
                    ->options([
                        'ontime' => 'Ontime',
                        'terlambat' => 'Terlambat',
                    ]),
                Textarea::make('keterangan'),
            ]);
    }
}
