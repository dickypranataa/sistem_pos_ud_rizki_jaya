<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('piutangs', function (Blueprint $table) {
            $table->id();
            // terhubung dengan table transaksi
            $table->foreignId('transaksi_id')->constrained('transaksis')->onDelete('cascade');
            // terhubung dengan table pelanggan
            $table->foreignId('pelanggan_id')->constrained('pelanggans')->onDelete('cascade');
            
            $table->integer('sisa_tagihan');
            $table->enum('status', ['belum_lunas', 'lunas'])->default('belum_lunas');
            $table->date('jatuh_tempo');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('piutangs');
    }
};
