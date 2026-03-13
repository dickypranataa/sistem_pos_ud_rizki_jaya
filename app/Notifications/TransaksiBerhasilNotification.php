<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TransaksiBerhasilNotification extends Notification
{
    use Queueable;

    private $transaksi;

    public function __construct($transaksi)
    {
        $this->transaksi = $transaksi;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'tipe'      => 'transaksi',
            'judul'     => 'Transaksi Baru!',
            'pesan'     => 'Trx ' . $this->transaksi->kode_transaksi . ' senilai Rp ' . number_format($this->transaksi->total_harga, 0, ',', '.'),
            'warna'     => 'text-green-600',
            'url'       => route('admin.transaksi.show', $this->transaksi->id) // Link menuju detail struk
        ];
    }
}