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

    @if ($tab === 'offline')
        <x-filament::section>
            <x-slot name="heading">
                Laporan Offline
            </x-slot>

            <table class="fi-ta-table w-full">
                <thead>
                    <tr class="fi-ta-header-row">
                        <th class="fi-ta-header-cell fi-align-start">ID Kampanye</th>
                        <th class="fi-ta-header-cell fi-align-start">Proyek</th>
                        <th class="fi-ta-header-cell fi-align-start">Sumber</th>
                        <th class="fi-ta-header-cell fi-align-end">Budget</th>
                        <th class="fi-ta-header-cell fi-align-end">Total Lead</th>
                        <th class="fi-ta-header-cell fi-align-end">Kunjungan</th>
                        <th class="fi-ta-header-cell fi-align-end">UTJ</th>
                        <th class="fi-ta-header-cell fi-align-end">CPL</th>
                        <th class="fi-ta-header-cell fi-align-end">CPA</th>
                    </tr>
                </thead>
                <tbody>
                    @php $offlineData = $this->getOfflineData(); @endphp
                    @forelse ($offlineData as $row)
                        <tr @class(['fi-ta-row', 'fi-striped' => $loop->even])>
                            <td class="fi-ta-cell fi-align-start"><div class="fi-ta-col">{{ $row->campaign_id }}</div></td>
                            <td class="fi-ta-cell fi-align-start"><div class="fi-ta-col">{{ $row->proyek }}</div></td>
                            <td class="fi-ta-cell fi-align-start"><div class="fi-ta-col">{{ $row->sumber_promosi }}</div></td>
                            <td class="fi-ta-cell fi-align-end"><div class="fi-ta-col">Rp{{ number_format($row->budget, 0, ',', '.') }}</div></td>
                            <td class="fi-ta-cell fi-align-end"><div class="fi-ta-col">{{ $row->total_lead }}</div></td>
                            <td class="fi-ta-cell fi-align-end"><div class="fi-ta-col">{{ $row->total_kunjungan }}</div></td>
                            <td class="fi-ta-cell fi-align-end"><div class="fi-ta-col">{{ $row->total_utj }}</div></td>
                            <td class="fi-ta-cell fi-align-end"><div class="fi-ta-col">Rp{{ number_format($row->cpl, 0, ',', '.') }}</div></td>
                            <td class="fi-ta-cell fi-align-end"><div class="fi-ta-col">Rp{{ number_format($row->cpa, 0, ',', '.') }}</div></td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>

            @if ($offlineData->isEmpty())
                <x-filament::empty-state
                    heading="Belum ada data kampanye offline"
                    icon="heroicon-o-clipboard-document"
                />
            @endif
        </x-filament::section>
    @endif

    @if ($tab === 'online')
        <x-filament::section>
            <x-slot name="heading">
                Laporan Online
            </x-slot>

            <table class="fi-ta-table w-full">
                <thead>
                    <tr class="fi-ta-header-row">
                        <th class="fi-ta-header-cell fi-align-start">ID Kampanye</th>
                        <th class="fi-ta-header-cell fi-align-start">Proyek</th>
                        <th class="fi-ta-header-cell fi-align-start">Sumber</th>
                        <th class="fi-ta-header-cell fi-align-end">Budget</th>
                        <th class="fi-ta-header-cell fi-align-end">Klik</th>
                        <th class="fi-ta-header-cell fi-align-end">Lead</th>
                        <th class="fi-ta-header-cell fi-align-end">Respon</th>
                        <th class="fi-ta-header-cell fi-align-end">Diskusi</th>
                        <th class="fi-ta-header-cell fi-align-end">Cek Lokasi</th>
                        <th class="fi-ta-header-cell fi-align-end">UTJ</th>
                        <th class="fi-ta-header-cell fi-align-end">CPA</th>
                    </tr>
                </thead>
                <tbody>
                    @php $onlineData = $this->getOnlineData(); @endphp
                    @forelse ($onlineData as $row)
                        <tr @class(['fi-ta-row', 'fi-striped' => $loop->even])>
                            <td class="fi-ta-cell fi-align-start"><div class="fi-ta-col">{{ $row->campaign_id }}</div></td>
                            <td class="fi-ta-cell fi-align-start"><div class="fi-ta-col">{{ $row->proyek }}</div></td>
                            <td class="fi-ta-cell fi-align-start"><div class="fi-ta-col">{{ $row->sumber_promosi }}</div></td>
                            <td class="fi-ta-cell fi-align-end"><div class="fi-ta-col">Rp{{ number_format($row->budget, 0, ',', '.') }}</div></td>
                            <td class="fi-ta-cell fi-align-end"><div class="fi-ta-col">{{ $row->total_klik }}</div></td>
                            <td class="fi-ta-cell fi-align-end"><div class="fi-ta-col">{{ $row->total_lead_masuk }}</div></td>
                            <td class="fi-ta-cell fi-align-end"><div class="fi-ta-col">{{ $row->total_respon }}</div></td>
                            <td class="fi-ta-cell fi-align-end"><div class="fi-ta-col">{{ $row->total_diskusi }}</div></td>
                            <td class="fi-ta-cell fi-align-end"><div class="fi-ta-col">{{ $row->total_cek_lokasi }}</div></td>
                            <td class="fi-ta-cell fi-align-end"><div class="fi-ta-col">{{ $row->total_utj }}</div></td>
                            <td class="fi-ta-cell fi-align-end"><div class="fi-ta-col">Rp{{ number_format($row->cpa, 0, ',', '.') }}</div></td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>

            @if ($onlineData->isEmpty())
                <x-filament::empty-state
                    heading="Belum ada data kampanye online"
                    icon="heroicon-o-chart-bar"
                />
            @endif
        </x-filament::section>
    @endif
</div>
