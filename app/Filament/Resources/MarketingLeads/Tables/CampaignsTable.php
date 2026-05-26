<?php

namespace App\Filament\Resources\MarketingLeads\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use App\Models\Cabang;
use App\Models\Proyek;

class CampaignsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('campaign_id')
                    ->label('ID Kampanye')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('proyek.nama_proyek')
                    ->label('Proyek')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('kategori_promosi')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Online' ? 'info' : 'warning')
                    ->sortable(),
                TextColumn::make('sumber_promosi')
                    ->label('Sumber')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('tanggal_mulai')
                    ->label('Mulai')
                    ->date()
                    ->sortable(),
                TextColumn::make('tanggal_selesai')
                    ->label('Selesai')
                    ->date()
                    ->sortable(),
                TextColumn::make('budget')
                    ->label('Budget')
                    ->money('IDR', decimalPlaces: 0, locale: 'id')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Draft' => 'gray',
                        'Berlangsung' => 'success',
                        'Jeda' => 'warning',
                        'Selesai' => 'info',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('cabang.nama')
                    ->label('Cabang')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                        Select::make('status')
                            ->label('Status')
                            ->placeholder('Semua Status')
                            ->options([
                                'Draft' => 'Draft',
                                'Berlangsung' => 'Berlangsung',
                                'Jeda' => 'Jeda',
                                'Selesai' => 'Selesai',
                            ]),
                    ])
                    ->query(fn ($query, $data) => $query
                        ->when(auth()->user()?->hasRole('admin-cabang'), fn ($q) => $q->where('cabang_id', auth()->user()->cabang_id))
                        ->when($data['cabang_id'] ?? null, fn ($q, $v) => $q->where('cabang_id', $v))
                        ->when($data['proyek_id'] ?? null, fn ($q, $v) => $q->where('proyek_id', $v))
                        ->when($data['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
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
