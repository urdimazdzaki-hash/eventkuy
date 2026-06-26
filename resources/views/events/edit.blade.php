@extends('layouts.app')
@section('title', 'Edit Acara - EventKuy')

@section('content')
<div class="max-w-4xl mx-auto px-8 py-8">

    <div class="mb-6">
        <a href="{{ route('events.show', $event) }}" class="text-sm text-gray-400 hover:text-coral mb-2 inline-block">&larr; Kembali ke Detail Acara</a>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Edit Acara</h1>
        <p class="text-gray-500 dark:text-gray-400">Perbarui detail acara, rundown, dan estimasi anggaran.</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-sm rounded-lg px-4 py-3 mb-6">
            <p class="font-medium mb-1">Periksa kembali isian kamu:</p>
            @foreach ($errors->all() as $error)
                <p>&middot; {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('events.update', $event) }}" id="eventForm">
        @csrf
        @method('PUT')

        {{-- Hidden input untuk nama_paket & fasilitas_paket --}}
        <input type="hidden" id="inputNamaPaket" name="nama_paket" value="{{ old('nama_paket', $event->nama_paket) }}">
        <input type="hidden" id="inputFasilitasPaket" name="fasilitas_paket" value="{{ old('fasilitas_paket', $event->fasilitas_paket) }}">

        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 mb-6">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100 mb-4">Detail Acara</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Nama Acara</label>
                    <input type="text" name="nama_event" value="{{ old('nama_event', $event->nama_event) }}"
                           class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-coral">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Jenis Acara</label>
                    <select name="jenis_event"
                            class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-coral">
                        <option value="wedding" {{ old('jenis_event', $event->jenis_event) == 'wedding' ? 'selected' : '' }}>Wedding</option>
                        <option value="konser" {{ old('jenis_event', $event->jenis_event) == 'konser' ? 'selected' : '' }}>Konser</option>
                        <option value="lainnya" {{ old('jenis_event', $event->jenis_event) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Lokasi Acara</label>
                    <select name="tipe_lokasi"
                            class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-coral">
                        <option value="outdoor" {{ old('tipe_lokasi', $event->tipe_lokasi) == 'outdoor' ? 'selected' : '' }}>Outdoor</option>
                        <option value="indoor" {{ old('tipe_lokasi', $event->tipe_lokasi) == 'indoor' ? 'selected' : '' }}>Indoor</option>
                    </select>
                    <p class="text-xs text-gray-400 mt-1">Outdoor akan dipantau cuacanya otomatis (Mhs 3).</p>
                </div>

                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Tanggal Acara</label>
                    <input type="date" name="tanggal_event" value="{{ old('tanggal_event', $event->tanggal_event->format('Y-m-d')) }}"
                           class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-coral">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Nama Lokasi/Venue</label>
                    <input type="text" name="lokasi_venue" value="{{ old('lokasi_venue', $event->lokasi_venue) }}"
                           class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-coral">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Kota Venue</label>
                    <input type="text" name="kota_venue" value="{{ old('kota_venue', $event->kota_venue) }}"
                           class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-coral">
                    <p class="text-xs text-gray-400 mt-1">Dipakai modul cek cuaca (Mhs 3) untuk memantau H-3 acara.</p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Pilih Paket Tamu</label>
                    <select id="paketTamu" onchange="pilihPaket()"
                            class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-coral">
                        <option value="custom">Custom (isi manual)</option>
                        <option value="silver" {{ old('nama_paket', $event->nama_paket) == 'silver' ? 'selected' : '' }}>Silver — 300 pax (Rp 250 Juta)</option>
                        <option value="gold"   {{ old('nama_paket', $event->nama_paket) == 'gold'   ? 'selected' : '' }}>Gold — 500 pax (Rp 450 Juta)</option>
                        <option value="platinum" {{ old('nama_paket', $event->nama_paket) == 'platinum' ? 'selected' : '' }}>Platinum — 1.000 pax (Rp 850 Juta)</option>
                        <option value="diamond" {{ old('nama_paket', $event->nama_paket) == 'diamond' ? 'selected' : '' }}>Diamond — 2.000 pax (Rp 1,5 Miliar)</option>
                    </select>

                    {{-- Card fasilitas muncul di sini --}}
                    <div id="cardFasilitas" class="mt-3 hidden bg-amber-50 dark:bg-gray-800 border border-amber-200 dark:border-gray-700 rounded-xl p-4">
                        <p id="judulPaket" class="text-sm font-semibold text-amber-700 dark:text-amber-400 mb-2"></p>
                        <ul id="listFasilitas" class="space-y-1"></ul>
                    </div>
                </div>

                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Jumlah Tamu</label>
                    <input type="number" id="jumlahTamu" name="jumlah_tamu" value="{{ old('jumlah_tamu', $event->jumlah_tamu) }}" min="0" oninput="hitungSubtotalCatering()"
                           class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-coral">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Harga per Orang (Rp)</label>
                    <input type="number" id="hargaPerOrang" name="harga_per_orang" value="{{ old('harga_per_orang', $event->harga_per_orang) }}" min="0" oninput="hitungSubtotalCatering()"
                           class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-coral">
                    <p class="text-xs text-gray-400 mt-1">Otomatis terhitung dari total paket &divide; jumlah tamu.</p>
                </div>

                <div class="md:col-span-2 bg-gray-50 dark:bg-gray-800 rounded-xl px-4 py-3 flex justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Subtotal Catering (Tamu &times; Harga)</span>
                    <span id="subtotalCatering" class="font-semibold text-gray-800 dark:text-gray-100">Rp 0</span>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Catatan (opsional)</label>
                    <textarea name="catatan" rows="2"
                              class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-coral">{{ old('catatan', $event->catatan) }}</textarea>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-gray-800 dark:text-gray-100">Rundown Acara</h2>
                <button type="button" onclick="tambahRundown()"
                        class="text-sm text-coral hover:underline">+ Tambah Baris</button>
            </div>

            <div id="rundownContainer" class="space-y-3"></div>
            <p id="rundownEmpty" class="text-sm text-gray-400">Belum ada rundown.</p>
        </div>

        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-gray-800 dark:text-gray-100">Item Anggaran Lain</h2>
                <button type="button" onclick="tambahAnggaran()"
                        class="text-sm text-coral hover:underline">+ Tambah Item</button>
            </div>

            <div id="anggaranContainer" class="space-y-3"></div>
            <p id="anggaranEmpty" class="text-sm text-gray-400">Belum ada item anggaran.</p>

            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-800 space-y-1">
                <div class="flex justify-between text-sm text-gray-500 dark:text-gray-400">
                    <span>Subtotal Catering</span>
                    <span id="totalCateringDisplay">Rp 0</span>
                </div>
                <div class="flex justify-between text-sm text-gray-500 dark:text-gray-400">
                    <span>Subtotal Item Lain</span>
                    <span id="totalItemLain">Rp 0</span>
                </div>
                <div class="flex justify-between text-base font-semibold text-gray-800 dark:text-gray-100 pt-1">
                    <span>Total Estimasi Anggaran</span>
                    <span id="totalAnggaran">Rp 0</span>
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('events.show', $event) }}"
               class="px-6 py-3 rounded-full border border-gray-300 dark:border-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                Batal
            </a>
            <button type="submit"
                    class="flex-1 bg-coral hover:bg-red-400 text-white font-semibold py-3 rounded-full transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
    let rundownIndex = 0;
    let anggaranIndex = 0;

    const dataPaket = {
        silver: {
            nama: 'Paket Silver',
            pax: 300,
            total: 250000000,
            fasilitas: [
                'Ballroom 6 jam', 'Catering premium', 'Dekorasi standard', 'MC profesional',
                'Sound system standard', 'Foto dokumentasi', 'Bridal room', 'Wedding organizer',
                'Welcome gate', 'Buku tamu digital'
            ]
        },
        gold: {
            nama: 'Paket Gold',
            pax: 500,
            total: 450000000,
            fasilitas: [
                'Grand ballroom 8 jam', 'Dekorasi luxury fresh flower', 'Live music',
                'Sound system premium', 'Foto + video dokumentasi', 'Bridal room + family room',
                'Wedding organizer full service', 'Digital invitation', 'Welcome drink', 'Wedding cake 3 tier'
            ]
        },
        platinum: {
            nama: 'Paket Platinum',
            pax: 1000,
            total: 850000000,
            fasilitas: [
                'Exclusive ballroom 10 jam', 'International buffet', 'Dekorasi exclusive',
                'LED screen', 'Live band', 'Foto cinematic', 'Photobooth unlimited',
                'Content creator', 'Executive suite', 'Valet parking', 'Wedding cake 5 tier', 'VIP lounge'
            ]
        },
        diamond: {
            nama: 'Paket Diamond',
            pax: 2000,
            total: 1500000000,
            fasilitas: [
                'Grand ballroom full day', 'International buffet', 'Dekorasi custom',
                'Fresh flower import', 'Videotron', 'Live orchestra', 'Foto cinematic movie',
                'Content creator pro', 'Photobooth unlimited', '2 Executive suite',
                'Honeymoon package', 'VIP holding room', 'Valet unlimited',
                'Wedding cake 7 tier', 'Security team'
            ]
        }
    };

    const rundownLama = @json($event->rundowns->map(fn($r) => ['waktu' => \Carbon\Carbon::parse($r->waktu)->format('H:i'), 'kegiatan' => $r->kegiatan, 'pic' => $r->pic]));
    const anggaranLama = @json($event->budgetItems->map(fn($b) => ['nama_item' => $b->nama_item, 'estimasi_biaya' => $b->estimasi_biaya]));

    function tambahRundown(data = null) {
        const empty = document.getElementById('rundownEmpty');
        if (empty) empty.style.display = 'none';
        const container = document.getElementById('rundownContainer');
        const div = document.createElement('div');
        div.className = 'grid grid-cols-12 gap-2 items-start';
        const waktu    = data ? data.waktu    : '';
        const kegiatan = data ? data.kegiatan : '';
        const pic      = data && data.pic ? data.pic : '';
        div.innerHTML = `
            <input type="time" name="rundown[${rundownIndex}][waktu]" value="${waktu}" class="col-span-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-coral">
            <input type="text" name="rundown[${rundownIndex}][kegiatan]" value="${kegiatan}" placeholder="Kegiatan" class="col-span-5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-coral">
            <input type="text" name="rundown[${rundownIndex}][pic]" value="${pic}" placeholder="PIC" class="col-span-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-coral">
            <button type="button" onclick="this.parentElement.remove()" class="col-span-1 text-gray-400 hover:text-red-500 py-2">✕</button>
        `;
        container.appendChild(div);
        rundownIndex++;
    }

    function tambahAnggaran(data = null) {
        const empty = document.getElementById('anggaranEmpty');
        if (empty) empty.style.display = 'none';
        const container = document.getElementById('anggaranContainer');
        const div = document.createElement('div');
        div.className = 'grid grid-cols-12 gap-2 items-start';
        const namaItem = data ? data.nama_item : '';
        const biaya    = data ? data.estimasi_biaya : '';
        div.innerHTML = `
            <input type="text" name="anggaran[${anggaranIndex}][nama_item]" value="${namaItem}" placeholder="Nama item" class="col-span-7 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-coral">
            <input type="number" name="anggaran[${anggaranIndex}][estimasi_biaya]" value="${biaya}" placeholder="Biaya (Rp)" min="0" oninput="hitungTotalAnggaran()" class="col-span-4 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-coral">
            <button type="button" onclick="this.parentElement.remove(); hitungTotalAnggaran()" class="col-span-1 text-gray-400 hover:text-red-500 py-2">✕</button>
        `;
        container.appendChild(div);
        anggaranIndex++;
    }

    function pilihPaket() {
        const select     = document.getElementById('paketTamu');
        const inputTamu  = document.getElementById('jumlahTamu');
        const inputHarga = document.getElementById('hargaPerOrang');
        const card       = document.getElementById('cardFasilitas');
        const judul      = document.getElementById('judulPaket');
        const listEl     = document.getElementById('listFasilitas');

        if (select.value === 'custom') {
            inputTamu.readOnly  = false;
            inputHarga.readOnly = false;
            card.classList.add('hidden');
            document.getElementById('inputNamaPaket').value      = '';
            document.getElementById('inputFasilitasPaket').value = '';
        } else {
            const paket = dataPaket[select.value];
            inputTamu.value     = paket.pax;
            inputHarga.value    = Math.round(paket.total / paket.pax);
            inputTamu.readOnly  = true;
            inputHarga.readOnly = true;

            // Tampilkan card fasilitas
            judul.textContent = '✨ ' + paket.nama;
            listEl.innerHTML  = paket.fasilitas
                .map(f => `<li class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <span class="text-green-500">✓</span> ${f}
                </li>`)
                .join('');
            card.classList.remove('hidden');

            // Simpan ke hidden input
            document.getElementById('inputNamaPaket').value      = select.value;
            document.getElementById('inputFasilitasPaket').value = paket.fasilitas.join('\n');
        }
        hitungSubtotalCatering();
    }

    function hitungSubtotalCatering() {
        const tamu    = parseInt(document.getElementById('jumlahTamu').value) || 0;
        const harga   = parseInt(document.getElementById('hargaPerOrang').value) || 0;
        const subtotal = tamu * harga;
        document.getElementById('subtotalCatering').textContent      = 'Rp ' + subtotal.toLocaleString('id-ID');
        document.getElementById('totalCateringDisplay').textContent  = 'Rp ' + subtotal.toLocaleString('id-ID');
        hitungTotalAnggaran();
    }

    function hitungTotalAnggaran() {
        const tamu    = parseInt(document.getElementById('jumlahTamu').value) || 0;
        const harga   = parseInt(document.getElementById('hargaPerOrang').value) || 0;
        const subtotalCatering = tamu * harga;

        const inputs = document.querySelectorAll('#anggaranContainer input[type="number"]');
        let totalItemLain = 0;
        inputs.forEach(input => totalItemLain += parseInt(input.value) || 0);

        document.getElementById('totalItemLain').textContent  = 'Rp ' + totalItemLain.toLocaleString('id-ID');
        document.getElementById('totalAnggaran').textContent  = 'Rp ' + (subtotalCatering + totalItemLain).toLocaleString('id-ID');
    }

    
    if (rundownLama.length > 0) {
        rundownLama.forEach(item => tambahRundown(item));
        document.getElementById('rundownEmpty').style.display = 'none';
    }

    if (anggaranLama.length > 0) {
        anggaranLama.forEach(item => tambahAnggaran(item));
        document.getElementById('anggaranEmpty').style.display = 'none';
    }

   const paketSelect = document.getElementById('paketTamu');
const namaPaketLama = "{{ old('nama_paket', $event->nama_paket) }}";
if (namaPaketLama && dataPaket[namaPaketLama]) {
    paketSelect.value = namaPaketLama;
    pilihPaket();
} else {
    hitungSubtotalCatering();
}
@endsection