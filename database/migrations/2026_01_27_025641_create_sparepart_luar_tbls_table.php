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
        Schema::create('sparepart_luar_tbls', function (Blueprint $table) {
            $table->id();
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
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sparepart_luar_tbls');
    }
};
