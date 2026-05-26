<?php

namespace App\Filament\Resources\MarketingLeads\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DailyLeadOfflinesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('campaign.campaign_id')
                    ->label('Kampanye')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('campaign.proyek.nama_proyek')
                    ->label('Proyek')
                    ->sortable(),
                TextColumn::make('tanggal_laporan')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                TextColumn::make('lead_didapat')
                    ->label('Lead')
                    ->sortable(),
                TextColumn::make('kunjungan_lokasi')
                    ->label('Kunjungan')
                    ->sortable(),
                TextColumn::make('closing_utj')
                    ->label('UTJ')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
