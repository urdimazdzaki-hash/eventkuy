<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
        'nama_vendor',
        'kategori',
        'kontak',
        'alamat',
        'harga',
        'keterangan',
    ];
}