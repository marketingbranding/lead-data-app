<?php

namespace App\Filament\Pages;

use App\Models\Campaign;
use App\Models\DailyLeadOffline;
use App\Models\DailyLeadOnline;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Html;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class InputHarian extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPencilSquare;

    protected static ?string $title = 'Input Harian';

    protected static UnitEnum|string|null $navigationGroup = 'Penjualan & Marketing';

    protected static ?int $navigationSort = 7;

    public string $tab = 'offline';

    public ?int $campaign_id = null;

    public string $tanggal_laporan;

    public ?int $lead_didapat = 0;

    public ?int $kunjungan_lokasi = null;

    public ?int $closing_utj = null;

    public ?int $klik_tautan = 0;

    public ?int $lead_masuk = 0;

    public ?int $respon = 0;

    public ?int $tahap_diskusi = 0;

    public ?int $cek_lokasi = 0;

    public function mount(): void
    {
        $this->tanggal_laporan = now()->toDateString();
    }

    public function updatedTab(string $value): void
    {
        $this->resetForm();
        $this->tanggal_laporan = now()->toDateString();
    }

    public function submit(): void
    {
        if ($this->tab === 'offline') {
            $this->validate([
                'campaign_id' => 'required|exists:campaigns,id',
                'tanggal_laporan' => 'required|date',
                'lead_didapat' => 'required|integer|min:0',
                'kunjungan_lokasi' => 'nullable|integer|min:0',
                'closing_utj' => 'nullable|integer|min:0',
            ]);

            $exists = DailyLeadOffline::where('campaign_id', $this->campaign_id)
                ->where('tanggal_laporan', $this->tanggal_laporan)
                ->exists();

            if ($exists) {
                $this->addError('tanggal_laporan', 'Data untuk kampanye dan tanggal ini sudah ada.');

                return;
            }

            DailyLeadOffline::create([
                'campaign_id' => $this->campaign_id,
                'tanggal_laporan' => $this->tanggal_laporan,
                'lead_didapat' => $this->lead_didapat ?? 0,
                'kunjungan_lokasi' => $this->kunjungan_lokasi,
                'closing_utj' => $this->closing_utj,
            ]);

            Notification::make()
                ->title('Data Offline berhasil disimpan')
                ->success()
                ->send();
        } else {
            $this->validate([
                'campaign_id' => 'required|exists:campaigns,id',
                'tanggal_laporan' => 'required|date',
                'klik_tautan' => 'required|integer|min:0',
                'lead_masuk' => 'required|integer|min:0',
                'respon' => 'required|integer|min:0',
                'tahap_diskusi' => 'required|integer|min:0',
                'cek_lokasi' => 'required|integer|min:0',
                'closing_utj' => 'required|integer|min:0',
            ]);

            $exists = DailyLeadOnline::where('campaign_id', $this->campaign_id)
                ->where('tanggal_laporan', $this->tanggal_laporan)
                ->exists();

            if ($exists) {
                $this->addError('tanggal_laporan', 'Data untuk kampanye dan tanggal ini sudah ada.');

                return;
            }

            DailyLeadOnline::create([
                'campaign_id' => $this->campaign_id,
                'tanggal_laporan' => $this->tanggal_laporan,
                'klik_tautan' => $this->klik_tautan ?? 0,
                'lead_masuk' => $this->lead_masuk ?? 0,
                'respon' => $this->respon ?? 0,
                'tahap_diskusi' => $this->tahap_diskusi ?? 0,
                'cek_lokasi' => $this->cek_lokasi ?? 0,
                'closing_utj' => $this->closing_utj ?? 0,
            ]);

            Notification::make()
                ->title('Data Online berhasil disimpan')
                ->success()
                ->send();
        }

        $this->resetForm();
        $this->tanggal_laporan = now()->toDateString();
    }

    protected function resetForm(): void
    {
        $this->reset(
            'campaign_id',
            'lead_didapat',
            'kunjungan_lokasi',
            'closing_utj',
            'klik_tautan',
            'lead_masuk',
            'respon',
            'tahap_diskusi',
            'cek_lokasi',
        );
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Input Harian')
                    ->livewireProperty('tab')
                    ->contained(false)
                    ->tabs([
                        Tab::make('Offline')
                            ->key('offline')
                            ->icon('heroicon-o-clipboard-document')
                            ->schema([
                                Section::make('Input Data Offline')
                                    ->description('Catat data harian campaign Offline')
                                    ->icon('heroicon-o-clipboard-document')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Select::make('campaign_id')
                                                    ->label('Kampanye')
                                                    ->required()
                                                    ->searchable()
                                                    ->placeholder('Pilih kampanye')
                                                    ->options(fn (): array => $this->getCampaignOptions('Offline')),
                                                DatePicker::make('tanggal_laporan')
                                                    ->label('Tanggal Laporan')
                                                    ->required(),
                                            ]),
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('lead_didapat')
                                                    ->label('Lead Didapat')
                                                    ->required()
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->default(0),
                                                TextInput::make('kunjungan_lokasi')
                                                    ->label('Kunjungan Lokasi')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->helperText('Kosongkan jika tidak ada'),
                                            ]),
                                        TextInput::make('closing_utj')
                                            ->label('Closing (UTJ)')
                                            ->numeric()
                                            ->minValue(0)
                                            ->helperText('Kosongkan jika tidak ada'),
                                    ])
                                    ->footer([$this->submitButton()]),
                            ]),
                        Tab::make('Online')
                            ->key('online')
                            ->icon('heroicon-o-chart-bar')
                            ->schema([
                                Section::make('Input Data Online')
                                    ->description('Catat data harian campaign Online')
                                    ->icon('heroicon-o-chart-bar')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Select::make('campaign_id')
                                                    ->label('Kampanye')
                                                    ->required()
                                                    ->searchable()
                                                    ->placeholder('Pilih kampanye')
                                                    ->options(fn (): array => $this->getCampaignOptions('Online')),
                                                DatePicker::make('tanggal_laporan')
                                                    ->label('Tanggal Laporan')
                                                    ->required(),
                                            ]),
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('klik_tautan')
                                                    ->label('Klik Tautan')
                                                    ->required()
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->default(0),
                                                TextInput::make('lead_masuk')
                                                    ->label('Lead Masuk')
                                                    ->required()
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->default(0),
                                            ]),
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('respon')
                                                    ->label('Respon')
                                                    ->required()
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->default(0),
                                                TextInput::make('tahap_diskusi')
                                                    ->label('Tahap Diskusi')
                                                    ->required()
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->default(0),
                                            ]),
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('cek_lokasi')
                                                    ->label('Cek Lokasi')
                                                    ->required()
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->default(0),
                                                TextInput::make('closing_utj')
                                                    ->label('Closing (UTJ)')
                                                    ->required()
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->default(0),
                                            ]),
                                    ])
                                    ->footer([$this->submitButton()]),
                            ]),
                    ]),
            ]);
    }

    protected function submitButton(): Html
    {
        return Html::make(\Illuminate\Support\Facades\Blade::render(
            '<div class="flex justify-end mt-6">' .
            '<x-filament::button wire:click="submit" color="primary" size="lg">Simpan</x-filament::button>' .
            '</div>',
        ))->key('submit_button');
    }

    protected function getCampaignOptions(string $kategori): array
    {
        return Campaign::where('status', 'Berlangsung')
            ->where('kategori_promosi', $kategori)
            ->when(auth()->user()?->cabang_id, fn (Builder $q, $v) => $q->where('cabang_id', $v))
            ->get()
            ->mapWithKeys(fn (Campaign $c) => [
                $c->id => "{$c->campaign_id} - {$c->proyek?->nama_proyek} ({$c->sumber_promosi})",
            ])
            ->toArray();
    }
}
