<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('judul');

            $table->longText('isi');

            $table->string('gambar')->nullable();

            $table->dateTime('tanggal_publish');

            $table->enum('status', [
                'draft',
                'dipublikasikan',
            ])->default('draft');

            $table->timestamps();

            $table->index([
                'status',
                'tanggal_publish',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};