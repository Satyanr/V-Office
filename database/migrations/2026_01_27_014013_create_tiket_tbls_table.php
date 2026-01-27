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
        Schema::create('tiket_tbls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_ledtbl')->constrained('led_tbls')->onDelete('cascade');
            $table->foreignId('id_sparepart_luar')->nullable()->constrained('sparepart_luar_tbls')->onDelete('set null');
            $table->string('kode_tiket')->unique();
            $table->date('tanggal_laporan')->nullable();
            $table->date('tanggal_solve')->nullable();
            $table->string('status')->nullable();
            $table->string('keterangan_kerusakan')->nullable();
            $table->string('foto_before')->nullable();
            $table->string('foto_solve')->nullable();
            $table->string('teknisi')->nullable();
            $table->string('keterangan_solve')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiket_tbls');
    }
};
