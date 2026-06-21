<x-filament-widgets::widget>
    <x-filament::section>
        <div class="overflow-x-auto">

            {{-- Filter --}}
            <form wire:submit.prevent="filterJurnal" class="flex gap-3 items-center mb-4">
                <label class="font-semibold text-sm">Pilih Periode:</label>
                <input 
                    type="month" 
                    wire:model="periode" 
                    class="border rounded px-2 py-1 text-sm"
                >
                <x-filament::button type="submit" color="success" size="sm">
                    Filter
                </x-filament::button>
            </form>

            {{-- Header --}}
            <div class="text-center mb-4">
                <b>Kreatocoffee</b><br>
                <b>Jurnal Umum</b><br>
                <b>Periode 
                    {{ $periode 
                        ? \Carbon\Carbon::createFromFormat('Y-m', $periode)->translatedFormat('F Y') 
                        : now()->translatedFormat('F Y') 
                    }}
                </b>
            </div>

            {{-- Tabel --}}
            <table class="w-full text-sm text-left border border-gray-200">
                <thead class="bg-gray-100 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-2 border">Nomor Jurnal</th>
                        <th class="px-4 py-2 border">Tanggal</th>
                        <th class="px-4 py-2 border">Akun</th>
                        <th class="px-4 py-2 border">Ref</th>
                        <th class="px-4 py-2 border text-right">Debit</th>
                        <th class="px-4 py-2 border text-right">Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jurnals as $jurnal)
                        @foreach($jurnal->detailJurnal as $detail)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 border">{{ $jurnal->nomor_jurnal }}</td>
                                <td class="px-4 py-2 border">
                                    {{ \Carbon\Carbon::parse($jurnal->tanggal_jurnal)->format('Y-m-d') }}
                                </td>

                                @if($detail->debit != 0)
                                    <td class="px-4 py-2 border text-orange-500">
                                        {{ $detail->coa->nama_akun ?? '-' }}
                                    </td>
                                @else
                                    <td class="px-4 py-2 border text-orange-500">
                                        &nbsp;&nbsp;&nbsp;{{ $detail->coa->nama_akun ?? '-' }}
                                    </td>
                                @endif

                                <td class="px-4 py-2 border">
                                    <span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded">
                                        {{ $jurnal->ref ?? '-' }}
                                    </span>
                                </td>

                                <td class="px-4 py-2 border text-right text-green-600">
                                    {{ $detail->debit != 0 ? 'Rp ' . number_format($detail->debit, 0, ',', '.') : '' }}
                                </td>
                                <td class="px-4 py-2 border text-right text-red-600">
                                    {{ $detail->kredit != 0 ? 'Rp ' . number_format($detail->kredit, 0, ',', '.') : '' }}
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="6" class="text-center px-4 py-4 text-gray-400">
                                Tidak ada data jurnal untuk periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="font-semibold bg-gray-100">
                        <td colspan="4" class="text-right px-4 py-2 border">Total</td>
                        <td class="text-right px-4 py-2 border text-green-600">
                            Rp {{ number_format($jurnals->flatMap->detailJurnal->sum('debit'), 0, ',', '.') }}
                        </td>
                        <td class="text-right px-4 py-2 border text-red-600">
                            Rp {{ number_format($jurnals->flatMap->detailJurnal->sum('kredit'), 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>

        </div>
    </x-filament::section>
</x-filament-widgets::widget>