<?php

namespace App\Filament\Resources\Basts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class BastForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Placeholder::make('id_kavling')
                    ->label('Kavling')
                    ->content(fn ($record, $get) => new HtmlString(
                        e($record?->kavling?->id_kavling ?? $get('id_kavling') ?? '-')
                    ))
                    ->columnSpanFull(),
                DatePicker::make('tanggal_bast')
                    ->required()
                    ->live(onBlur: true),
                TextInput::make('lead_time_hari')
                    ->numeric()
                    ->disabled(),
                Placeholder::make('status')
                    ->label('Status')
                    ->content(fn ($record) => new HtmlString(
                        match ($record?->status) {
                            'terlambat' => '<span style="color:#dc2626;font-weight:bold">Terlambat</span>',
                            'ontime' => '<span style="color:#16a34a;font-weight:bold">Ontime</span>',
                            default => '<span style="color:#6b7280">-</span>',
                        }
                    ))
                    ->columnSpanFull(),
                Textarea::make('keterangan'),
                Placeholder::make('status_data')
                    ->label('Status Data')
                    ->content(fn ($get) => new HtmlString(
                        !blank($get('tanggal_bast'))
                            ? '<span style="color:#16a34a;font-weight:bold">Data Lengkap</span>'
                            : '<span style="color:#dc2626;font-weight:bold">Data Belum Lengkap</span>'
                    ))
                    ->columnSpanFull(),
            ]);
    }
}
