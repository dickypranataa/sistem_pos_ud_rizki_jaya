<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use League\CommonMark\Extension\TaskList\TaskListExtension;
use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained()->cascadeOnDelete();
            $table->string('sku');
            $table->string('nama_produk');
            $table->string('satuan');
            $table->integer('stok')->default(0);

            //harga beli
            $table->decimal('harga_modal',15,2);

            //3 level harga
            $table->decimal('harga_retail', 15,2);
            $table->decimal('harga_semi_grosir',15,2);
            $table->decimal('harga_grosir',15,2);

            $table->string('gambar')->nullable();
            $table->timestamps();   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
