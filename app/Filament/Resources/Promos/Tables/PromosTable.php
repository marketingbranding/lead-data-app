<?php

namespace App\Filament\Resources\Promos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PromosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_promo')->label('ID Promo')->sortable()->searchable(),
                TextColumn::make('nama_promo')->label('Nama Promo')->sortable()->searchable(),
                TextColumn::make('tanggal_mulai')->label('Tanggal Mulai')->date()->sortable(),
                TextColumn::make('tanggal_selesai')->label('Tanggal Selesai')->date()->sortable(),
                TextColumn::make('keterangan')->sortable()->toggleable(isToggledHiddenByDefault: true),
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
