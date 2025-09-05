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
        Schema::create('presensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pegawai')->constrained('pegawais');
            $table->date('tanggal_masuk');
            $table->time('jam_masuk');
            $table->string('foto_masuk');
            $table->date('tanggal_keluar');
            $table->time('jam_keluar');
            $table->string('foto_keluar');
            $table->string('total_jam');
            $table->integer('terlambat');
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensis');
    }
};
