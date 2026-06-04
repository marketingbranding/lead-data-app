<div
    x-data="{ open: false }"
    x-on:click.away="open = false"
    style="position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 50;"
>
    {{-- Bubble button --}}
    <button
        x-on:click="open = !open"
        :style="open
            ? 'display: flex; align-items: center; justify-content: center; width: 3.5rem; height: 3.5rem; border-radius: 9999px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); color: white; transition: all 0.2s; background: #4b5563; transform: rotate(45deg);'
            : 'display: flex; align-items: center; justify-content: center; width: 3.5rem; height: 3.5rem; border-radius: 9999px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); color: white; transition: all 0.2s; background: #d97706;'"
        style="display: flex; align-items: center; justify-content: center; width: 3.5rem; height: 3.5rem; border-radius: 9999px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); color: white; transition: all 0.2s; background: #d97706;"
        title="Laporkan Bug"
    >
        <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" style="width: 1.75rem; height: 1.75rem;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
        </svg>
        <svg x-show="open" xmlns="http://www.w3.org/2000/svg" style="width: 1.75rem; height: 1.75rem; display: none;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
        style="position: absolute; bottom: 4rem; right: 0; width: 20rem; background: white; border-radius: 0.75rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); border: 1px solid #e5e7eb; overflow: hidden; display: none;"
    >
        {{-- Header --}}
        <div style="background: #d97706; color: white; padding: 0.75rem 1rem; display: flex; align-items: center; justify-content: space-between;">
            <span style="font-weight: 600;">Laporkan Bug</span>
            <button x-on:click="open = false" style="color: rgba(255,255,255,0.8); background: none; border: none; cursor: pointer;">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        {{-- Form --}}
        <div style="padding: 1rem; color: #111827;">
            @if ($errors->any())
                <div style="margin-bottom: 0.75rem; padding: 0.5rem; background: #fef2f2; color: #dc2626; font-size: 0.875rem; border-radius: 0.5rem; border: 1px solid #fca5a5;">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">Judul</label>
                    <input
                        type="text"
                        wire:model="judul"
                        style="width: 100%; border-radius: 0.5rem; border: 1px solid #d1d5db; font-size: 0.875rem; padding: 0.5rem 0.75rem; box-sizing: border-box; color: #111827;"
                        placeholder="Ringkasan masalah..."
                    >
                </div>

                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">Deskripsi</label>
                    <textarea
                        wire:model="deskripsi"
                        rows="3"
                        style="width: 100%; border-radius: 0.5rem; border: 1px solid #d1d5db; font-size: 0.875rem; padding: 0.5rem 0.75rem; box-sizing: border-box; resize: vertical; color: #111827;"
                        placeholder="Jelaskan masalah secara detail..."
                    ></textarea>
                </div>

                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">Prioritas</label>
                    <select
                        wire:model="prioritas"
                        style="width: 100%; border-radius: 0.5rem; border: 1px solid #d1d5db; font-size: 0.875rem; padding: 0.5rem 0.75rem; box-sizing: border-box; background: white; color: #111827;"
                    >
                        <option value="rendah" style="color: #111827;">Rendah</option>
                        <option value="sedang" style="color: #111827;">Sedang</option>
                        <option value="tinggi" style="color: #111827;">Tinggi</option>
                        <option value="kritis" style="color: #111827;">Kritis</option>
                    </select>
                </div>
            </div>

            <div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
                <button
                    type="button"
                    x-on:click="open = false"
                    style="flex: 1; padding: 0.5rem 1rem; font-size: 0.875rem; color: #4b5563; background: #f3f4f6; border: none; border-radius: 0.5rem; cursor: pointer;"
                >Batal</button>
                <button
                    type="button"
                    wire:click="submit"
                    wire:loading.attr="disabled"
                    style="flex: 1; padding: 0.5rem 1rem; font-size: 0.875rem; color: white; background: #d97706; border: none; border-radius: 0.5rem; font-weight: 500; cursor: pointer; opacity: 1;"
                    wire:loading.class="opacity-50"
                >
                    <span wire:loading.remove wire:target="submit">Kirim</span>
                    <span wire:loading wire:target="submit">Mengirim...</span>
                </button>
            </div>
        </div>
    </div>
</div>
