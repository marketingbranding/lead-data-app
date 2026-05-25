<?php

namespace App\Filament\Resources\PipelineLogs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PipelineLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_kavling')->sortable()->searchable(),
                TextColumn::make('tahap_asal')->sortable()->searchable(),
                TextColumn::make('tahap_tujuan')->sortable()->searchable(),
                TextColumn::make('tanggal_masuk')->date()->sortable(),
                TextColumn::make('tanggal_keluar')->date()->sortable(),
                TextColumn::make('lead_time_hari')->sortable(),
                TextColumn::make('status')->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
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
