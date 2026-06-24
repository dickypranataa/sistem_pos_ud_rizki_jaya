<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

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

        // Scan folder storage produk untuk mencocokkan gambar secara dinamis berdasarkan nama produk
        $produkStoragePath = public_path('storage/produk');
        $availableImages = [];
        if (File::exists($produkStoragePath)) {
            $files = File::files($produkStoragePath);
            foreach ($files as $file) {
                $filename = $file->getFilename();
                $nameWithoutExt = pathinfo($filename, PATHINFO_FILENAME);
                // Lewati file yang memiliki nama acak/panjang (seperti hash upload default Laravel)
                if (strlen($nameWithoutExt) < 30) {
                    $availableImages[strtolower($nameWithoutExt)] = 'produk/' . $filename;
                }
            }
            // Urutkan kata kunci dari terpanjang ke terpendek agar pencocokan lebih spesifik
            uksort($availableImages, function($a, $b) {
                return strlen($b) - strlen($a);
            });
        }

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

                // ==========================================
                // 2. LOGIKA GAMBAR BERDASARKAN NAMA PRODUK
                // ==========================================
                $productName = trim($row[2] ?? 'Tanpa Nama');
                $cleanProductName = preg_replace('/[^a-z0-9]/', '', strtolower($productName));
                $imagePath = null;

                // Cari kecocokan kata kunci nama file di dalam nama produk
                foreach ($availableImages as $keyword => $path) {
                    if (str_contains($cleanProductName, strtolower($keyword))) {
                        $imagePath = $path;
                        break;
                    }
                }
                // ==========================================

                // 3. Satuan
                $unit = $this->cleanUnit($row[3] ?? 'Pcs');

                // 4. Ambil Angka Mentah dari CSV
                $rawPurchasePrice = $this->parseNumber($row[4] ?? 0); 
                $discountPercent  = $this->parseNumber($row[5] ?? 0); 
                $priceRetail      = $this->parseNumber($row[7] ?? 0); 

                // --- LOGIKA PERBAIKAN HARGA (SELISIH 2500) ---

                // A. Hitung Modal BERSIH (Netto)
                if ($discountPercent > 100) $discountPercent = 0; 
                $netPurchasePrice = $rawPurchasePrice - ($rawPurchasePrice * ($discountPercent / 100));
                
                if ($netPurchasePrice <= 100) $netPurchasePrice = $rawPurchasePrice;

                // B. Tentukan Harga Jual Retail (Ecer)
                if ($priceRetail <= $netPurchasePrice) {
                    $priceRetail = $netPurchasePrice * 1.30;
                }

                // C. Terapkan Selisih Rp 2.500 antar level
                $priceSemi      = $priceRetail - 2500;
                $priceWholesale = $priceSemi - 2500;

                // D. PROTEKSI ANTI RUGI (WAJIB ADA)
                $minProfitMargin = 1.05; // Minimal untung 5%
                $minWholesalePrice = $netPurchasePrice * $minProfitMargin;

                if ($priceWholesale < $minWholesalePrice) {
                    // Jika dikurangi 5000 ternyata rugi, kita naikkan dari bawah
                    $priceWholesale = $minWholesalePrice;       // Grosir diset ke Modal + 5%
                    $priceSemi      = $priceWholesale + 2500;   // Semi Grosir naik 2500 dari Grosir
                    $priceRetail    = $priceSemi + 2500;        // Retail naik 2500 dari Semi Grosir
                }

                // Simpan
                Produk::create([
                    'kategori_id'        => $category->id,
                    'sku'                => trim($row[0]),
                    'nama_produk'        => trim($row[2] ?? 'Tanpa Nama'),
                    'satuan'             => $unit,
                    'stok'               => 5, 
                    'harga_modal'        => $netPurchasePrice, 
                    'harga_retail'       => $priceRetail,
                    'harga_semi_grosir'  => $priceSemi,
                    'harga_grosir'       => $priceWholesale,
                    'gambar'             => $imagePath, // Menyimpan lokasi file atau NULL
                ]);
                $count++;
            }
            DB::commit();
            $this->command->info("Berhasil! $count produk diimpor dengan selisih harga Rp2.500 dan mapping gambar dinamis.");
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
        $clean = preg_replace('/[^0-9,.]/', '', $value);

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
            if (substr_count($clean, '.') > 1 || strlen(substr(strrchr($clean, '.'), 1)) === 3) {
                $clean = str_replace('.', '', $clean);
            } 
        }
        return (float) $clean;
    }
}