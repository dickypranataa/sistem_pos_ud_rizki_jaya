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
use App\Http\Controllers\Admin\PiutangController;

//kasir
use App\Http\Controllers\Kasir\KasirController;
use App\Livewire\KasirTransaksi;
use App\Http\Controllers\Kasir\RiwayatTransaksi;
use App\Http\Controllers\Kasir\PiutangKasirController;


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

    // Manajemen Piutang
    Route::get('piutang', [PiutangController::class, 'index'])->name('piutang.index');
    Route::get('piutang/{id}', [PiutangController::class, 'show'])->name('piutang.show');
    Route::post('piutang/{id}/bayar', [PiutangController::class, 'storePembayaran'])->name('piutang.bayar');
    Route::post('piutang/{id}/perpanjang', [PiutangController::class, 'storePerpanjangan'])->name('piutang.perpanjang');
    Route::get('piutang/{id}/cetak-cicilan/{cicilanId}', [PiutangController::class, 'cetakCicilan'])->name('piutang.cetak_cicilan');
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

    // Piutang Kasir
    Route::get('piutang', [PiutangKasirController::class, 'index'])->name('piutang.index');
    Route::get('piutang/{id}', [PiutangKasirController::class, 'show'])->name('piutang.show');
    Route::post('piutang/{id}/bayar', [PiutangKasirController::class, 'storePembayaran'])->name('piutang.bayar');
    Route::post('piutang/{id}/perpanjang', [PiutangKasirController::class, 'storePerpanjangan'])->name('piutang.perpanjang');
    Route::get('piutang/{id}/cetak-cicilan/{cicilanId}', [PiutangKasirController::class, 'cetakCicilan'])->name('piutang.cetak_cicilan');
});

Route::middleware('auth')->group(function () {

    // Tandai semua notifikasi sebagai sudah dibaca
    Route::get('/notifikasi/read-all', function () {
        request()->user()->unreadNotifications->markAsRead();
        return redirect()->back();
    })->name('notifikasi.readAll');

    // Halaman Profil (Dapat diakses oleh admin dan kasir)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

require __DIR__.'/auth.php';
