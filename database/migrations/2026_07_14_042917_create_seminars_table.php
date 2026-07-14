<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seminars', function (Blueprint $table) {
            $table->id();

            $table->string('judul');
            $table->string('narasumber');
            $table->text('deskripsi');

            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai')->nullable();

            $table->string('lokasi');
            $table->unsignedInteger('kuota')->default(0);

            $table->decimal('harga', 12, 2)->default(0);

            $table->string('gambar')->nullable();

            $table->enum('status', [
                'draft',
                'dipublikasikan',
                'selesai',
            ])->default('draft');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seminars');
    }
};