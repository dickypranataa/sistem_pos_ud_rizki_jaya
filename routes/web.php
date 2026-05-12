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
use App\Http\Controllers\Admin\AsistenAiController;

//kasir
use App\Http\Controllers\Kasir\KasirController;
use App\Livewire\KasirTransaksi;
use App\Http\Controllers\Kasir\RiwayatTransaksi;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->group(function (){
    
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('produk', ProdukController::class);
    Route::resource('kategori', KategoriController::class);
    Route::resource('user', UserController::class);
    Route::resource('pembayaran', PembayaranController::class);
    
    Route::get('riwayat/export-pdf', [RiwayatStokController::class, 'exportPdf'])->name('riwayat.export_pdf');
    Route::get('riwayat', [RiwayatStokController::class, 'index'])->name('riwayat.index');
    //koreksi stok
    Route::get('koreksi', [KoreksiStokController::class, 'create'])->name('koreksi.create');
    Route::post('koreksi', [KoreksiStokController::class, 'store'])->name('koreksi.store');
    //struk dari riwayat transaksi
    Route::get('/transaksi/{id}/cetak', [TransaksiController::class, 'cetakStruk'])->name('transaksi.cetak');
    //export riwayat transaksi
    Route::get('transaksi/export', [TransaksiController::class, 'export'])->name('transaksi.export');
    Route::resource('transaksi', TransaksiController::class);

    // Rute untuk AI
    Route::post('/tanya-ai', [AsistenAiController::class, 'tanya'])->name('tanya.ai');
});

Route::middleware('role:kasir')->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/dashboard', [KasirController::class, 'index'])->name('dashboard');

    //transaksi
    Route::get('/transaksi', KasirTransaksi::class)->name('transaksi');

    //cetak
    Route::get('/transaksi/{id}/cetak', [KasirController::class, 'cetakStruk'])->name('transaksi.cetak');

    //riwayat
    Route::get('riwayat/export', [RiwayatTransaksi::class, 'export'])->name('riwayat.export');
    Route::get('riwayat', [RiwayatTransaksi::class, 'index'])->name('riwayat.index');
    Route::get('riwayat/{id}', [RiwayatTransaksi::class, 'show'])->name('riwayat.show');
    Route::get('riwayat/{id}/cetak', [RiwayatTransaksi::class, 'cetakStruk'])->name('riwayat.cetak');
});

Route::middleware('auth')->group(function () {

    Route::get('/notifikasi/read-all', function () {
        request()->user()->unreadNotifications->markAsRead();
        return redirect()->back();
    })->name('notifikasi.readAll');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
