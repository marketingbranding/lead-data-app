<?php

namespace App\Filament\Resources\Kavlings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use App\Models\Cabang;
use App\Models\Proyek;

class KavlingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_kavling')->sortable()->searchable(),
                TextColumn::make('cabang.nama')->label('Cabang')->sortable()->searchable(),
                TextColumn::make('proyek.nama_proyek')->label('Proyek')->sortable()->searchable(),
                TextColumn::make('kode_kavling')->sortable()->searchable(),
                TextColumn::make('luas_bangunan_m2')->label('Luas Bangunan (m2)')->sortable(),
                TextColumn::make('luas_tanah_m2')->label('Luas Tanah (m2)')->sortable(),
                TextColumn::make('progres_bangun')->label('Progres Bangun')->sortable(),
                TextColumn::make('harga')->money('IDR', decimalPlaces: 0, locale: 'id')->sortable(),
                TextColumn::make('status_kavling')
                    ->label('Status Kavling')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Tersedia' => 'success',
                        'Dipesan' => 'warning',
                        'Terjual' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('cabang_proyek')
                    ->form([
                        Select::make('cabang_id')
                            ->label('Cabang')
                            ->placeholder('Semua Cabang')
                            ->options(Cabang::pluck('nama', 'id'))
                            ->hidden(fn () => auth()->user()?->hasRole('admin-cabang'))
                            ->live(),
                        Select::make('proyek_id')
                            ->label('Proyek')
                            ->placeholder('Semua Proyek')
                            ->options(fn ($get) => Proyek::when(
                                $get('cabang_id') ?? (auth()->user()?->hasRole('admin-cabang') ? auth()->user()->cabang_id : null),
                                fn ($q, $v) => $q->where('cabang_id', $v)
                            )->pluck('nama_proyek', 'id')),
                    ])
                    ->query(fn ($query, $data) => $query
                        ->when(auth()->user()?->hasRole('admin-cabang'), fn ($q) => $q->where('cabang_id', auth()->user()->cabang_id))
                        ->when($data['cabang_id'] ?? null, fn ($q, $v) => $q->where('cabang_id', $v))
                        ->when($data['proyek_id'] ?? null, fn ($q, $v) => $q->where('proyek_id', $v))
                    ),
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
