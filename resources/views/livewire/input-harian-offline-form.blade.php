<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
    <h3 class="text-base font-semibold text-gray-900 mb-1">Input Data Offline</h3>
    <p class="text-sm text-gray-500 mb-6">Catat data harian campaign Offline</p>

    {{-- Campaign & Tanggal --}}
    <div class="space-y-4">
        <div>
            <label for="campaign_id" class="block text-sm font-medium text-gray-700 mb-1">
                Kampanye <span class="text-danger-500">*</span>
            </label>
            <select
                id="campaign_id"
                wire:model="campaign_id"
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-base py-3"
            >
                <option value="">-- Pilih Kampanye --</option>
                @foreach ($this->campaigns as $id => $label)
                    <option value="{{ $id }}">{{ $label }}</option>
                @endforeach
            </select>
            @error('campaign_id')
                <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="tanggal_laporan" class="block text-sm font-medium text-gray-700 mb-1">
                Tanggal Laporan <span class="text-danger-500">*</span>
            </label>
            <input
                id="tanggal_laporan"
                type="date"
                wire:model="tanggal_laporan"
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-base py-3"
            >
            @error('tanggal_laporan')
                <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <hr class="my-5 border-gray-200">

    {{-- Metrik Offline --}}
    <div class="space-y-4">
        <div>
            <label for="lead_didapat" class="block text-sm font-medium text-gray-700 mb-1">
                Lead Didapat (Masuk) <span class="text-danger-500">*</span>
            </label>
            <input
                id="lead_didapat"
                type="number"
                min="0"
                placeholder="0"
                wire:model="lead_didapat"
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-base py-3"
            >
            @error('lead_didapat')
                <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="kunjungan_lokasi" class="block text-sm font-medium text-gray-700 mb-1">
                Kunjungan Lokasi
            </label>
            <input
                id="kunjungan_lokasi"
                type="number"
                min="0"
                placeholder="0"
                wire:model="kunjungan_lokasi"
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-base py-3"
            >
            <p class="mt-1 text-xs text-gray-400">Kosongkan jika tidak ada</p>
            @error('kunjungan_lokasi')
                <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="closing_utj" class="block text-sm font-medium text-gray-700 mb-1">
                Closing (UTJ)
            </label>
            <input
                id="closing_utj"
                type="number"
                min="0"
                placeholder="0"
                wire:model="closing_utj"
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-base py-3"
            >
            <p class="mt-1 text-xs text-gray-400">Kosongkan jika tidak ada</p>
            @error('closing_utj')
                <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <hr class="my-5 border-gray-200">

    {{-- Submit --}}
    <button
        type="button"
        wire:click="submit"
        wire:loading.attr="disabled"
        wire:target="submit"
        class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-primary-600 text-white text-base font-medium rounded-lg shadow-sm hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition"
    >
        <svg wire:loading.remove wire:target="submit" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <svg wire:loading wire:target="submit" class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
        </svg>
        <span wire:loading.remove wire:target="submit">Simpan</span>
        <span wire:loading wire:target="submit">Menyimpan...</span>
    </button>
</div>
