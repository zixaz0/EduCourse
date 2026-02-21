<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
        $table->id();
        $table->foreignId('tagihan_id')->constrained('tagihan')->onDelete('cascade');
        $table->string('nomor_unik')->unique();
        $table->decimal('uang_bayar', 10, 2);
        $table->decimal('uang_kembalian', 10, 2)->default(0);
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
