<div class="space-y-4">
    <x-filament::tabs label="Laporan">
        <x-filament::tabs.item
            wire:click="$set('tab', 'offline')"
            :alpine-active="'tab === \'offline\''"
            icon="heroicon-o-clipboard-document"
        >
            Laporan Offline
        </x-filament::tabs.item>

        <x-filament::tabs.item
            wire:click="$set('tab', 'online')"
            :alpine-active="'tab === \'online\''"
            icon="heroicon-o-chart-bar"
        >
            Laporan Online
        </x-filament::tabs.item>
    </x-filament::tabs>

    <x-filament::section>
        <x-slot name="heading">
            Laporan {{ $tab === 'offline' ? 'Offline' : 'Online' }}
        </x-slot>
        <div class="overflow-x-auto">
            {{ $this->table }}
        </div>
    </x-filament::section>
</div>
