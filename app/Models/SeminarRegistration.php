<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeminarRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'seminar_id',
        'status_pendaftaran',
        'catatan_admin',
        'tanggal_verifikasi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_verifikasi' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function seminar(): BelongsTo
    {
        return $this->belongsTo(Seminar::class);
    }
}