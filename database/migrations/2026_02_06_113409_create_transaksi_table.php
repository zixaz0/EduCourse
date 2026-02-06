<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_produk')->constrained('produk')->cascadeOnDelete();
            $table->foreignId('id_peserta')->constrained('peserta')->cascadeOnDelete();
            $table->string('nomor_unik', 10)->unique();
            $table->integer('uang_bayar');
            $table->integer('uang_kembali');
            $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};