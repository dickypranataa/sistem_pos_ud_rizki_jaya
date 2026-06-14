<?php

namespace App\Console\Commands;

use App\Models\Piutang;
use App\Models\User;
use App\Notifications\PiutangJatuhTempoNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class CheckPiutangJatuhTempo extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:check-piutang-jatuh-tempo';

    /**
     * The console command description.
     */
    protected $description = 'Kirim notifikasi ke admin untuk piutang yang sudah melewati jatuh tempo.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // Ambil semua piutang yang belum lunas dan sudah melewati jatuh tempo
        $piutangTerlambat = Piutang::with(['pelanggan', 'transaksi'])
            ->where('status', 'belum_lunas')
            ->whereDate('jatuh_tempo', '<', now()->toDateString())
            ->get();

        if ($piutangTerlambat->isEmpty()) {
            $this->info('Tidak ada piutang yang melewati jatuh tempo.');
            return;
        }

        // Ambil semua user admin yang akan menerima notifikasi
        $admins = User::where('role', 'admin')->get();

        if ($admins->isEmpty()) {
            $this->warn('Tidak ada user admin ditemukan.');
            return;
        }

        $terkirim = 0;

        foreach ($piutangTerlambat as $piutang) {
            // Cek apakah notifikasi untuk piutang ini sudah dikirim HARI INI
            // agar tidak flood notifikasi jika command berjalan berkali-kali
            $sudahDikirimHariIni = $admins->first()
                ->notifications()
                ->whereDate('created_at', now()->toDateString())
                ->where('type', PiutangJatuhTempoNotification::class)
                ->where('data->url', route('admin.piutang.show', $piutang->id))
                ->exists();

            if ($sudahDikirimHariIni) {
                continue; // Lewati, sudah dikirim hari ini
            }

            Notification::send($admins, new PiutangJatuhTempoNotification($piutang));
            $terkirim++;
        }

        $this->info("Selesai. {$terkirim} notifikasi piutang jatuh tempo dikirim ke {$admins->count()} admin.");
    }
}

