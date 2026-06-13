<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('riwayat_perpanjangan_tempos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('piutang_id')->constrained('piutangs')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users'); // Admin/Kasir yang memberi izin
            
            $table->date('tempo_lama');
            $table->date('tempo_baru');
            $table->string('alasan_perpanjangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('riwayat_perpanjangan_tempos');
    }
};