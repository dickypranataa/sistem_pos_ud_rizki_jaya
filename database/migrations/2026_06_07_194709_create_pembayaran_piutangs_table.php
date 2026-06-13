<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pembayaran_piutangs', function (Blueprint $table) {
            $table->id();
            // terhubung ke tabel piutang
            $table->foreignId('piutang_id')->constrained('piutangs')->onDelete('cascade');
            // terhubung ke tabel user
            $table->foreignId('user_id')->constrained('users'); 
            
            $table->integer('jumlah_bayar');
            $table->date('tanggal_bayar');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pembayaran_piutangs');
    }
};