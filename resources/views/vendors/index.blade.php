@extends('layouts.app')

@section('title', 'Vendor')

@section('content')
<div class="p-8">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">
                Manajemen Vendor
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">
                Kelola seluruh vendor untuk kebutuhan event.
            </p>
        </div>

        <a href="{{ route('vendors.create') }}"
            class="bg-coral hover:bg-red-500 text-white px-5 py-3 rounded-xl flex items-center gap-2 transition">
            <i data-lucide="plus"></i>
            Tambah Vendor
        </a>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow overflow-hidden">

        <table class="w-full">
            <thead class="bg-gray-100 dark:bg-gray-800">
                <tr>
                    <th class="p-4 text-left">Nama Vendor</th>
                    <th class="p-4 text-left">Kategori</th>
                    <th class="p-4 text-left">Kontak</th>
                    <th class="p-4 text-left">Harga</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>

            @forelse($vendors as $vendor)

                <tr class="border-b dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800">

                    <td class="p-4">
                        {{ $vendor->nama_vendor }}
                    </td>

                    <td class="p-4">
                        {{ $vendor->kategori }}
                    </td>

                    <td class="p-4">
                        {{ $vendor->kontak }}
                    </td>

                    <td class="p-4">
                        Rp {{ number_format($vendor->harga,0,',','.') }}
                    </td>

                    <td class="p-4">
                        <div class="flex justify-center gap-2">

                            <a href="{{ route('vendors.edit',$vendor->id) }}"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg">
                                Edit
                            </a>

                            <form action="{{ route('vendors.destroy',$vendor->id) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button
                                    onclick="return confirm('Yakin ingin menghapus vendor ini?')"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg">
                                    Hapus
                                </button>

                            </form>

                        </div>
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="5" class="text-center py-10 text-gray-400">
                        Belum ada data vendor.
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>
@endsection