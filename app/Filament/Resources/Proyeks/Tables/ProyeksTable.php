<?php

namespace App\Filament\Resources\Proyeks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProyeksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_proyek')->label('Nama Proyek')->sortable()->searchable(),
                TextColumn::make('cabang.nama')->label('Cabang')->sortable()->searchable(),
                TextColumn::make('kavlings_count')->label('Jumlah Kavling')->counts('kavlings')->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
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
