<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('no_hp', 20)
                ->nullable()
                ->after('email');

            $table->text('alamat')
                ->nullable()
                ->after('no_hp');

            $table->enum('role', ['admin', 'peserta'])
                ->default('peserta')
                ->after('alamat');

            $table->enum(
                'status_akun',
                ['pending', 'diterima', 'ditolak']
            )
                ->default('pending')
                ->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'no_hp',
                'alamat',
                'role',
                'status_akun',
            ]);
        });
    }
};