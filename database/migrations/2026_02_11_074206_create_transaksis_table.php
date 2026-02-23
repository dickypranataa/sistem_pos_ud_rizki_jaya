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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            //menampilkan nama pembayaran
            $table->foreignId('pembayaran_id')->constrained()->cascadeOnDelete();
            //menampilkan nama user yang melayani
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->dateTime('waktu_transaksi');

            //tipe harga
            $table->enum('tipe_harga', ['retail', 'semi_grosir', 'grosir']);
            $table->decimal('total_harga', 15, 2);
            $table->decimal('bayar', 15, 2);
            $table->decimal('kembalian', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
