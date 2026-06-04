<?php

namespace App\Filament\Resources\BugReports\Tables;

use App\Filament\Resources\BugReports\BugReportResource;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BugReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Pelapor')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user.cabang.nama')
                    ->label('Cabang')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('judul')
                    ->label('Judul')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('prioritas')
                    ->label('Prioritas')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'rendah' => 'gray',
                        'sedang' => 'warning',
                        'tinggi' => 'orange',
                        'kritis' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'baru' => 'danger',
                        'dibaca' => 'warning',
                        'diproses' => 'info',
                        'selesai' => 'success',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('prioritas')
                    ->options([
                        'rendah' => 'Rendah',
                        'sedang' => 'Sedang',
                        'tinggi' => 'Tinggi',
                        'kritis' => 'Kritis',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'baru' => 'Baru',
                        'dibaca' => 'Dibaca',
                        'diproses' => 'Diproses',
                        'selesai' => 'Selesai',
                    ]),
            ])
            ->recordActions([
                EditAction::make()
                    ->url(fn ($record) => BugReportResource::getUrl('edit', ['record' => $record])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
