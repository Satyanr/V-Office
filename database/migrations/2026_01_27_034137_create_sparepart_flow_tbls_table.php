<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sparepart_flow_tbls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_tiket')->nullable()->constrained('tiket_tbls')->onDelete('cascade');
            $table->foreignId('id_led')->nullable()->constrained('led_tbls')->onDelete('cascade');
            $table->date('tanggal')->nullable();
            $table->string('modul')->nullable();
            $table->string('power_supply')->nullable();
            $table->string('receiving_card')->nullable();
            $table->string('led_lamp')->nullable();
            $table->string('ic')->nullable();
            $table->string('kabel_dc')->nullable();
            $table->string('kabel_lan')->nullable();
            $table->string('kabel_flat')->nullable();
            $table->string('masking')->nullable();
            $table->string('magnet')->nullable();
            $table->string('kabinet')->nullable();
            $table->enum('status', ['bagus', 'rusak', 'diluar', 'repair'])->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sparepart_flow_tbls');
    }
};
