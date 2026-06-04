<div
    x-data="{ open: false }"
    x-on:click.away="open = false"
    class="fixed bottom-6 right-6 z-50"
>
    {{-- Bubble button --}}
    <button
        x-on:click="open = !open"
        class="flex items-center justify-center w-14 h-14 rounded-full shadow-lg text-white transition-all duration-200"
        :class="open ? 'bg-gray-600 rotate-45' : 'bg-amber-500 hover:bg-amber-600'"
        title="Laporkan Bug"
    >
        <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
        </svg>
        <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
    </button>

    {{-- Form card --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        class="absolute bottom-16 right-0 w-80 sm:w-96 bg-white rounded-xl shadow-2xl border overflow-hidden"
        style="display: none;"
    >
        {{-- Header --}}
        <div class="bg-amber-500 text-white px-4 py-3 flex items-center justify-between">
            <span class="font-semibold">Laporkan Bug</span>
            <button x-on:click="open = false" class="text-white/80 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        {{-- Form --}}
        <div class="p-4">
            @if ($errors->any())
                <div class="mb-3 p-2 bg-red-50 text-red-600 text-sm rounded border border-red-200">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                    <input
                        type="text"
                        wire:model="judul"
                        class="w-full rounded-lg border-gray-300 text-sm focus:border-amber-500 focus:ring-amber-500"
                        placeholder="Ringkasan masalah..."
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea
                        wire:model="deskripsi"
                        rows="3"
                        class="w-full rounded-lg border-gray-300 text-sm focus:border-amber-500 focus:ring-amber-500"
                        placeholder="Jelaskan masalah secara detail..."
                    ></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prioritas</label>
                    <select
                        wire:model="prioritas"
                        class="w-full rounded-lg border-gray-300 text-sm focus:border-amber-500 focus:ring-amber-500"
                    >
                        <option value="rendah">🟢 Rendah</option>
                        <option value="sedang">🟡 Sedang</option>
                        <option value="tinggi">🟠 Tinggi</option>
                        <option value="kritis">🔴 Kritis</option>
                    </select>
                </div>
            </div>

            <div class="mt-4 flex gap-2">
                <button
                    type="button"
                    x-on:click="open = false"
                    class="flex-1 px-4 py-2 text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg"
                >Batal</button>
                <button
                    type="button"
                    wire:click="submit"
                    wire:loading.attr="disabled"
                    class="flex-1 px-4 py-2 text-sm text-white bg-amber-500 hover:bg-amber-600 rounded-lg font-medium disabled:opacity-50"
                >
                    <span wire:loading.remove wire:target="submit">Kirim</span>
                    <span wire:loading wire:target="submit">Mengirim...</span>
                </button>
            </div>
        </div>
    </div>
</div>
