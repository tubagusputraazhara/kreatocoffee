<x-filament-widgets::widget>
    <x-filament::section>
        <div class="overflow-x-auto">

            {{-- Filter --}}
            <form wire:submit.prevent="filterJurnal" class="flex gap-4 items-center">
                <div>
                    <label for="periode_awal">Periode Awal:</label>
                    <input type="month" wire:model="periode_awal" id="periode_awal" class="border rounded px-2 py-1">
                </div>
                <div>
                    <label for="periode_akhir">Periode Akhir:</label>
                    <input type="month" wire:model="periode_akhir" id="periode_akhir" class="border rounded px-2 py-1">
                </div>
                <div>
                    <label for="coa_id">Akun COA:</label>
                    <select wire:model="coa_id" id="coa_id" class="border rounded px-2 py-1">
                        <option value="">-- Pilih Akun --</option>
                        @foreach(\App\Models\Coa::where('status_akun', 'Aktif')->orderBy('kode_akun')->get() as $akun)
                            <option value="{{ $akun->id_coa }}">{{ $akun->kode_akun }} - {{ $akun->nama_akun }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-filament::button type="submit" color="success" size="sm">
                        Filter
                    </x-filament::button>
                </div>
            </form>

            <br><br>

            {{-- Header --}}
            <div class="text-center">
                <b>Kreatocoffee</b><br>
                <b>Buku Besar</b><br>
                <b>Periode
                    @if($periode_awal && $periode_akhir)
                        {{ \Carbon\Carbon::createFromFormat('Y-m', $periode_awal)->translatedFormat('F Y') }}
                        -
                        {{ \Carbon\Carbon::createFromFormat('Y-m', $periode_akhir)->translatedFormat('F Y') }}
                    @else
                        {{ now()->translatedFormat('F Y') }}
                    @endif
                </b>
            </div>

            <br>

            {{-- Tabel --}}
            <table class="w-full text-sm text-left border border-gray-200 font-sans">
                <thead class="bg-gray-100 text-xs uppercase">
                    <tr class="font-semibold bg-gray-200">
                        <td colspan="4" class="text-right px-4 py-2 border">Saldo Awal</td>
                        <td colspan="2" class="text-right px-4 py-2 border">
                            Rp {{ number_format($saldoAwal, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <th class="px-4 py-2 border">Nomor Jurnal</th>
                        <th class="px-4 py-2 border">Tanggal</th>
                        <th class="px-4 py-2 border">Akun</th>
                        <th class="px-4 py-2 border">Ref</th>
                        <th class="px-4 py-2 border text-right">Debit</th>
                        <th class="px-4 py-2 border text-right">Kredit</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    @forelse($jurnals as $jurnal)
                        @foreach($jurnal->detailJurnal as $detail)
                            <tr>
                                <td class="px-4 py-2 border">{{ $jurnal->nomor_jurnal }}</td>
                                <td class="px-4 py-2 border">
                                    {{ \Carbon\Carbon::parse($jurnal->tanggal_jurnal)->format('Y-m-d') }}
                                </td>

                                @if($detail->debit != 0)
                                    <td class="px-4 py-2 border text-orange-500">{{ $detail->coa->nama_akun ?? '-' }}</td>
                                    <td class="px-4 py-2 border">{{ $jurnal->ref }}</td>
                                    <td class="px-4 py-2 border text-right text-green-600">
                                        Rp {{ number_format($detail->debit, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-2 border text-right"></td>
                                @else
                                    <td class="px-4 py-2 border text-orange-500">&nbsp;&nbsp;&nbsp;{{ $detail->coa->nama_akun ?? '-' }}</td>
                                    <td class="px-4 py-2 border">{{ $jurnal->ref }}</td>
                                    <td class="px-4 py-2 border text-right"></td>
                                    <td class="px-4 py-2 border text-right text-red-600">
                                        Rp {{ number_format($detail->kredit, 0, ',', '.') }}
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="6" class="text-center px-4 py-4 text-gray-400">
                                Tidak ada transaksi untuk akun dan periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="font-semibold text-xs bg-gray-200">
                    @php
                        $totalDebit = $jurnals->flatMap->detailJurnal->sum('debit');
                        $totalKredit = $jurnals->flatMap->detailJurnal->sum('kredit');
                        $saldoAkhir = $saldoAwal + ($totalDebit - $totalKredit);
                    @endphp
                    <tr class="font-semibold bg-gray-100">
                        <td colspan="4" class="text-right px-4 py-2 border">Total</td>
                        <td class="text-right px-4 py-2 border text-green-600">
                            Rp {{ number_format($totalDebit, 0, ',', '.') }}
                        </td>
                        <td class="text-right px-4 py-2 border text-red-600">
                            Rp {{ number_format($totalKredit, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr class="font-semibold bg-gray-200">
                        <td colspan="4" class="text-right px-4 py-2 border">Saldo Akhir</td>
                        <td colspan="2" class="text-right px-4 py-2 border">
                            Rp {{ number_format($saldoAkhir, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>

        </div>
    </x-filament::section>
</x-filament-widgets::widget>