<?php

namespace App\Filament\Resources\DanaTalangans\Schemas;

use App\Models\Bank;
use App\Models\Kavling;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class DanaTalanganForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('cabang_id')
                    ->label('Cabang')
                    ->relationship('cabang', 'nama')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->default(fn () => auth()->user()?->cabang_id)
                    ->disabled(fn () => auth()->user()?->hasRole('admin-cabang'))
                    ->required()
                    ->live(),
                Select::make('proyek_id')
                    ->label('Proyek')
                    ->relationship('proyek', 'nama_proyek', fn ($query, $get) => $query->where('cabang_id', $get('cabang_id') ?? auth()->user()?->cabang_id))
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->live(),
                Select::make('kavling_id')
                    ->label('Blok/Kav')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->optionsLimit(9999)
                    ->options(fn ($get) => Kavling::query()
                        ->when($get('cabang_id') ?? auth()->user()?->cabang_id, fn ($q, $v) => $q->where('cabang_id', $v))
                        ->when($get('proyek_id'), fn ($q, $v) => $q->where('proyek_id', $v))
                        ->pluck('id_kavling', 'id_kavling'))
                    ->required()
                    ->live(),
                Select::make('konsumen_id')
                    ->label('Nama Konsumen')
                    ->relationship('konsumen', 'nama_konsumen')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->optionsLimit(9999)
                    ->required(),
                Select::make('bank_id')
                    ->label('Bank')
                    ->relationship('bank', 'bank')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->required(),
                DatePicker::make('tgl_akad')
                    ->label('Tgl Akad')
                    ->required()
                    ->live(),
                Placeholder::make('tgl_bbg_due')
                    ->label('Tgl BBG Due')
                    ->content(fn ($get) => $get('tgl_akad')
                        ? Carbon::parse($get('tgl_akad'))->addYear()->format('d M Y')
                        : '-'),
                Placeholder::make('status_bbg')
                    ->label('Status BBG')
                    ->content(fn ($record) => $record?->status_bbg ?? '-')
                    ->hint(fn ($record) => $record?->bbg_remaining_days !== null
                        ? $record->bbg_remaining_days . ' hari lagi'
                        : ($record?->status_bbg === 'Expired' ? 'Sudah expired' : '')),
                DatePicker::make('tgl_pengajuan_dana_talangan')
                    ->label('Tgl Pengajuan Dana Talangan'),
                DatePicker::make('tgl_pengembalian_dana_talangan')
                    ->label('Tgl Pengembalian Dana Talangan'),
                Textarea::make('penyelesaian')
                    ->label('Penyelesaian')
                    ->rows(3),
            ]);
    }
}
