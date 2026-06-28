@extends('layouts.app')
@section('title', 'Konfirmasi Pembayaran - EventKuy')

@section('content')
<div class="max-w-2xl mx-auto px-8 py-8">

    <div class="text-center mb-8">
        <div class="w-16 h-16 bg-coral/10 rounded-full flex items-center justify-center mx-auto mb-4">
            <i data-lucide="credit-card" class="w-8 h-8 text-coral"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Konfirmasi Pembayaran</h1>
        <p class="text-gray-500 dark:text-gray-400">Selesaikan pembayaran untuk mengaktifkan acaramu.</p>
    </div>

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 mb-6">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100 mb-4">Ringkasan Acara</h2>
        <div class="space-y-2 text-sm mb-4">
            <div class="flex justify-between">
                <span class="text-gray-500">Nama Acara</span>
                <span class="font-medium text-gray-800 dark:text-gray-100">{{ $event->nama_event }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Tanggal</span>
                <span class="font-medium text-gray-800 dark:text-gray-100">{{ $event->tanggal_event->translatedFormat('d F Y') }}</span>
            </div>
            @if ($event->nama_paket)
                <div class="flex justify-between">
                    <span class="text-gray-500">Paket</span>
                    <span class="font-medium text-gray-800 dark:text-gray-100">{{ ucfirst($event->nama_paket) }}</span>
                </div>
            @endif
        </div>

        <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4 space-y-2">
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Total Anggaran</span>
                <span class="font-semibold text-gray-800 dark:text-gray-100">Rp {{ number_format($event->total_anggaran, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Sudah Dibayar</span>
                <span class="font-semibold text-green-600">Rp {{ number_format($event->jumlah_dibayar ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm pt-2 border-t border-gray-200 dark:border-gray-700">
                <span class="text-gray-700 dark:text-gray-300 font-medium">Sisa Pembayaran</span>
                <span class="font-bold text-coral">Rp {{ number_format($event->sisa_pembayaran, 0, ',', '.') }}</span>
            </div>

            <div class="pt-2">
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    <div class="bg-coral h-2 rounded-full transition-all" style="width: {{ $event->persen_terbayar }}%"></div>
                </div>
                <p class="text-xs text-gray-400 mt-1 text-right">{{ $event->persen_terbayar }}% terbayar</p>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-sm rounded-lg px-4 py-3 mb-6">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('payment.confirm', $event) }}">
        @csrf

        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 mb-6">
            <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Jumlah Pembayaran (Rp)</label>
            <input type="number" name="jumlah_bayar" min="1" max="{{ $event->sisa_pembayaran }}" placeholder="Contoh: 50000000" required
                   class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-coral">
            <p class="text-xs text-gray-400 mt-2">
                <i data-lucide="info" class="w-3 h-3 inline"></i>
                Halaman simulasi pembayaran untuk keperluan demo. Bisa bayar DP sebagian atau lunas sekaligus.
            </p>
        </div>

        <button type="submit"
                class="w-full bg-coral hover:bg-red-400 text-white font-semibold py-3 rounded-full transition">
            Konfirmasi Pembayaran
        </button>
    </form>

    <p class="text-center text-sm text-gray-400 mt-4">
        <a href="{{ route('events.index') }}" class="hover:text-coral">Lewati untuk sekarang, bayar nanti &rarr;</a>
    </p>

</div>
@endsection