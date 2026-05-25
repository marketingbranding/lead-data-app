<?php

namespace App\Filament\Resources\LeadTimes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LeadTimesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_lead_time')->sortable(),
                TextColumn::make('tahap_awal')->sortable()->searchable(),
                TextColumn::make('tahap_tujuan')->sortable()->searchable(),
                TextColumn::make('proses')->sortable()->searchable(),
                TextColumn::make('target_hari_kerja')->sortable(),
            ])
            ->filters([
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
