<?php

namespace App\Filament\Widgets;

use App\Models\BugReport;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class BugReportWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Laporan Bug Terbaru';

    public function table(Table $table): Table
    {
        $query = BugReport::with('user.cabang')->latest()->limit(5);

        if (!auth()->user()?->hasRole('super-admin')) {
            return $table->query($query->whereRaw('0=1'));
        }

        return $table
            ->query($query)
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Pelapor')
                    ->sortable(),
                TextColumn::make('user.cabang.nama')
                    ->label('Cabang')
                    ->sortable(),
                TextColumn::make('judul')
                    ->label('Judul')
                    ->limit(40)
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
            ]);
    }

    public static function canView(): bool
    {
        return auth()->user()?->hasRole('super-admin');
    }
}
