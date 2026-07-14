<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'seminar_registration_id',
        'nama_pengirim',
        'bank_pengirim',
        'jumlah_bayar',
        'tanggal_bayar',
        'bukti_pembayaran',
        'status_pembayaran',
        'catatan_admin',
        'tanggal_verifikasi',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_bayar' => 'decimal:2',
            'tanggal_bayar' => 'date',
            'tanggal_verifikasi' => 'datetime',
        ];
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(
            SeminarRegistration::class,
            'seminar_registration_id'
        );
    }
}