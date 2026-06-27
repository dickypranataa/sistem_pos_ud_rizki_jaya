<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Pembayaran;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\Piutang;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Livewire\Livewire;
use App\Livewire\KasirTransaksi;

class QABugHuntingTest extends TestCase
{
    use RefreshDatabase;

    private $admin;
    private $kasir;
    private $kategori;
    private $pembayaran;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create default roles
        $this->admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => 'password123', // Automatically hashed by Casts in User.php
            'role' => 'admin',
        ]);

        $this->kasir = User::create([
            'name' => 'Kasir Test',
            'email' => 'kasir@test.com',
            'password' => 'password123',
            'role' => 'kasir',
        ]);

        // 2. Create sample Kategori
        $this->kategori = Kategori::create([
            'nama_kategori' => 'Kategori Test',
        ]);

        // 3. Create sample Pembayaran
        $this->pembayaran = Pembayaran::create([
            'nama_pembayaran' => 'Cash',
        ]);
        
        Pembayaran::create([
            'nama_pembayaran' => 'Piutang',
        ]);
    }

    /**
     * Test 1: User Password Double-Hashing Bug on Update.
     */
    public function test_user_password_double_hashing_on_update()
    {
        // acting as admin
        $this->actingAs($this->admin);

        // Update user name and email, leaving password blank
        $response = $this->put(route('admin.user.update', $this->kasir->id), [
            'name' => 'Kasir Updated',
            'email' => 'kasir@test.com',
            'password' => '', // blank password should not re-hash or change password
            'role' => 'kasir',
        ]);

        $response->assertRedirect(route('admin.user.index'));

        // Refresh kasir and verify password is still valid for login
        $this->kasir->refresh();
        
        // Let's verify we can still authenticate this user
        $this->assertTrue(\Hash::check('password123', $this->kasir->password));

        // Now update with a new password
        $response = $this->put(route('admin.user.update', $this->kasir->id), [
            'name' => 'Kasir Updated 2',
            'email' => 'kasir@test.com',
            'password' => 'newpassword123',
            'role' => 'kasir',
        ]);

        $this->kasir->refresh();

        // Check if the updated password works
        // If it double-hashed, Hash::check('newpassword123', $this->kasir->password) will fail!
        $works = \Hash::check('newpassword123', $this->kasir->password);
        $this->assertTrue($works, 'Password failed to match. This indicates a double-hashing bug on update!');
    }

    /**
     * Test 2: Produk CRUD Validation for Negative and Decimal values.
     */
    public function test_produk_crud_negative_and_decimal_values()
    {
        $this->actingAs($this->admin);

        // Try storing negative stock
        $response = $this->post(route('admin.produk.store'), [
            'kategori_id' => $this->kategori->id,
            'sku' => 'SKU-NEG-1',
            'nama_produk' => 'Produk Negatif',
            'satuan' => 'Pcs',
            'stok' => -5,
            'harga_modal' => 1000,
            'harga_retail' => 1500,
            'harga_semi_grosir' => 1400,
            'harga_grosir' => 1300,
        ]);
        $response->assertSessionHasErrors(['stok']);

        // Try storing negative price
        $response = $this->post(route('admin.produk.store'), [
            'kategori_id' => $this->kategori->id,
            'sku' => 'SKU-NEG-2',
            'nama_produk' => 'Produk Negatif Harga',
            'satuan' => 'Pcs',
            'stok' => 10,
            'harga_modal' => -1000,
            'harga_retail' => 1500,
            'harga_semi_grosir' => 1400,
            'harga_grosir' => 1300,
        ]);
        $response->assertSessionHasErrors(['harga_modal']);

        // Try storing invalid non-integer stock
        $response = $this->post(route('admin.produk.store'), [
            'kategori_id' => $this->kategori->id,
            'sku' => 'SKU-NEG-3',
            'nama_produk' => 'Produk Stok Desimal',
            'satuan' => 'Pcs',
            'stok' => 5.5,
            'harga_modal' => 1000,
            'harga_retail' => 1500,
            'harga_semi_grosir' => 1400,
            'harga_grosir' => 1300,
        ]);
        $response->assertSessionHasErrors(['stok']);
    }

    /**
     * Test 3: Kategori CRUD validation.
     */
    public function test_kategori_crud_validation()
    {
        $this->actingAs($this->admin);

        // Store empty name
        $response = $this->post(route('admin.kategori.store'), [
            'nama_kategori' => '',
        ]);
        $response->assertSessionHasErrors(['nama_kategori']);

        // Store duplicate name
        $response = $this->post(route('admin.kategori.store'), [
            'nama_kategori' => 'Kategori Test',
        ]);
        $response->assertSessionHasErrors(['nama_kategori']);

        // Store special characters and minus sign
        $response = $this->post(route('admin.kategori.store'), [
            'nama_kategori' => '-',
        ]);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('kategoris', ['nama_kategori' => '-']);
    }

    /**
     * Test 4: Koreksi Stok validation and behavior.
     */
    public function test_koreksi_stok_negative_and_decimal_inputs()
    {
        $this->actingAs($this->admin);

        $produk = Produk::create([
            'kategori_id' => $this->kategori->id,
            'sku' => 'PROD-001',
            'nama_produk' => 'Produk A',
            'satuan' => 'Pcs',
            'stok' => 10,
            'harga_modal' => 1000,
            'harga_retail' => 1500,
            'harga_semi_grosir' => 1400,
            'harga_grosir' => 1300,
        ]);

        // 1. Check validation on negative amount (min is 1)
        $response = $this->post(route('admin.koreksi.store'), [
            'produk_id' => $produk->id,
            'jenis_koreksi' => 'correction_plus',
            'jumlah' => -5,
            'keterangan' => 'Koreksi Negatif',
        ]);
        $response->assertSessionHasErrors(['jumlah']);

        // 2. Check decimal amount (is it accepted? is it safe?)
        // Let's try sending 2.5
        $response = $this->post(route('admin.koreksi.store'), [
            'produk_id' => $produk->id,
            'jenis_koreksi' => 'correction_plus',
            'jumlah' => 2.5,
            'keterangan' => 'Koreksi Desimal',
        ]);
        $response->assertSessionHasErrors(['jumlah']);

        // 3. Subtract more than available
        $response = $this->post(route('admin.koreksi.store'), [
            'produk_id' => $produk->id,
            'jenis_koreksi' => 'correction_minus',
            'jumlah' => 100,
            'keterangan' => 'Kurang melebihi stok',
        ]);
        $response->assertSessionHas('error');
    }

    /**
     * Test 5: Livewire Transaksi Kasir Validation.
     */
    public function test_kasir_transaksi_livewire_negatives()
    {
        $produk = Produk::create([
            'kategori_id' => $this->kategori->id,
            'sku' => 'PROD-KASIR',
            'nama_produk' => 'Barang Kasir',
            'satuan' => 'Pcs',
            'stok' => 50,
            'harga_modal' => 5000,
            'harga_retail' => 7000,
            'harga_semi_grosir' => 6500,
            'harga_grosir' => 6000,
        ]);

        $pembayaranCash = $this->pembayaran;
        $pembayaranPiutang = Pembayaran::where('nama_pembayaran', 'like', '%piutang%')->first();

        // Acting as kasir
        $this->actingAs($this->kasir);

        // Test Livewire component
        Livewire::test(KasirTransaksi::class)
            // Add product to cart
            ->call('tambahKeKeranjang', $produk->id)
            ->assertSet('total_harga', 7000)
            // Try setting negative quantity
            ->set('keranjang.0.qty', -2)
            ->call('hitungTotal')
            ->call('simpanTransaksi');

        // Verify that no transaction was created in the database due to negative qty
        $this->assertDatabaseCount('transaksis', 0);

        // Run another component instance to test checkout errors and successful checkout
        Livewire::test(KasirTransaksi::class)
            ->call('tambahKeKeranjang', $produk->id)
            ->set('keranjang.0.qty', 5)
            ->call('hitungTotal')
            ->assertSet('total_harga', 35000)
            ->set('pembayaran_id', $pembayaranCash->id)
            // Try inputting cash amount less than total_harga (30000 < 35000)
            ->set('bayar', 30000)
            ->call('hitungTotal')
            ->call('simpanTransaksi');

        // Still no transaction should be created
        $this->assertDatabaseCount('transaksis', 0);

        // Try negative cash payment
        Livewire::test(KasirTransaksi::class)
            ->call('tambahKeKeranjang', $produk->id)
            ->set('keranjang.0.qty', 5)
            ->call('hitungTotal')
            ->set('pembayaran_id', $pembayaranCash->id)
            ->set('bayar', -5000)
            ->call('hitungTotal')
            ->call('simpanTransaksi');

        $this->assertDatabaseCount('transaksis', 0);

        // Set valid cash payment and complete checkout successfully
        Livewire::test(KasirTransaksi::class)
            ->call('tambahKeKeranjang', $produk->id)
            ->set('keranjang.0.qty', 5)
            ->call('hitungTotal')
            ->set('pembayaran_id', $pembayaranCash->id)
            ->set('bayar', 40000)
            ->call('hitungTotal')
            ->call('simpanTransaksi');

        // Transaction should be successfully created now
        $this->assertDatabaseCount('transaksis', 1);

        // Check if stock decreased by 5
        $produk->refresh();
        $this->assertEquals(45, $produk->stok);
    }

    /**
     * Test 6: Kasir Authorization Bypass on Transactions (BOLA).
     */
    public function test_kasir_authorization_bypass_on_transactions()
    {
        // 1. Create a transaction belonging to admin
        $adminTrx = Transaksi::create([
            'kode_transaksi' => 'TRX-ADMIN-001',
            'user_id' => $this->admin->id,
            'pembayaran_id' => $this->pembayaran->id,
            'tipe_harga' => 'retail',
            'total_harga' => 10000,
            'bayar' => 10000,
            'kembalian' => 0,
            'waktu_transaksi' => now(),
        ]);

        // 2. Create a transaction belonging to kasir
        $kasirTrx = Transaksi::create([
            'kode_transaksi' => 'TRX-KASIR-001',
            'user_id' => $this->kasir->id,
            'pembayaran_id' => $this->pembayaran->id,
            'tipe_harga' => 'retail',
            'total_harga' => 5000,
            'bayar' => 5000,
            'kembalian' => 0,
            'waktu_transaksi' => now(),
        ]);

        // 3. Act as kasir
        $this->actingAs($this->kasir);

        // Try to view admin's transaction show page
        $response = $this->get(route('kasir.riwayat.show', $adminTrx->id));
        
        // Assert that kasir cannot view admin's transaction details.
        // It should return 403 Forbidden or 404 Not Found.
        $this->assertTrue(
            $response->status() === 403 || $response->status() === 404,
            "Kasir was able to access Admin's transaction details page! This is an authorization bypass (BOLA) bug."
        );
    }
}
