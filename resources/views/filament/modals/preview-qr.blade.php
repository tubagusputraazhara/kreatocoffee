<div class="flex flex-col items-center gap-6 p-6">

    {{-- Nama Meja --}}
    <h2 class="text-xl font-bold text-gray-700 dark:text-gray-200">
        {{ $meja->nama_meja }}
    </h2>

    {{-- Gambar QR Code --}}
    <div class="border-4 border-gray-200 rounded-xl p-4 bg-white">
        <img
            src="{{ asset('storage/' . $meja->qr_code_path) }}"
            alt="QR Code {{ $meja->nama_meja }}"
            class="w-64 h-64 object-contain"
        />
    </div>

    {{-- ID Meja --}}
    <p class="text-sm text-gray-400">ID: {{ $meja->id_meja }}</p>

    {{-- Tombol Download di dalam Modal --}}
    
        href="{{ asset('storage/' . $meja->qr_code_path) }}"
        download="QRCode-{{ $meja->nama_meja }}.png"
        class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 
               text-white font-semibold px-6 py-2 rounded-lg transition"
    >
        ⬇ Download PNG
    </a>

</div>