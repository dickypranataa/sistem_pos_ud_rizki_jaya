<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $file = database_path('seeders/csv/produk_riski_jaya.csv');

        if (!file_exists($file)) {
            $this->command->error("File CSV tidak ditemukan di: $file");
            return;
        }

        $handle = fopen($file, 'r');
        
        // Deteksi Separator
        $firstLine = fgets($handle);
        $separator = (str_contains($firstLine, ';')) ? ';' : ',';
        rewind($handle);

        fgetcsv($handle, 1000, $separator); // Lewati Header

        DB::beginTransaction();
        try {
            $count = 0;
            while (($row = fgetcsv($handle, 1000, $separator)) !== FALSE) {
                if (count($row) < 8) continue; 
                if (empty($row[0])) continue;

                // 1. Kategori
                $categoryName = trim(strtoupper($row[1] ?? 'UMUM'));
                if (empty($categoryName)) $categoryName = 'UMUM';
                $category = Kategori::firstOrCreate(['nama_kategori' => $categoryName]);

                // 2. Satuan
                $unit = $this->cleanUnit($row[3] ?? 'Pcs');

                // 3. Ambil Angka Mentah dari CSV
                $rawPurchasePrice = $this->parseNumber($row[4] ?? 0); // Harga Beli (6200)
                $discountPercent  = $this->parseNumber($row[5] ?? 0); // Diskon (15)
                $priceRetail      = $this->parseNumber($row[7] ?? 0); // Jual (6500)

                // --- LOGIKA PERBAIKAN HARGA ---

                // A. Hitung Modal BERSIH (Netto)
                // Rumus: Harga Beli - (Harga Beli * Diskon%)
                // Contoh: 6200 - (6200 * 15%) = 5270
                if ($discountPercent > 100) $discountPercent = 0; // Jaga-jaga error input
                $netPurchasePrice = $rawPurchasePrice - ($rawPurchasePrice * ($discountPercent / 100));
                
                // Failsafe: Jika hasil minus/nol, pakai harga beli awal
                if ($netPurchasePrice <= 100) $netPurchasePrice = $rawPurchasePrice;

                // B. Tentukan Harga Jual Retail (Ecer)
                // Jika kosong/0 di Excel, set otomatis untung 30% dari modal
                if ($priceRetail <= $netPurchasePrice) {
                    $priceRetail = $netPurchasePrice * 1.30;
                }

                // C. Hitung Target Harga Grosir (Misal diskon 10% dari Ecer)
                $priceWholesale = $priceRetail * 0.90; 
                $priceSemi      = $priceRetail * 0.95;

                // D. PROTEKSI ANTI RUGI (WAJIB ADA)
                // Pastikan Grosir minimal untung 5% dari Modal Bersih
                $minProfitMargin = 1.05; // 5% Margin
                $minWholesalePrice = $netPurchasePrice * $minProfitMargin;

                if ($priceWholesale < $minWholesalePrice) {
                    // Jika harga grosir ternyata rugi (di bawah modal + 5%), NAIKKAN!
                    $priceWholesale = $minWholesalePrice;       // Set Grosir = Modal + 5%
                    $priceSemi      = $priceWholesale * 1.05;   // Set Semi   = Grosir + 5%
                    $priceRetail    = $priceSemi * 1.05;        // Set Retail = Semi + 5%
                }

                // Simpan
                Produk::create([
                    'kategori_id'        => $category->id,
                    'sku'                => trim($row[0]),
                    'nama_produk'        => trim($row[2] ?? 'Tanpa Nama'),
                    'satuan'             => $unit,
                    'stok'               => 5, 
                    'harga_modal'        => $netPurchasePrice, // SIMPAN HARGA BERSIH (5270)
                    'harga_retail'       => $priceRetail,
                    'harga_semi_grosir'  => $priceSemi,
                    'harga_grosir'       => $priceWholesale,
                ]);
                $count++;
            }
            DB::commit();
            $this->command->info("Berhasil! $count produk diimpor. Hirarki harga telah diperbaiki.");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("Gagal: " . $e->getMessage());
        }
        fclose($handle);
    }

    private function cleanUnit($unitRaw)
    {
        $unit = strtolower(trim($unitRaw));
        if (str_contains($unit, 'pcs')) return 'Pcs';
        if (str_contains($unit, 'batang')) return 'Batang';
        if (str_contains($unit, 'set')) return 'Set';
        return ucfirst($unit) ?: 'Pcs';
    }

    private function parseNumber($value)
    {
        if (empty($value)) return 0;
        // Hapus Rp, spasi, dan karakter aneh
        $clean = preg_replace('/[^0-9,.]/', '', $value);

        // Deteksi format 1.000,00 vs 1,000.00
        if (str_contains($clean, '.') && str_contains($clean, ',')) {
            $lastDot = strrpos($clean, '.');
            $lastComma = strrpos($clean, ',');
            if ($lastComma > $lastDot) {
                $clean = str_replace('.', '', $clean); 
                $clean = str_replace(',', '.', $clean); 
            } else {
                $clean = str_replace(',', '', $clean);
            }
        } elseif (str_contains($clean, '.')) {
            // Jika titik lebih dari satu (1.000.000) atau 3 digit pas (1.500) -> itu ribuan
            if (substr_count($clean, '.') > 1 || strlen(substr(strrchr($clean, '.'), 1)) === 3) {
                $clean = str_replace('.', '', $clean);
            } 
        }
        return (float) $clean;
    }
}