<?php

namespace App\Filament\Resources\MarketingLeads\Schemas;

use App\Models\Proyek;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;

class CampaignForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('campaign_id')
                    ->label('ID Kampanye')
                    ->disabled()
                    ->dehydrated(false)
                    ->columnSpanFull(),
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
                    ->label('Nama Proyek')
                    ->required()
                    ->searchable()
                    ->live()
                    ->options(fn ($get) => Proyek::where('cabang_id', $get('cabang_id') ?? auth()->user()?->cabang_id)
                        ->pluck('nama_proyek', 'id')),
                Select::make('kategori_promosi')
                    ->label('Kategori Promosi')
                    ->required()
                    ->live()
                    ->options([
                        'Online' => 'Online',
                        'Offline' => 'Offline',
                    ]),
                Select::make('sumber_promosi')
                    ->label('Sumber Promosi')
                    ->required()
                    ->searchable()
                    ->options([
                        'FB Ads' => 'FB Ads',
                        'IG Ads' => 'IG Ads',
                        'Google Ads' => 'Google Ads',
                        'Brosur' => 'Brosur',
                        'Umbul-Umbul' => 'Umbul-Umbul',
                        'Open House' => 'Open House',
                        'Event Mall' => 'Event Mall',
                        'Lainnya' => 'Lainnya',
                    ]),
                DatePicker::make('tanggal_mulai')
                    ->label('Tanggal Mulai')
                    ->required()
                    ->live(onBlur: true),
                DatePicker::make('tanggal_selesai')
                    ->label('Tanggal Selesai')
                    ->required()
                    ->afterOrEqual('tanggal_mulai')
                    ->live(onBlur: true),
                TextInput::make('budget')
                    ->label('Budget Promosi')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->mask(RawJs::make('$money($input, ".", ",")'))
                    ->dehydrateStateUsing(fn ($state) => (int) preg_replace('/[^0-9]/', '', $state)),
                Select::make('status')
                    ->required()
                    ->options([
                        'Draft' => 'Draft',
                        'Berlangsung' => 'Berlangsung',
                        'Jeda' => 'Jeda',
                        'Selesai' => 'Selesai',
                    ]),
                Textarea::make('catatan')
                    ->label('Catatan'),
            ]);
    }
}
