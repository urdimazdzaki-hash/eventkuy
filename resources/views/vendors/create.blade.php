@extends('layouts.app')

@section('title', 'Tambah Vendor')

@section('content')
<div class="p-8">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">
            Tambah Vendor
        </h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">
            Tambahkan data vendor baru.
        </p>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow p-8">

        <form action="{{ route('vendors.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="block mb-2 font-medium">Nama Vendor</label>
                    <input
                        type="text"
                        name="nama_vendor"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-800 px-4 py-3"
                        required>
                </div>

                <div>
                    <label class="block mb-2 font-medium">Kategori</label>
                    <input
                        type="text"
                        name="kategori"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-800 px-4 py-3"
                        required>
                </div>

                <div>
                    <label class="block mb-2 font-medium">Kontak</label>
                    <input
                        type="text"
                        name="kontak"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-800 px-4 py-3">
                </div>

                <div>
                    <label class="block mb-2 font-medium">Harga</label>
                    <input
                        type="number"
                        name="harga"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-800 px-4 py-3"
                        required>
                </div>

                <div class="md:col-span-2">
                    <label class="block mb-2 font-medium">Alamat</label>
                    <textarea
                        name="alamat"
                        rows="3"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-800 px-4 py-3"></textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block mb-2 font-medium">Keterangan</label>
                    <textarea
                        name="keterangan"
                        rows="3"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-800 px-4 py-3"></textarea>
                </div>

            </div>

            <div class="flex gap-3 mt-8">

                <button
                    type="submit"
                    class="bg-coral hover:bg-red-500 text-white px-6 py-3 rounded-xl">
                    Simpan
                </button>

                <a href="{{ route('vendors.index') }}"
                    class="bg-gray-300 dark:bg-gray-700 px-6 py-3 rounded-xl">
                    Kembali
                </a>

            </div>

        </form>

    </div>

</div>
@endsection