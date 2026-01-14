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
        Schema::create('project_tbls', function (Blueprint $table) {
            $table->id();
            $table->string('kode_project')->unique();
            $table->foreignId('id_konsumens')->constrained('konsumen_tbls')->onDelete('cascade');
            $table->foreignId('id_layanans')->nullable()->constrained('layanan_tbls')->onDelete('cascade');
            $table->enum('status', ['baru', 'proses', 'selesai', 'diambil'])->default('baru');
            $table->string('total_harga')->nullable();
            $table->string('sisa_pembayaran')->nullable();
            $table->date('tanggal_pengingat')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_tbls');
    }
};
