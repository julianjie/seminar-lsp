<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seminar_registrations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('seminar_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('status_pendaftaran', [
                'pending',
                'diterima',
                'ditolak',
            ])->default('pending');

            $table->text('catatan_admin')->nullable();

            $table->timestamp('tanggal_verifikasi')->nullable();

            $table->timestamps();

            $table->unique([
                'user_id',
                'seminar_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seminar_registrations');
    }
};