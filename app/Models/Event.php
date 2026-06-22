<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_event',
        'jenis_event',
        'tanggal_event',
        'lokasi_venue',
        'kota_venue',
        'latitude',
        'longitude',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_event' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rundowns(): HasMany
    {
        return $this->hasMany(Rundown::class)->orderBy('waktu');
    }

    public function budgetItems(): HasMany
    {
        return $this->hasMany(BudgetItem::class);
    }

    public function getTotalAnggaranAttribute(): int
    {
        return $this->budgetItems->sum('estimasi_biaya');
    }
}