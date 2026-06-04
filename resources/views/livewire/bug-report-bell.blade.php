<div
    x-data="{ open: false }"
    x-on:click.away="open = false"
    style="position: relative; display: inline-flex; align-items: center;"
>
    <button
        x-on:click="
            open = !open;
            if (open) { $wire.markAllRead() }
        "
        style="position: relative; background: transparent; border: none; cursor: pointer; padding: 0.5rem; display: flex; align-items: center; justify-content: center; outline: none;"
        title="Laporan Bug"
    >
        <svg xmlns="http://www.w3.org/2000/svg" style="width: 1.5rem; height: 1.5rem; color: #6b7280;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
        </svg>

        @if ($unreadCount > 0)
            <span style="position: absolute; top: 0; right: 0; transform: translate(25%, -25%); display: flex; align-items: center; justify-content: center; min-width: 1.25rem; height: 1.25rem; padding: 0 0.25rem; border-radius: 9999px; background: #dc2626; color: white; font-size: 0.75rem; font-weight: 700; line-height: 1; box-sizing: border-box;">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        style="position: absolute; top: 100%; right: 0; margin-top: 0.5rem; width: 24rem; background: white; border-radius: 0.75rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); border: 1px solid #e5e7eb; overflow: hidden; z-index: 9999; display: none;"
    >
        <div style="padding: 0.75rem 1rem; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: space-between;">
            <span style="font-size: 0.875rem; font-weight: 600; color: #111827;">Laporan Bug</span>
            @if ($unreadCount > 0)
                <span style="font-size: 0.75rem; color: #6b7280;">{{ $unreadCount }} belum dibaca</span>
            @endif
        </div>

        <div style="max-height: 24rem; overflow-y: auto;">
            @forelse ($reports as $report)
                <button
                    type="button"
                    wire:click="openReport({{ $report->id }})"
                    wire:loading.attr="disabled"
                    style="display: block; width: 100%; text-align: left; padding: 0.75rem 1rem; border: none; border-bottom: 1px solid #f3f4f6; background: {{ $report->status === 'baru' ? '#fffbeb' : 'white' }}; cursor: pointer; transition: background 0.15s; font-family: inherit;"
                >
                    <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 0.5rem;">
                        <div style="flex: 1; min-width: 0;">
                            <div style="font-size: 0.875rem; font-weight: 500; color: #111827; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ Str::limit($report->judul, 50) }}
                            </div>
                            <div style="margin-top: 0.25rem; font-size: 0.75rem; color: #6b7280;">
                                {{ $report->user?->cabang?->nama ?? '-' }} &middot; {{ $report->created_at->diffForHumans() }}
                            </div>
                        </div>
                        <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.25rem; flex-shrink: 0;">
                            <span style="display: inline-flex; padding: 0.125rem 0.5rem; font-size: 0.625rem; font-weight: 600; border-radius: 9999px; text-transform: uppercase; letter-spacing: 0.05em; {{ $report->prioritas === 'kritis' ? 'background: #fef2f2; color: #dc2626;' : ($report->prioritas === 'tinggi' ? 'background: #fff7ed; color: #ea580c;' : ($report->prioritas === 'sedang' ? 'background: #fefce8; color: #ca8a04;' : 'background: #f9fafb; color: #6b7280;')) }}">
                                {{ $report->prioritas }}
                            </span>
                            <span style="display: inline-flex; padding: 0.125rem 0.5rem; font-size: 0.625rem; font-weight: 600; border-radius: 9999px; text-transform: uppercase; letter-spacing: 0.05em; {{ $report->status === 'baru' ? 'background: #fef2f2; color: #dc2626;' : ($report->status === 'dibaca' ? 'background: #fefce8; color: #ca8a04;' : ($report->status === 'diproses' ? 'background: #eff6ff; color: #2563eb;' : 'background: #f0fdf4; color: #16a34a;')) }}">
                                {{ $report->status }}
                            </span>
                        </div>
                    </div>
                </button>
            @empty
                <div style="padding: 2rem 1rem; text-align: center; font-size: 0.875rem; color: #9ca3af;">
                    Belum ada laporan bug
                </div>
            @endforelse
        </div>

        <a
            href="{{ \App\Filament\Resources\BugReports\BugReportResource::getUrl('index') }}"
            style="display: block; padding: 0.75rem 1rem; text-align: center; font-size: 0.875rem; color: #d97706; text-decoration: none; border-top: 1px solid #e5e7eb; background: white;"
        >
            Lihat Semua Laporan
        </a>
    </div>
</div>
