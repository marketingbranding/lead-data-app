<?php

namespace App\Livewire;

use App\Models\BugReport;
use Livewire\Component;

class BugReportBell extends Component
{
    public int $unreadCount = 0;

    public function mount(): void
    {
        $this->refresh();
    }

    public function refresh(): void
    {
        $this->unreadCount = BugReport::where('status', 'baru')->count();
    }

    public function markAllRead(): void
    {
        BugReport::where('status', 'baru')->update(['status' => 'dibaca']);
        $this->refresh();
    }

    public function openReport(int $id): void
    {
        $report = BugReport::find($id);
        if ($report && $report->status === 'dibaca') {
            $report->update(['status' => 'diproses']);
        }

        $this->redirect(\App\Filament\Resources\BugReports\BugReportResource::getUrl('edit', ['record' => $id]));
    }

    public function render()
    {
        $reports = BugReport::with('user.cabang')->latest()->take(5)->get();

        return view('livewire.bug-report-bell', ['reports' => $reports]);
    }
}
