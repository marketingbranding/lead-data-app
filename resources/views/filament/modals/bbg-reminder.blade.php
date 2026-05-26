<div class="space-y-3">
    @forelse ($records as $record)
        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-3 dark:border-gray-700 dark:bg-gray-800">
            <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-medium text-gray-900 dark:text-white">
                    {{ $record->konsumen?->nama_konsumen ?? '-' }}
                </p>
                <p class="truncate text-sm text-gray-500 dark:text-gray-400">
                    {{ $record->kavling_id }} — {{ $record->cabang?->nama ?? '-' }}
                </p>
            </div>
            <div class="ml-4 flex items-center gap-4">
                <span class="whitespace-nowrap text-sm text-gray-500">
                    {{ \Carbon\Carbon::parse($record->tgl_bbg_due)->format('d M Y') }}
                </span>
                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $record->bbg_remaining_days <= 7 ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' }}">
                    {{ $record->bbg_remaining_days }} hari lagi
                </span>
            </div>
        </div>
    @empty
        <p class="py-6 text-center text-sm text-gray-500 dark:text-gray-400">
            Tidak ada BBG yang akan expired dalam 30 hari ke depan.
        </p>
    @endforelse
</div>
