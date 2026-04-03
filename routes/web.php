<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

//admin
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\TransaksiController;
use App\Http\Controllers\Admin\RiwayatStokController;
use App\Http\Controllers\Admin\KoreksiStokController;

//kasir
use App\Http\Controllers\Kasir\KasirController;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->group(function (){
    
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('produk', ProdukController::class);
    Route::resource('kategori', KategoriController::class);
    Route::resource('user', UserController::class);
    Route::resource('pembayaran', PembayaranController::class);
    Route::resource('transaksi', TransaksiController::class);
    Route::get('riwayat', [RiwayatStokController::class, 'index'])->name('riwayat.index');
    //koreksi stok
    Route::get('koreksi', [KoreksiStokController::class, 'create'])->name('koreksi.create');
    Route::post('koreksi', [KoreksiStokController::class, 'store'])->name('koreksi.store');
    //struk
    Route::get('/transaksi/{id}/cetak', [TransaksiController::class, 'cetakStruk'])->name('transaksi.cetak');

    //notifikasi
    Route::get('/notifikasi/read-all', function () {
        // Menandai semua notifikasi milik admin yang sedang login menjadi "sudah dibaca"
        request()->user()->unreadNotifications->markAsRead();
        return redirect()->back();
    })->name('notifikasi.readAll');
});

Route::middleware('role:kasir')->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/dashboard', [KasirController::class, 'index'])->name('dashboard');

    //transaksi
    Route::get('/transaksi', \App\Livewire\KasirTransaksi::class)->name('transaksi');

    //cetak
    Route::get('/transaksi/{id}/cetak', [KasirController::class, 'cetakStruk'])->name('transaksi.cetak');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
