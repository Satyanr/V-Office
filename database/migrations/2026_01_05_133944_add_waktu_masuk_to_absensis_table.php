<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->dateTime('waktu_masuk')->after('keterangan')->nullable();
        });
    }

    public function down()
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropColumn('waktu_masuk');
        });
    }
};
