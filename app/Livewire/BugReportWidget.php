<?php

namespace App\Livewire;

use App\Models\BugReport;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class BugReportWidget extends Component
{
    public bool $open = false;

    public string $judul = '';

    public string $deskripsi = '';

    public string $prioritas = 'sedang';

    public function toggle()
    {
        $this->open = !$this->open;
    }

    public function submit()
    {
        $this->validate([
            'judul' => 'required|max:255',
            'deskripsi' => 'required',
            'prioritas' => 'required|in:rendah,sedang,tinggi,kritis',
        ]);

        $report = BugReport::create([
            'user_id' => auth()->id(),
            'judul' => $this->judul,
            'deskripsi' => $this->deskripsi,
            'prioritas' => $this->prioritas,
        ]);

        $this->sendToDiscord($report);

        $this->reset(['judul', 'deskripsi', 'prioritas', 'open']);

        Notification::make()
            ->title('Laporan terkirim')
            ->body('Terima kasih, laporan bug sudah kami terima.')
            ->success()
            ->send();
    }

    protected function sendToDiscord(BugReport $report): void
    {
        $webhookUrl = config('services.discord.bug_report_webhook');
        if (!$webhookUrl) {
            Log::warning('Discord webhook URL not configured');
            return;
        }

        Log::info('Sending bug report to Discord', [
            'report_id' => $report->id,
            'judul' => $report->judul,
        ]);

        $user = $report->user;
        $cabang = $user?->cabang?->nama ?? '-';

        $prioritasEmoji = match ($report->prioritas) {
            'rendah' => '🟢',
            'sedang' => '🟡',
            'tinggi' => '🟠',
            'kritis' => '🔴',
            default => '⚪',
        };

        $payload = json_encode([
            'embeds' => [[
                'title' => $report->judul,
                'description' => $report->deskripsi,
                'color' => match ($report->prioritas) {
                    'rendah' => 5763719,
                    'sedang' => 16776960,
                    'tinggi' => 15105570,
                    'kritis' => 15548997,
                    default => 9807270,
                },
                'fields' => [
                    ['name' => 'Cabang', 'value' => $cabang, 'inline' => true],
                    ['name' => 'Pelapor', 'value' => $user?->name ?? '-', 'inline' => true],
                    ['name' => 'Prioritas', 'value' => "$prioritasEmoji {$report->prioritas}", 'inline' => true],
                    ['name' => 'Status', 'value' => $report->status, 'inline' => true],
                    ['name' => 'Waktu', 'value' => $report->created_at->format('d M Y H:i'), 'inline' => true],
                ],
                'footer' => ['text' => 'Bug Report • OASIS'],
                'timestamp' => $report->created_at->toIso8601String(),
            ]],
        ]);

        $ch = curl_init($webhookUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $res = curl_exec($ch);
        $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($http !== 204) {
            Log::warning('Discord webhook failed', [
                'status' => $http,
                'error' => $error,
                'body' => $res,
            ]);
        }
    }

    public function render()
    {
        return view('livewire.bug-report-widget');
    }
}
