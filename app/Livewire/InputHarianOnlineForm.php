<?php

namespace App\Livewire;

use App\Models\Campaign;
use App\Models\DailyLeadOnline;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class InputHarianOnlineForm extends Component
{
    public ?int $campaign_id = null;

    public string $tanggal_laporan;

    public ?int $klik_tautan = 0;

    public ?int $lead_masuk = 0;

    public ?int $respon = 0;

    public ?int $tahap_diskusi = 0;

    public ?int $cek_lokasi = 0;

    public ?int $closing_utj = 0;

    public function mount(): void
    {
        $this->tanggal_laporan = now()->toDateString();
    }

    public function getCampaignsProperty(): array
    {
        return Campaign::where('status', 'Berlangsung')
            ->where('kategori_promosi', 'Online')
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

        $this->reset('campaign_id', 'klik_tautan', 'lead_masuk', 'respon', 'tahap_diskusi', 'cek_lokasi', 'closing_utj');
        $this->tanggal_laporan = now()->toDateString();
    }

    public function render(): View
    {
        return view('livewire.input-harian-online-form');
    }
}
