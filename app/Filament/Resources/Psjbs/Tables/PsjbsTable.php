<?php

namespace App\Filament\Resources\Psjbs\Tables;

use App\Services\MundurService;
use App\Services\PipelineFlowService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use App\Models\Cabang;
use App\Models\Proyek;

class PsjbsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_kavling')->sortable()->searchable(),
                TextColumn::make('konsumen')
                    ->label('Konsumen')
                    ->getStateUsing(fn ($record) => $record->kavling?->konsumens()->where('status_konsumen', 'aktif')->first()?->nama_konsumen)
                    ->toggleable(),
                TextColumn::make('nama_koordinator')->sortable()->searchable(),
                TextColumn::make('nama_sales')->sortable()->searchable(),
                TextColumn::make('tanggal_psjb')->date()->sortable(),
                TextColumn::make('cara_pembayaran')->sortable()->searchable(),
                TextColumn::make('promo.nama_promo')->label('Promo')->sortable()->searchable(),
                TextColumn::make('lead_time_hari')->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'terlambat' ? 'danger' : 'success')
                    ->sortable(),
                TextColumn::make('status_data')
                    ->label('Status Data')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Data Lengkap' ? 'success' : 'danger')
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
                    ->query(fn ($query, $data) => $query->whereHas('kavling', fn ($q) =>
                        $q
                            ->when(auth()->user()?->hasRole('admin-cabang'), fn ($q) => $q->where('cabang_id', auth()->user()->cabang_id))
                            ->when($data['cabang_id'] ?? null, fn ($q, $v) => $q->where('cabang_id', $v))
                            ->when($data['proyek_id'] ?? null, fn ($q, $v) => $q->where('proyek_id', $v))
                    )),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->label('')
                        ->icon('heroicon-m-pencil-square'),
                    Action::make('mundur')
                        ->label('')
                        ->icon('heroicon-o-arrow-left-circle')
                        ->color('danger')
                        ->tooltip('Mundur')
                        ->requiresConfirmation()
                        ->modalHeading('Mundurkan Proses')
                        ->modalDescription('Apakah Anda yakin ingin memundurkan proses ini? Konsumen akan ditandai sebagai mundur.')
                        ->visible(fn ($record) => $record->kavling?->konsumens()->where('status_konsumen', 'aktif')->exists())
                        ->action(fn ($record) => app(MundurService::class)->mundurkan($record)),
                ]),
                Action::make('lanjutTahap')
                    ->label(fn ($record) => app(PipelineFlowService::class)->getNextStageLabel($record))
                    ->icon('heroicon-o-arrow-right-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status_data === 'Data Lengkap')
                    ->action(fn ($record) => redirect(app(PipelineFlowService::class)->getNextStageEditUrl($record))),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn ($query) => $query->whereDoesntHave('kavling.pemberkasan'));
    }
}
