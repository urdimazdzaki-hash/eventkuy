@extends('layouts.app')
@section('title', 'Buat Acara - EventKuy')

@section('content')
<div class="max-w-4xl mx-auto px-8 py-8">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Buat Acara Baru</h1>
        <p class="text-gray-500 dark:text-gray-400">Isi detail acara, rundown, dan estimasi anggaran.</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-sm rounded-lg px-4 py-3 mb-6">
            <p class="font-medium mb-1">Periksa kembali isian kamu:</p>
            @foreach ($errors->all() as $error)
                <p>&middot; {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('events.store') }}" id="eventForm">
        @csrf
        <input type="hidden" id="namaPaketInput" name="nama_paket" value="{{ old('nama_paket') }}">
        <input type="hidden" id="fasilitasPaketInput" name="fasilitas_paket" value="{{ old('fasilitas_paket') }}">

        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 mb-6">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100 mb-4">Detail Acara</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Nama Acara</label>
                    <input type="text" name="nama_event" value="{{ old('nama_event') }}" placeholder="Contoh: Pernikahan Syarif & Ranita"
                           class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-coral">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Jenis Acara</label>
                    <select name="jenis_event"
                            class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-coral">
                        <option value="wedding" {{ old('jenis_event') == 'wedding' ? 'selected' : '' }}>Wedding</option>
                        <option value="konser" {{ old('jenis_event') == 'konser' ? 'selected' : '' }}>Konser</option>
                        <option value="lainnya" {{ old('jenis_event') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Lokasi Acara</label>
                    <select name="tipe_lokasi"
                            class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-coral">
                        <option value="outdoor" {{ old('tipe_lokasi', 'outdoor') == 'outdoor' ? 'selected' : '' }}>Outdoor</option>
                        <option value="indoor" {{ old('tipe_lokasi') == 'indoor' ? 'selected' : '' }}>Indoor</option>
                    </select>
                    <p class="text-xs text-gray-400 mt-1">Outdoor akan dipantau cuacanya otomatis (Mhs 3).</p>
                </div>

                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Tanggal Acara</label>
                    <input type="date" name="tanggal_event" value="{{ old('tanggal_event') }}"
                           class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-coral">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Nama Lokasi/Venue</label>
                    <input type="text" name="lokasi_venue" value="{{ old('lokasi_venue') }}" placeholder="Contoh: Gedung Siliwangi"
                           class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-coral">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Kota Venue</label>
                    <input type="text" name="kota_venue" value="{{ old('kota_venue') }}" placeholder="Contoh: Bandung"
                           class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-coral">
                    <p class="text-xs text-gray-400 mt-1">Dipakai modul cek cuaca (Mhs 3) untuk memantau H-3 acara.</p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Catatan (opsional)</label>
                    <textarea name="catatan" rows="2" placeholder="Catatan tambahan tentang acara ini"
                              class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-coral">{{ old('catatan') }}</textarea>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 mb-6">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100 mb-4">Paket Wedding</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Pilih Paket</label>
                    <select id="paketTamu" onchange="pilihPaket()"
                            class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-coral">
                        <option value="custom">Custom (isi manual)</option>
                        <option value="silver">🥈 Silver — 300 pax (Rp 250 Juta)</option>
                        <option value="gold">🥇 Gold — 500 pax (Rp 450 Juta)</option>
                        <option value="platinum">💎 Platinum — 1.000 pax (Rp 850 Juta)</option>
                        <option value="diamond">👑 Diamond — 2.000 pax (Rp 1,5 Miliar)</option>
                    </select>

                    <div id="fasilitasCard" class="hidden mt-3 bg-amber-50 dark:bg-gray-800 border border-amber-200 dark:border-gray-700 rounded-xl p-4">
                        <p id="fasilitasNamaPaket" class="text-sm font-semibold text-amber-700 dark:text-amber-400 mb-1"></p>
                        <p id="fasilitasHarga" class="text-xs text-gray-500 dark:text-gray-400 mb-3"></p>
                        <ul id="fasilitasList" class="grid grid-cols-1 md:grid-cols-2 gap-1.5 text-sm text-gray-700 dark:text-gray-300"></ul>
                    </div>
                </div>

                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Jumlah Tamu</label>
                    <input type="number" id="jumlahTamu" name="jumlah_tamu" value="{{ old('jumlah_tamu') }}" min="0" placeholder="Jumlah tamu (pax)" oninput="hitungSubtotalCatering()"
                           class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-coral">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Harga per Orang (Rp)</label>
                    <input type="number" id="hargaPerOrang" name="harga_per_orang" value="{{ old('harga_per_orang') }}" min="0" placeholder="Otomatis dari paket" oninput="hitungSubtotalCatering()"
                           class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-coral">
                    <p class="text-xs text-gray-400 mt-1">Otomatis terhitung dari total paket &divide; jumlah tamu.</p>
                </div>

                <div class="md:col-span-2 bg-gray-50 dark:bg-gray-800 rounded-xl px-4 py-3 flex justify-between items-center text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Subtotal Catering</span>
                    <span id="subtotalCatering" class="font-semibold text-gray-800 dark:text-gray-100">Rp 0</span>
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
            <p id="rundownEmpty" class="text-sm text-gray-400">Belum ada rundown. Klik "+ Tambah Baris" untuk mulai.</p>
        </div>

        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-gray-800 dark:text-gray-100">Item Anggaran Lain</h2>
                <button type="button" onclick="tambahAnggaran()"
                        class="text-sm text-coral hover:underline">+ Tambah Item</button>
            </div>
            <p class="text-xs text-gray-400 mb-3">Item di luar paket, misal: souvenir tambahan, transportasi, dll.</p>

            <div id="anggaranContainer" class="space-y-3"></div>
            <p id="anggaranEmpty" class="text-sm text-gray-400">Belum ada item anggaran. Klik "+ Tambah Item" untuk mulai.</p>

            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-800 space-y-1">
                <div class="flex justify-between text-sm text-gray-500 dark:text-gray-400">
                    <span>Subtotal Catering (Paket)</span>
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
            <a href="{{ route('events.index') }}"
               class="px-6 py-3 rounded-full border border-gray-300 dark:border-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                Batal
            </a>
            <button type="submit"
                    class="flex-1 bg-coral hover:bg-red-400 text-white font-semibold py-3 rounded-full transition">
                Simpan Acara
            </button>
        </div>
    </form>
</div>

<script>
    let rundownIndex = 0;
    let anggaranIndex = 0;

    const dataPaket = {
        silver: {
            nama: 'Silver Package',
            pax: 300,
            total: 250000000,
            fasilitas: [
                'Venue Ballroom 6 Jam', 'Catering Premium 300 Pax', 'Dekorasi Standard Elegan',
                'MC Profesional', 'Sound System Standard', 'Dokumentasi Foto', 'Bridal Room',
                'Wedding Planner', 'Welcome Gate', 'Buku Tamu Digital'
            ]
        },
        gold: {
            nama: 'Gold Package',
            pax: 500,
            total: 450000000,
            fasilitas: [
                'Grand Ballroom 8 Jam', 'Catering Premium 500 Pax', 'Dekorasi Luxury Fresh Flower',
                'MC Profesional', 'Live Music Acoustic', 'Sound System Premium', 'Foto & Video Dokumentasi',
                'Bridal Room + Family Room', 'Wedding Organizer Full Service', 'Digital Invitation',
                'Welcome Drink', 'Wedding Cake 3 Tier'
            ]
        },
        platinum: {
            nama: 'Platinum Package',
            pax: 1000,
            total: 850000000,
            fasilitas: [
                'Exclusive Ballroom 10 Jam', 'International Buffet 1.000 Pax', 'Dekorasi Luxury Exclusive',
                'Fresh Flower Premium', 'LED Screen Stage', 'Live Band Performance', 'MC Profesional',
                'Foto & Video Cinematic', 'Photobooth Unlimited', 'Content Creator Team',
                '1 Executive Suite Room', 'Valet Parking', 'Wedding Cake 5 Tier', 'VIP Family Lounge'
            ]
        },
        diamond: {
            nama: 'Diamond Package',
            pax: 2000,
            total: 1500000000,
            fasilitas: [
                'Grand Ballroom Exclusive Full Day', 'International Buffet 2.000 Pax', 'Luxury Decoration Custom Concept',
                'Fresh Flower Import', 'Videotron & Premium Lighting', 'Live Orchestra / Band', 'MC Profesional Premium',
                'Cinematic Wedding Movie', 'Professional Content Creator Team', 'Photobooth Unlimited',
                '2 Executive Suite Room', 'Honeymoon Package', 'VIP Holding Room', 'Valet Parking Unlimited',
                'Wedding Cake Premium 7 Tier', 'Security & Guest Management Team'
            ]
        },
    };

    function tambahRundown() {
        document.getElementById('rundownEmpty').style.display = 'none';
        const container = document.getElementById('rundownContainer');
        const div = document.createElement('div');
        div.className = 'grid grid-cols-12 gap-2 items-start';
        div.innerHTML = `
            <input type="time" name="rundown[${rundownIndex}][waktu]" class="col-span-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-coral">
            <input type="text" name="rundown[${rundownIndex}][kegiatan]" placeholder="Kegiatan" class="col-span-5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-coral">
            <input type="text" name="rundown[${rundownIndex}][pic]" placeholder="PIC" class="col-span-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-coral">
            <button type="button" onclick="this.parentElement.remove()" class="col-span-1 text-gray-400 hover:text-red-500 py-2">✕</button>
        `;
        container.appendChild(div);
        rundownIndex++;
    }

    function tambahAnggaran() {
        document.getElementById('anggaranEmpty').style.display = 'none';
        const container = document.getElementById('anggaranContainer');
        const div = document.createElement('div');
        div.className = 'grid grid-cols-12 gap-2 items-start';
        div.innerHTML = `
            <input type="text" name="anggaran[${anggaranIndex}][nama_item]" placeholder="Nama item" class="col-span-7 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-coral">
            <input type="number" name="anggaran[${anggaranIndex}][estimasi_biaya]" placeholder="Biaya (Rp)" min="0" oninput="hitungTotalAnggaran()" class="col-span-4 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-coral">
            <button type="button" onclick="this.parentElement.remove(); hitungTotalAnggaran()" class="col-span-1 text-gray-400 hover:text-red-500 py-2">✕</button>
        `;
        container.appendChild(div);
        anggaranIndex++;
    }

    function pilihPaket() {
        const select = document.getElementById('paketTamu');
        const inputTamu = document.getElementById('jumlahTamu');
        const inputHarga = document.getElementById('hargaPerOrang');
        const fasilitasCard = document.getElementById('fasilitasCard');

        if (select.value === 'custom') {
            inputTamu.readOnly = false;
            inputHarga.readOnly = false;
            inputTamu.value = '';
            inputHarga.value = '';
            fasilitasCard.classList.add('hidden');
            document.getElementById('namaPaketInput').value = '';
            document.getElementById('fasilitasPaketInput').value = '';
        } else {
            const paket = dataPaket[select.value];
            const hargaPerOrang = Math.round(paket.total / paket.pax);

            inputTamu.value = paket.pax;
            inputHarga.value = hargaPerOrang;
            inputTamu.readOnly = true;
            inputHarga.readOnly = true;

            document.getElementById('fasilitasNamaPaket').textContent = '✨ ' + paket.nama;
            document.getElementById('fasilitasHarga').textContent =
                paket.pax.toLocaleString('id-ID') + ' pax — Total Rp ' + paket.total.toLocaleString('id-ID');

            const list = document.getElementById('fasilitasList');
            list.innerHTML = '';
            paket.fasilitas.forEach(item => {
                const li = document.createElement('li');
                li.className = 'flex items-start gap-1.5';
                li.innerHTML = `<span class="text-green-500">✓</span><span>${item}</span>`;
                list.appendChild(li);
            });

            fasilitasCard.classList.remove('hidden');
            document.getElementById('namaPaketInput').value = select.value;
            document.getElementById('fasilitasPaketInput').value = paket.fasilitas.join('\n');
        }
        hitungSubtotalCatering();
    }

    function hitungSubtotalCatering() {
        const tamu = parseInt(document.getElementById('jumlahTamu').value) || 0;
        const harga = parseInt(document.getElementById('hargaPerOrang').value) || 0;
        const subtotal = tamu * harga;
        document.getElementById('subtotalCatering').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
        document.getElementById('totalCateringDisplay').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
        hitungTotalAnggaran();
    }

    function hitungTotalAnggaran() {
        const tamu = parseInt(document.getElementById('jumlahTamu').value) || 0;
        const harga = parseInt(document.getElementById('hargaPerOrang').value) || 0;
        const subtotalCatering = tamu * harga;

        const inputs = document.querySelectorAll('#anggaranContainer input[type="number"]');
        let totalItemLain = 0;
        inputs.forEach(input => totalItemLain += parseInt(input.value) || 0);

        document.getElementById('totalItemLain').textContent = 'Rp ' + totalItemLain.toLocaleString('id-ID');
        document.getElementById('totalAnggaran').textContent = 'Rp ' + (subtotalCatering + totalItemLain).toLocaleString('id-ID');
    }

    const templateRundown = [
        { waktu: '07:00', kegiatan: 'Persiapan Venue & Dekorasi (s.d 09.00)', pic: 'EO & Vendor' },
        { waktu: '08:00', kegiatan: 'Persiapan Pengantin (s.d 10.00)', pic: 'MUA' },
        { waktu: '09:00', kegiatan: 'Gladi Bersih Acara (s.d 10.00)', pic: 'EO' },
        { waktu: '10:00', kegiatan: 'Briefing Seluruh Vendor (s.d 10.30)', pic: 'Event Director' },
        { waktu: '10:30', kegiatan: 'Pembukaan oleh MC (s.d 10.40)', pic: 'MC' },
        { waktu: '10:40', kegiatan: "Pembacaan Ayat Suci Al-Qur'an (s.d 10.45)", pic: 'Qori' },
        { waktu: '10:45', kegiatan: 'Sambutan Keluarga (s.d 10.50)', pic: 'Perwakilan Keluarga' },
        { waktu: '10:50', kegiatan: 'Prosesi Akad Nikah (s.d 11.15)', pic: 'Penghulu' },
        { waktu: '11:15', kegiatan: 'Penandatanganan Dokumen (s.d 11.20)', pic: 'Pengantin' },
        { waktu: '11:20', kegiatan: 'Doa Pernikahan (s.d 11.30)', pic: 'Ustadz' },
        { waktu: '11:30', kegiatan: 'Sungkeman kepada Orang Tua (s.d 11.45)', pic: 'Pengantin' },
        { waktu: '11:45', kegiatan: 'Grand Entrance Pengantin (s.d 12.00)', pic: 'EO' },
        { waktu: '12:00', kegiatan: 'Ucapan Syukur Pengantin (s.d 12.10)', pic: 'Pengantin' },
        { waktu: '12:10', kegiatan: 'Tausiyah Singkat Pernikahan (s.d 12.20)', pic: 'Ustadz' },
        { waktu: '12:20', kegiatan: 'Doa Bersama (s.d 12.30)', pic: 'Ustadz' },
        { waktu: '12:30', kegiatan: 'Ramah Tamah & Jamuan Makan (s.d 14.30)', pic: 'Catering' },
        { waktu: '12:30', kegiatan: 'Live Gambus / Instrumental Islami (s.d 14.30)', pic: 'Entertainment' },
        { waktu: '14:30', kegiatan: 'Foto Bersama Tamu Undangan (s.d 16.30)', pic: 'Dokumentasi' },
        { waktu: '14:30', kegiatan: 'Silaturahmi Keluarga & Kerabat (s.d 16.30)', pic: 'EO' },
        { waktu: '14:30', kegiatan: 'Hiburan Akustik Islami (s.d 16.30)', pic: 'Entertainment' },
        { waktu: '16:30', kegiatan: 'Ucapan Terima Kasih (s.d 16.40)', pic: 'MC' },
        { waktu: '16:40', kegiatan: 'Doa Penutup (s.d 16.50)', pic: 'Ustadz' },
        { waktu: '16:50', kegiatan: 'Pelepasan Tamu Kehormatan (s.d 17.00)', pic: 'EO' },
        { waktu: '17:00', kegiatan: 'Acara Selesai', pic: 'Semua Tim' },
    ];

    templateRundown.forEach(item => tambahRundown(item));
    tambahAnggaran();
</script>
@endsection