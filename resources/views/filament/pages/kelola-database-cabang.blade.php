@php
    $scriptUrl = config('oasisync.script_url', 'https://script.google.com/.../exec');
    $cardColors = ['bg-amber-50', 'bg-blue-50', 'bg-indigo-50', 'bg-pink-50', 'bg-emerald-50', 'bg-fuchsia-50', 'bg-lime-50', 'bg-orange-50'];
    $icons = ['heroicon-o-circle-stack', 'heroicon-o-table-cells', 'heroicon-o-squares-2x2', 'heroicon-o-server', 'heroicon-o-server-stack', 'heroicon-o-window', 'heroicon-o-rectangle-stack', 'heroicon-o-folder'];
@endphp

<x-filament::page>
    <div class="space-y-6">
        <x-filament::section>
            <x-slot name="heading">
                Database Cabang
            </x-slot>

            <x-slot name="description">
                Kelola database Google Sheet masing-masing cabang. Klik card untuk membuka halaman database cabang di tab baru.
            </x-slot>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 mt-4">
                @foreach($this->getCabangs() as $cabang)
                    <a
                        href="{{ $scriptUrl }}?cabang_id={{ $cabang->id }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="{{ $cardColors[$loop->index % 8] }} block rounded-xl p-7 text-center transition-all duration-200 hover:scale-105 hover:shadow-lg border border-transparent hover:border-gray-200"
                    >
                        <x-filament::icon
                            icon="{{ $icons[$loop->index % 8] }}"
                            class="w-12 h-12 mx-auto mb-3 text-gray-600"
                        />
                        <h3 class="font-semibold text-base text-gray-800">{{ $cabang->nama }}</h3>
                        <p class="text-xs text-gray-500 mt-0.5">ID: {{ $cabang->id }} · 24 tab</p>
                    </a>
                @endforeach
            </div>

            @if(count($this->getCabangs()) === 0)
                <div class="text-center py-8 text-gray-500">
                    <x-filament::icon
                        icon="heroicon-o-circle-stack"
                        class="w-12 h-12 mx-auto mb-3 text-gray-300"
                    />
                    <p>Belum ada cabang dengan Google Sheet terhubung.</p>
                </div>
            @endif
        </x-filament::section>
    </div>
</x-filament::page>
