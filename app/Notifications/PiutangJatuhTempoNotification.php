<?php

namespace App\Notifications;

use App\Models\Piutang;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PiutangJatuhTempoNotification extends Notification
{
    use Queueable;

    public function __construct(private Piutang $piutang) {}

    /**
     * Kirim hanya ke channel database (in-app notification).
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Data notifikasi yang disimpan ke tabel notifications.
     */
    public function toArray(object $notifiable): array
    {
        $pelanggan   = $this->piutang->pelanggan->nama_pelanggan ?? 'Pelanggan';
        $sisa        = number_format($this->piutang->sisa_tagihan, 0, ',', '.');
        $jatuhTempo  = \Carbon\Carbon::parse($this->piutang->jatuh_tempo)->translatedFormat('d F Y');
        $kode        = $this->piutang->transaksi->kode_transaksi ?? '-';
        $hariLewat   = \Carbon\Carbon::parse($this->piutang->jatuh_tempo)->diffInDays(now());

        return [
            'tipe'   => 'piutang_jatuh_tempo',
            'judul'  => 'Piutang Jatuh Tempo!',
            'pesan'  => "{$pelanggan} (#{$kode}) memiliki sisa tagihan Rp {$sisa} yang sudah melewati jatuh tempo {$jatuhTempo} ({$hariLewat} hari lalu).",
            'warna'  => 'text-orange-600',
            'url'    => route('admin.piutang.show', $this->piutang->id),
        ];
    }
}
