<div class="space-y-6">
    <div>
        <label for="campaign_id" class="block text-sm font-medium text-gray-700 mb-1">
            Kampanye
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
            Tanggal Laporan
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

    <div>
        <label for="lead_didapat" class="block text-sm font-medium text-gray-700 mb-1">
            Lead Didapat (Masuk)
        </label>
        <input
            id="lead_didapat"
            type="number"
            min="0"
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
            wire:model="kunjungan_lokasi"
            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-base py-3"
        >
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
            wire:model="closing_utj"
            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-base py-3"
        >
        @error('closing_utj')
            <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
        @enderror
    </div>

    <button
        type="button"
        wire:click="submit"
        wire:loading.attr="disabled"
        wire:target="submit"
        class="w-full inline-flex items-center justify-center px-4 py-3 bg-primary-600 text-white text-base font-medium rounded-lg shadow-sm hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition"
    >
        <span wire:loading.remove wire:target="submit">Simpan</span>
        <span wire:loading wire:target="submit">Menyimpan...</span>
    </button>
</div>
