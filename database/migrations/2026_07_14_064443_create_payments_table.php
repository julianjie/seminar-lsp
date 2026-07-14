<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('seminar_registration_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            $table->string('nama_pengirim');
            $table->string('bank_pengirim', 100);

            $table->decimal('jumlah_bayar', 12, 2);
            $table->date('tanggal_bayar');

            $table->string('bukti_pembayaran');

            $table->enum('status_pembayaran', [
                'pending',
                'diterima',
                'ditolak',
            ])->default('pending');

            $table->text('catatan_admin')->nullable();
            $table->timestamp('tanggal_verifikasi')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};