<?php

namespace App\Filament\Resources\MonitoringJalans\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MonitoringJalansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('periode')
                    ->label('Periode')
                    ->date()
                    ->sortable(),
                TextColumn::make('cabang.nama')
                    ->label('Cabang')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('proyek.nama_proyek')
                    ->label('Proyek')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('total_konsumen_survey')
                    ->label('Total Survey')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('konsumen_insiden_jalan')
                    ->label('Insiden Jalan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('batal_beli_karena_jalan')
                    ->label('Batal Beli')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('catatan_lapangan')
                    ->label('Catatan')
                    ->limit(50)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('periode', 'desc');
    }
}
