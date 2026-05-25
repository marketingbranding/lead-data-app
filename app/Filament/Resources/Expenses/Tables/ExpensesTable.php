<?php

namespace App\Filament\Resources\Expenses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ExpensesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_pengeluaran')->sortable()->searchable(),
                TextColumn::make('kategori')->sortable()->searchable(),
                TextColumn::make('jumlah')->money('IDR', decimalPlaces: 0, locale: 'id')->sortable(),
                TextColumn::make('tanggal')->date()->sortable(),
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
