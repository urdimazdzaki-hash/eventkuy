@extends('layouts.app')

@section('title', 'Data Venue')

@section('content')
<div class="p-8">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">
                Data Venue
            </h1>
            <p class="text-gray-500 dark:text-gray-400">
                Kelola seluruh data venue acara.
            </p>
        </div>

        <a href="{{ route('venues.create') }}"
            class="bg-coral text-white px-5 py-2 rounded-xl hover:opacity-90 transition">
            + Tambah Venue
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow overflow-hidden">

        <table class="w-full">
            <thead class="bg-gray-100 dark:bg-gray-800">
                <tr>
                    <th class="p-4 text-left">No</th>
                    <th class="p-4 text-left">Nama Venue</th>
                    <th class="p-4 text-left">Alamat</th>
                    <th class="p-4 text-left">Kapasitas</th>
                    <th class="p-4 text-left">Harga</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($venues as $venue)

                <tr class="border-t dark:border-gray-700">

                    <td class="p-4">
                        {{ $loop->iteration }}
                    </td>

                    <td class="p-4">
                        {{ $venue->nama_venue }}
                    </td>

                    <td class="p-4">
                        {{ $venue->alamat }}
                    </td>

                    <td class="p-4">
                        {{ $venue->kapasitas }} Orang
                    </td>

                    <td class="p-4">
                        Rp {{ number_format($venue->harga,0,',','.') }}
                    </td>

                    <td class="p-4 text-center">

                        <a href="{{ route('venues.edit',$venue->id) }}"
                            class="bg-yellow-400 text-white px-3 py-1 rounded-lg hover:bg-yellow-500">
                            Edit
                        </a>

                        <form action="{{ route('venues.destroy',$venue->id) }}"
                            method="POST"
                            class="inline">

                            @csrf
                            @method('DELETE')

                            <button
                                onclick="return confirm('Yakin ingin menghapus venue ini?')"
                                class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600">
                                Hapus
                            </button>

                        </form>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="6" class="text-center py-8 text-gray-500">
                        Belum ada data venue.
                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>
@endsection