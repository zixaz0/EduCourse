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
        Schema::create('tagihan', function (Blueprint $table) {
        $table->id();
        $table->foreignId('peserta_id')->constrained('peserta')->onDelete('cascade');
        $table->string('bulan_tahun'); // contoh: 01-2026
        $table->decimal('total_tagihan', 10, 2);
        $table->enum('status', ['belum_lunas', 'lunas'])->default('belum_lunas');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihan');
    }
};
