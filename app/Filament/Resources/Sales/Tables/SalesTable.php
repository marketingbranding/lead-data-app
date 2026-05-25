<?php

namespace App\Filament\Resources\Sales\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SalesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nik_sales')->label('NIK Sales')->sortable()->searchable(),
                TextColumn::make('nama_sales')->label('Nama Sales')->sortable()->searchable(),
                TextColumn::make('nik_koordinator')->label('NIK Koordinator')->sortable()->searchable(),
                TextColumn::make('nama_koordinator')->label('Nama Koordinator')->sortable()->searchable(),
                TextColumn::make('cabang')->sortable()->searchable(),
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
