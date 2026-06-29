<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Piutang;
use App\Models\PembayaranPiutang;
use App\Models\RiwayatPerpanjanganTempo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PiutangKasirController extends Controller
{
    /**
     * Kasir hanya melihat piutang yang dibuat oleh dirinya sendiri.
     */
    public function index(Request $request)
    {
        $status = $request->input('status', 'belum_lunas');
        $search = $request->input('search');

        $piutangs = Piutang::with(['transaksi.user', 'pelanggan'])
            ->whereHas('transaksi', fn($q) => $q->where('user_id', Auth::id()))
            ->when($status !== 'semua', fn($q) => $q->where('status', $status))
            ->when($search, function ($q) use ($search) {
                $q->whereHas('pelanggan', fn($pq) =>
                    $pq->where('nama_pelanggan', 'like', "%$search%")
                       ->orWhere('no_hp', 'like', "%$search%")
                )->orWhereHas('transaksi', fn($tq) =>
                    $tq->where('kode_transaksi', 'like', "%$search%")
                );
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('kasir.piutang.index', compact('piutangs', 'status', 'search'));
    }

    /**
     * Detail piutang — hanya jika piutang ini milik kasir yang sedang login.
     */
    public function show($id)
    {
        $piutang = Piutang::with([
            'transaksi.detail.produk',
            'transaksi.user',
            'transaksi.pembayaran',
            'pelanggan',
            'pembayaranPiutangs.user',
            'riwayatPerpanjanganTempos.user',
        ])->whereHas('transaksi', fn($q) => $q->where('user_id', Auth::id()))
          ->findOrFail($id);

        return view('kasir.piutang.show', compact('piutang'));
    }

    /**
     * Kasir terima cicilan untuk piutang yang dia buat.
     */
    public function storePembayaran(Request $request, $id)
    {
        $piutang = Piutang::whereHas('transaksi', fn($q) => $q->where('user_id', Auth::id()))
            ->findOrFail($id);

        if ($piutang->status === 'lunas') {
            return back()->with('error', 'Piutang ini sudah lunas!');
        }

        $request->validate([
            'jumlah_bayar' => 'required|integer|min:1|max:' . $piutang->sisa_tagihan,
            'tanggal_bayar' => 'required|date',
        ], [
            'jumlah_bayar.max' => 'Jumlah bayar melebihi sisa tagihan (Rp ' . number_format($piutang->sisa_tagihan, 0, ',', '.') . ').',
        ]);

        $pesan = DB::transaction(function () use ($request, $id) {
            // Lock row agar tidak ada dua request yang mengubah sisa_tagihan bersamaan
            $piutang = Piutang::whereHas('transaksi', fn($q) => $q->where('user_id', Auth::id()))
                ->lockForUpdate()
                ->findOrFail($id);

            if ($piutang->status === 'lunas') {
                throw new \Exception('Piutang ini sudah lunas!');
            }

            $jumlah = (int) $request->jumlah_bayar;
            if ($jumlah > $piutang->sisa_tagihan) {
                throw new \Exception('Jumlah bayar melebihi sisa tagihan yang tersedia.');
            }

            PembayaranPiutang::create([
                'piutang_id'    => $piutang->id,
                'user_id'       => Auth::id(),
                'jumlah_bayar'  => $jumlah,
                'tanggal_bayar' => $request->tanggal_bayar,
            ]);

            $sisaBaru = $piutang->sisa_tagihan - $jumlah;
            $piutang->update([
                'sisa_tagihan' => $sisaBaru,
                'status'       => $sisaBaru <= 0 ? 'lunas' : 'belum_lunas',
            ]);

            return $sisaBaru <= 0
                ? 'Piutang berhasil DILUNASI!'
                : 'Cicilan berhasil disimpan. Sisa tagihan: Rp ' . number_format($sisaBaru, 0, ',', '.');
        });

        return redirect()->route('kasir.piutang.show', $id)->with('success', $pesan);
    }

    /**
     * Kasir bisa perpanjang tempo untuk piutang yang dia buat.
     */
    public function storePerpanjangan(Request $request, $id)
    {
        $piutang = Piutang::whereHas('transaksi', fn($q) => $q->where('user_id', Auth::id()))
            ->findOrFail($id);

        if ($piutang->status === 'lunas') {
            return back()->with('error', 'Piutang ini sudah lunas.');
        }

        $request->validate([
            'tempo_baru'          => 'required|date|after:today',
            'alasan_perpanjangan' => 'nullable|string|max:255',
        ]);

        RiwayatPerpanjanganTempo::create([
            'piutang_id'          => $piutang->id,
            'user_id'             => Auth::id(),
            'tempo_lama'          => $piutang->jatuh_tempo,
            'tempo_baru'          => $request->tempo_baru,
            'alasan_perpanjangan' => $request->alasan_perpanjangan,
        ]);

        $piutang->update(['jatuh_tempo' => $request->tempo_baru]);

        return redirect()->route('kasir.piutang.show', $piutang->id)
            ->with('success', 'Jatuh tempo berhasil diperpanjang hingga ' . \Carbon\Carbon::parse($request->tempo_baru)->format('d M Y') . '.');
    }

    /**
     * Cetak struk bukti cicilan.
     */
    public function cetakCicilan($id, $cicilanId)
    {
        // Pastikan cicilan ini milik piutang yang diminta DAN piutang milik kasir ini
        $cicilan = PembayaranPiutang::with([
            'piutang.pelanggan',
            'piutang.transaksi',
            'user',
        ])->where('piutang_id', $id)
          ->whereHas('piutang.transaksi', fn($q) => $q->where('user_id', Auth::id()))
          ->findOrFail($cicilanId);

        return view('admin.piutang.cetak_cicilan', compact('cicilan'));
    }
}
