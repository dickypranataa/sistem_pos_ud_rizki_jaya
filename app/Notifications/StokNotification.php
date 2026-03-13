<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StokNotification extends Notification
{
    use Queueable;
    private $produk;

    /**
     * Create a new notification instance.
     */
    public function __construct($produk)
    {
        //
        $this->produk = $produk;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        //stok menipis dan stok habis
        $status = $this->produk->stok == 0 ? 'Habis!' : 'Menipis!';
        $warna  = $this->produk->stok == 0 ? 'text-red-600' : 'text-yellow-600';

        return [
            'tipe'      => 'stok',
            'judul'     => 'Stok ' . $status,
            'pesan'     => 'Produk ' . $this->produk->nama_produk . ' tersisa ' . $this->produk->stok . ' ' . $this->produk->satuan . '.',
            'warna'     => $warna,
            'url'       => route('admin.produk.index')
        ];
    }
}
