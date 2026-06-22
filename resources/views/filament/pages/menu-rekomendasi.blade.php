<x-filament-panels::page>
    <div class="space-y-4">

        @if(empty($rules))
            <div class="text-center text-gray-400 py-10">
                Belum ada data transaksi yang cukup untuk menghasilkan rekomendasi.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border border-gray-200">
                    <thead class="bg-gray-100 text-xs uppercase">
                        <tr>
                            <th class="px-4 py-2 border">Jika Pesan</th>
                            <th class="px-4 py-2 border">Maka Rekomendasikan</th>
                            <th class="px-4 py-2 border text-center">Support</th>
                            <th class="px-4 py-2 border text-center">Confidence</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rules as $rule)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 border text-orange-500 font-semibold">
                                    {{ implode(', ', $rule['antecedent']) }}
                                </td>
                                <td class="px-4 py-2 border text-green-600 font-semibold">
                                    {{ implode(', ', $rule['consequent']) }}
                                </td>
                                <td class="px-4 py-2 border text-center">
                                    {{ round($rule['support'] * 100, 1) }}%
                                </td>
                                <td class="px-4 py-2 border text-center">
                                    {{ round($rule['confidence'] * 100, 1) }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </div>
</x-filament-panels::page>