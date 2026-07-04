@extends('layouts.app')

@section('title', 'Tambah Venue')

@section('content')
<div class="p-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">
                Tambah Venue
            </h1>
            <p class="text-gray-500 dark:text-gray-400">
                Tambahkan venue baru untuk acara.
            </p>
        </div>

        <a href="{{ route('venues.index') }}"
            class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-xl">
            Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg p-8">

        <form action="{{ route('venues.store') }}" method="POST">
            @csrf

            <div class="mb-5">
                <label class="block mb-2 font-semibold">
                    Nama Venue
                </label>

                <input
                    type="text"
                    name="nama_venue"
                    class="w-full border rounded-xl px-4 py-3 dark:bg-gray-800 dark:border-gray-700"
                    required>
            </div>

            <div class="mb-5">
                <label class="block mb-2 font-semibold">
                    Alamat
                </label>

                <textarea
                    name="alamat"
                    rows="3"
                    class="w-full border rounded-xl px-4 py-3 dark:bg-gray-800 dark:border-gray-700"
                    required></textarea>
            </div>

            <div class="mb-5">
                <label class="block mb-2 font-semibold">
                    Kapasitas
                </label>

                <input
                    type="number"
                    name="kapasitas"
                    class="w-full border rounded-xl px-4 py-3 dark:bg-gray-800 dark:border-gray-700"
                    required>
            </div>

            <div class="mb-5">
                <label class="block mb-2 font-semibold">
                    Harga
                </label>

                <input
                    type="number"
                    name="harga"
                    class="w-full border rounded-xl px-4 py-3 dark:bg-gray-800 dark:border-gray-700"
                    required>
            </div>

            <div class="mb-5">
                <label class="block mb-2 font-semibold">
                    Fasilitas
                </label>

                <textarea
                    name="fasilitas"
                    rows="3"
                    class="w-full border rounded-xl px-4 py-3 dark:bg-gray-800 dark:border-gray-700"></textarea>
            </div>

            <div class="mb-6">
                <label class="block mb-2 font-semibold">
                    Keterangan
                </label>

                <textarea
                    name="keterangan"
                    rows="3"
                    class="w-full border rounded-xl px-4 py-3 dark:bg-gray-800 dark:border-gray-700"></textarea>
            </div>

            <button
                type="submit"
                class="bg-coral hover:opacity-90 text-white px-6 py-3 rounded-xl">
                Simpan Venue
            </button>

        </form>

    </div>

</div>
@endsection