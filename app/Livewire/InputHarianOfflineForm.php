<?php

namespace App\Livewire;

use App\Models\Campaign;
use App\Models\DailyLeadOffline;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class InputHarianOfflineForm extends Component
{
    public ?int $campaign_id = null;

    public string $tanggal_laporan;

    public ?int $lead_didapat = 0;

    public ?int $kunjungan_lokasi = null;

    public ?int $closing_utj = null;

    public function mount(): void
    {
        $this->tanggal_laporan = now()->toDateString();
    }

    public function getCampaignsProperty(): array
    {
        return Campaign::where('status', 'Berlangsung')
            ->where('kategori_promosi', 'Offline')
            ->when(auth()->user()?->cabang_id, fn ($q, $v) => $q->where('cabang_id', $v))
            ->get()
            ->mapWithKeys(fn ($c) => [
                $c->id => "{$c->campaign_id} - {$c->proyek?->nama_proyek} ({$c->sumber_promosi})",
            ])
            ->toArray();
    }

    public function submit(): void
    {
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

        $this->reset('campaign_id', 'lead_didapat', 'kunjungan_lokasi', 'closing_utj');
        $this->tanggal_laporan = now()->toDateString();
    }

    public function render(): View
    {
        return view('livewire.input-harian-offline-form');
    }
}
