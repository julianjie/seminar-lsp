<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seminar extends Model
{

    use HasFactory;

    protected $fillable = [
        'judul',
        'narasumber',
        'deskripsi',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'lokasi',
        'kuota',
        'harga',
        'gambar',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'harga' => 'decimal:2',
            'kuota' => 'integer',
        ];
    }
        public function registrations(): HasMany
    {
        return $this->hasMany(SeminarRegistration::class);
    }
}