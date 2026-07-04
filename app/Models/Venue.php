<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $fillable = [
        'nama_venue',
        'alamat',
        'kapasitas',
        'harga',
        'fasilitas',
        'keterangan',
    ];
}