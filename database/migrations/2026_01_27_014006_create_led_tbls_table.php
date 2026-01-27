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
        Schema::create('led_tbls', function (Blueprint $table) {
            $table->id();
            $table->string('project_name')->nullable();
            $table->string('client')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('pixel')->nullable();
            $table->string('ukuran')->nullable();
            $table->string('brand')->nullable();
            $table->string('no_batch')->nullable();
            $table->foreignId('id_spareparts')->constrained('sparepart_tbls')->onDelete('set null');
            $table->foreignId('id_sparepart_rusak')->nullable()->constrained('sparepart_rusak_tbls')->onDelete('set null');
            $table->foreignId('id_sparepart_repair')->nullable()->constrained('sparepart_repair_tbls')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('led_tbls');
    }
};
