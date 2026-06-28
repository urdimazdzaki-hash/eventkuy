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
        'tipe_lokasi',
        'jumlah_tamu',
        'harga_per_orang',
        'nama_paket',
        'fasilitas_paket',
        'tanggal_event',
        'lokasi_venue',
        'kota_venue',
        'latitude',
        'longitude',
        'catatan',
        'status_pembayaran',
        'jumlah_dibayar',
        'paid_at',
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

    public function checklists(): HasMany
    {
        return $this->hasMany(Checklist::class);
    }

    public function getSubtotalCateringAttribute(): int
    {
        return ($this->jumlah_tamu ?? 0) * ($this->harga_per_orang ?? 0);
    }

    public function getTotalAnggaranAttribute(): int
    {
        return $this->subtotal_catering + $this->budgetItems->sum('estimasi_biaya');
    }

    public function getSisaPembayaranAttribute(): int
    {
        return max(0, $this->total_anggaran - ($this->jumlah_dibayar ?? 0));
    }

    public function getPersenTerbayarAttribute(): int
    {
        if ($this->total_anggaran <= 0) {
            return 0;
        }
        return min(100, round((($this->jumlah_dibayar ?? 0) / $this->total_anggaran) * 100));
    }

    public function getDaftarFasilitasAttribute(): array
    {
        if (!$this->fasilitas_paket) {
            return [];
        }

        return array_filter(explode("\n", $this->fasilitas_paket));
    }

    public function getHariMenujuEventAttribute(): int
    {
        return (int) floor(now()->diffInHours($this->tanggal_event, false) / 24);
    }

    public function getButuhCekCuacaAttribute(): bool
    {
        return $this->tipe_lokasi === 'outdoor'
            && $this->hari_menuju_event >= 0
            && $this->hari_menuju_event <= 3;
    }
}