<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'judul',
        'isi',
        'gambar',
        'tanggal_publish',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_publish' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }
}