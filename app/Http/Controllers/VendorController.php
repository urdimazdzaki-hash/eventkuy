<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vendors = Vendor::latest()->get();

        return view('vendors.index', compact('vendors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('vendors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'kontak' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'harga' => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]);

        Vendor::create($request->only([
            'nama_vendor',
            'kategori',
            'kontak',
            'alamat',
            'harga',
            'keterangan',
        ]));

        return redirect()->route('vendors.index')
            ->with('success', 'Vendor berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vendor $vendor)
    {
        return view('vendors.show', compact('vendor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vendor $vendor)
    {
        return view('vendors.edit', compact('vendor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vendor $vendor)
    {
        $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'kontak' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'harga' => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]);

        $vendor->update($request->only([
            'nama_vendor',
            'kategori',
            'kontak',
            'alamat',
            'harga',
            'keterangan',
        ]));

        return redirect()->route('vendors.index')
            ->with('success', 'Vendor berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vendor $vendor)
    {
        $vendor->delete();

        return redirect()->route('vendors.index')
            ->with('success', 'Vendor berhasil dihapus.');
    }
}