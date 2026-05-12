<div class="flex flex-col lg:flex-row h-[calc(100vh-64px)] w-full bg-gray-50">

    {{-- Katalog Produk --}}
    <div class="w-full lg:w-2/3 flex flex-col p-4 sm:p-5 lg:border-r border-gray-200 bg-white">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-5">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Katalog Produk</h2>
                <p class="text-xs text-gray-400 mt-0.5">Klik produk untuk menambah ke keranjang</p>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <div class="relative flex-1 sm:w-56">
                    <input type="text" wire:model.live="search" placeholder="Cari produk atau SKU..."
                        class="w-full pl-9 pr-3 py-2 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 placeholder-gray-400">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <a href="{{ route('kasir.dashboard') }}"
                    class="inline-flex items-center gap-1.5 bg-gray-100 hover:bg-gray-200 text-gray-600 py-2 px-3 rounded-xl text-sm font-semibold transition flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        @if (session()->has('error'))
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-2.5 text-sm rounded-xl mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 overflow-y-auto pr-1 pb-4">
            @forelse($produks as $produk)
                @php
                    $hargaTampil = $produk->harga_retail;
                    if ($tipe_harga == 'semi_grosir') $hargaTampil = $produk->harga_semi_grosir;
                    if ($tipe_harga == 'grosir') $hargaTampil = $produk->harga_grosir;
                @endphp

                <div wire:key="produk-{{ $produk->id }}" wire:click="tambahKeKeranjang({{ $produk->id }})"
                    class="bg-white rounded-xl border {{ $produk->stok < 1 ? 'border-gray-100 opacity-50 grayscale cursor-not-allowed' : 'border-gray-200 hover:border-blue-400 hover:shadow-md cursor-pointer' }} overflow-hidden transition-all duration-150 relative">

                    @if ($produk->stok == 0)
                        <span class="absolute top-0 right-0 bg-red-500 text-white text-[9px] px-1.5 py-0.5 rounded-bl-lg font-bold">Habis</span>
                    @elseif($produk->stok < 5)
                        <span class="absolute top-0 right-0 bg-amber-400 text-white text-[9px] px-1.5 py-0.5 rounded-bl-lg font-bold">Sisa {{ $produk->stok }}</span>
                    @endif

                    <div class="p-3 flex flex-col h-full">
                        <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-0.5 font-mono">{{ $produk->sku ?? 'No SKU' }}</p>
                        <h3 class="font-semibold text-gray-900 text-sm leading-tight line-clamp-2 flex-1">{{ $produk->nama_produk }}</h3>
                        <div class="mt-2 pt-2 border-t border-gray-100">
                            <span class="font-bold text-blue-600 text-sm">Rp {{ number_format($hargaTampil, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center py-12 text-gray-400">
                    <svg class="w-10 h-10 mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <p class="text-sm font-medium">Produk tidak ditemukan.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Keranjang --}}
    <div class="w-full lg:w-1/3 bg-white flex flex-col border-t lg:border-t-0 border-gray-200 shadow-xl lg:shadow-none z-20">

        {{-- Tipe Harga --}}
        <div class="px-5 py-4 border-b border-gray-100 bg-blue-50/50">
            <label class="block text-xs font-bold text-blue-700 uppercase tracking-wider mb-2">Kategori Harga Pelanggan</label>
            <select wire:model.live="tipe_harga"
                class="w-full py-2 pl-3 pr-8 text-sm border-blue-200 bg-white rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent font-medium text-gray-700">
                <option value="retail">🛒 Retail (Eceran)</option>
                <option value="semi_grosir">🤝 Langganan (Semi Grosir)</option>
                <option value="grosir">🏢 Mitra (Grosir)</option>
            </select>
        </div>

        {{-- Item List --}}
        <div class="flex-1 px-5 py-4 overflow-y-auto space-y-3">
            @forelse($keranjang as $index => $item)
                <div wire:key="cart-item-{{ $index }}" class="flex flex-col py-3 border-b border-gray-100 last:border-0">
                    <div class="flex justify-between items-start mb-2">
                        <div class="pr-2 flex-1">
                            <h4 class="font-semibold text-gray-900 text-sm leading-tight">{{ $item['nama'] }}</h4>
                            <p class="text-[11px] text-gray-400 mt-0.5">@ Rp {{ number_format($item['harga'], 0, ',', '.') }}</p>
                        </div>
                        <button wire:click="hapusDariKeranjang({{ $index }})"
                            class="text-gray-300 hover:text-red-500 transition-colors flex-shrink-0 p-1 rounded-lg hover:bg-red-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden">
                            <input type="number" wire:model.live="keranjang.{{ $index }}.qty"
                                wire:key="input-qty-{{ $index }}-{{ $item['qty'] }}" min="1"
                                max="{{ $item['stok_asli'] }}"
                                class="w-16 py-1.5 text-center text-sm border-0 focus:ring-0 text-gray-800 font-bold bg-transparent">
                        </div>
                        <span class="font-bold text-gray-900 text-sm">
                            Rp {{ number_format($item['harga'] * $item['qty'], 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="h-full flex flex-col items-center justify-center text-gray-400 py-10">
                    <svg class="w-14 h-14 mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <p class="text-sm font-semibold text-gray-400">Keranjang Kosong</p>
                    <p class="text-xs text-gray-300 mt-1">Klik produk untuk menambahkan</p>
                </div>
            @endforelse
        </div>

        {{-- Summary & Checkout --}}
        <div class="p-5 bg-gray-50 border-t border-gray-200">

            <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray-200">
                <span class="text-sm font-semibold text-gray-600">Total Tagihan</span>
                <span class="text-2xl font-bold text-gray-900">Rp {{ number_format($total_harga, 0, ',', '.') }}</span>
            </div>

            <div class="space-y-3 mb-4">
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Metode Pembayaran</label>
                    <select wire:model="pembayaran_id"
                        class="w-full py-2.5 pl-3 pr-8 text-sm border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                        <option value="">-- Pilih Metode --</option>
                        @foreach ($metodePembayaran as $metode)
                            <option value="{{ $metode->id }}">{{ $metode->nama_pembayaran }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Uang Diterima</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-400 text-sm font-semibold">Rp</span>
                        </div>
                        <input type="number" wire:model.live.debounce.500ms="bayar" placeholder="0"
                            class="w-full pl-10 pr-3 py-2.5 text-sm border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent font-bold bg-white">
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center mb-4 px-4 py-3 rounded-xl font-semibold
                {{ $kembalian < 0 ? 'bg-red-50 text-red-700 border border-red-100' : 'bg-emerald-50 text-emerald-700 border border-emerald-100' }}">
                <span class="text-sm">Kembalian</span>
                <span class="text-lg font-bold">Rp {{ number_format($kembalian, 0, ',', '.') }}</span>
            </div>

            <button wire:click="simpanTransaksi" wire:loading.attr="disabled"
                {{ $total_harga == 0 || $kembalian < 0 || empty($pembayaran_id) ? 'disabled' : '' }}
                class="w-full py-3 px-4 rounded-xl text-sm font-bold text-white transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500
                    {{ $total_harga == 0 || $kembalian < 0 || empty($pembayaran_id) ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700 shadow-md hover:shadow-lg' }}">
                <span wire:loading.remove wire:target="simpanTransaksi" class="flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Proses Transaksi
                </span>
                <span wire:loading wire:target="simpanTransaksi">Menyimpan...</span>
            </button>
        </div>

    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('buka-struk', (event) => {
                window.open(event.url, '_blank', 'width=400,height=600,toolbar=no,scrollbars=yes,resizable=yes');
            });
        });
    </script>
</div>