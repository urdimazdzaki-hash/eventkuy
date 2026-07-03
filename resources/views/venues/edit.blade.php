@extends('layouts.app')

@section('title', 'Edit Venue')

@section('content')
<div class="p-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">
                Edit Venue
            </h1>
            <p class="text-gray-500 dark:text-gray-400">
                Perbarui data venue.
            </p>
        </div>

        <a href="{{ route('venues.index') }}"
            class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-xl">
            Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg p-8">

        <form action="{{ route('venues.update', $venue->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-5">
                <label class="block mb-2 font-semibold">
                    Nama Venue
                </label>

                <input
                    type="text"
                    name="nama_venue"
                    value="{{ old('nama_venue', $venue->nama_venue) }}"
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
                    required>{{ old('alamat', $venue->alamat) }}</textarea>
            </div>

            <div class="mb-5">
                <label class="block mb-2 font-semibold">
                    Kapasitas
                </label>

                <input
                    type="number"
                    name="kapasitas"
                    value="{{ old('kapasitas', $venue->kapasitas) }}"
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
                    value="{{ old('harga', $venue->harga) }}"
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
                    class="w-full border rounded-xl px-4 py-3 dark:bg-gray-800 dark:border-gray-700">{{ old('fasilitas', $venue->fasilitas) }}</textarea>
            </div>

            <div class="mb-6">
                <label class="block mb-2 font-semibold">
                    Keterangan
                </label>

                <textarea
                    name="keterangan"
                    rows="3"
                    class="w-full border rounded-xl px-4 py-3 dark:bg-gray-800 dark:border-gray-700">{{ old('keterangan', $venue->keterangan) }}</textarea>
            </div>

            <button
                type="submit"
                class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-xl">
                Update Venue
            </button>

        </form>

    </div>

</div>
@endsection