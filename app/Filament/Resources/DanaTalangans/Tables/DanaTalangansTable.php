<?php

namespace App\Filament\Resources\DanaTalangans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DanaTalangansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cabang.nama')->label('Cabang')->sortable()->searchable(),
                TextColumn::make('proyek.nama_proyek')->label('Proyek')->sortable()->searchable(),
                TextColumn::make('kavling_id')->label('Blok/Kav')->sortable()->searchable(),
                TextColumn::make('konsumen.nama_konsumen')->label('Nama Konsumen')->sortable()->searchable(),
                TextColumn::make('bank.bank')->label('Bank')->sortable(),
                TextColumn::make('tgl_akad')->label('Tgl Akad')->date()->sortable(),
                TextColumn::make('tgl_bbg_due')->label('Tgl BBG Due')->date()->sortable(),
                TextColumn::make('status_bbg')
                    ->label('Status BBG')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Active' ? 'success' : 'danger')
                    ->sortable(),
                TextColumn::make('bbg_reminder')
                    ->label('BBG Reminder')
                    ->getStateUsing(fn ($record) => $record->bbg_remaining_days !== null && $record->bbg_remaining_days <= 30
                        ? $record->bbg_remaining_days . ' hari lagi'
                        : '-')
                    ->badge()
                    ->color(fn (string $state): string => $state !== '-' ? 'warning' : 'gray')
                    ->visibleFrom('md'),
                TextColumn::make('tgl_pengajuan_dana_talangan')->label('Tgl Pengajuan')->date()->sortable(),
                TextColumn::make('tgl_pengembalian_dana_talangan')->label('Tgl Pengembalian')->date()->sortable(),
                TextColumn::make('penyelesaian')->label('Penyelesaian')->limit(30)->searchable(),
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
