<div class="flex flex-col lg:flex-row h-[calc(100vh-64px)] w-full bg-gray-50">
    
    <div class="w-full lg:w-2/3 flex flex-col p-4 sm:p-6 lg:border-r border-gray-200">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Katalog Produk</h2>
            <div class="w-full sm:w-1/2 relative">
                <input type="text" wire:model.live="search" placeholder="Cari nama produk atau SKU..." 
                       class="w-full pl-9 pr-3 py-2 text-sm rounded-md border border-gray-300 shadow-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition placeholder-gray-400">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        @if (session()->has('error'))
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-2 text-sm rounded-md mb-4 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 overflow-y-auto pr-1 pb-4">
            @forelse($produks as $produk)
                @php
                    $hargaTampil = $produk->harga_retail;
                    if($tipe_harga == 'semi_grosir') $hargaTampil = $produk->harga_semi_grosir;
                    if($tipe_harga == 'grosir') $hargaTampil = $produk->harga_grosir;
                @endphp

                <div wire:key="produk-{{ $produk->id }}" 
                     wire:click="tambahKeKeranjang({{ $produk->id }})" 
                     class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden cursor-pointer hover:border-indigo-400 hover:shadow-md transition-all duration-150 {{ $produk->stok < 1 ? 'opacity-50 grayscale cursor-not-allowed' : '' }}">
                    
                    <div class="p-3 flex flex-col h-full relative">
                        @if($produk->stok == 0)
                            <span class="absolute top-0 right-0 bg-red-100 text-red-700 text-[10px] px-1.5 py-0.5 rounded-bl font-semibold border-b border-l border-red-200">Habis</span>
                        @elseif($produk->stok < 5)
                            <span class="absolute top-0 right-0 bg-amber-100 text-amber-700 text-[10px] px-1.5 py-0.5 rounded-bl font-semibold border-b border-l border-amber-200">Sisa {{ $produk->stok }}</span>
                        @endif

                        <div class="flex-1 mt-1">
                            <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-0.5">{{ $produk->sku ?? 'No SKU' }}</p>
                            <h3 class="font-medium text-gray-900 text-sm leading-tight line-clamp-2">{{ $produk->nama_produk }}</h3>
                        </div>
                        <div class="mt-2 pt-2 border-t border-gray-100">
                            <span class="font-semibold text-indigo-600 text-sm">
                                Rp {{ number_format($hargaTampil, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center py-10 text-gray-500">
                    <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    <p class="text-sm">Produk tidak ditemukan.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="w-full lg:w-1/3 bg-white flex flex-col z-20">
        
        <div class="px-5 py-4 border-b border-gray-200 bg-gray-50">
            <label class="block text-xs font-medium text-gray-700 mb-1.5">Kategori Harga Pelanggan</label>
            <select wire:model.live="tipe_harga" class="w-full py-1.5 pl-3 pr-8 text-sm border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="retail">Retail (Eceran)</option>
                <option value="semi_grosir">Langganan (Semi Grosir)</option>
                <option value="grosir">Mitra (Grosir)</option>
            </select>
        </div>

        <div class="flex-1 px-5 py-4 overflow-y-auto space-y-3">
            @forelse($keranjang as $index => $item)
                <div class="flex flex-col py-3 border-b border-gray-100 last:border-0">
                    <div class="flex justify-between items-start mb-2">
                        <div class="pr-3">
                            <h4 class="font-medium text-gray-900 text-sm leading-tight">{{ $item['nama'] }}</h4>
                            <p class="text-[11px] text-gray-500 mt-0.5">@ Rp {{ number_format($item['harga'], 0, ',', '.') }}</p>
                        </div>
                        <button wire:click="hapusDariKeranjang({{ $index }})" class="text-gray-400 hover:text-red-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center border border-gray-300 rounded-md">
                            <input type="number" wire:model.live="keranjang.{{ $index }}.qty" min="1" max="{{ $item['stok_asli'] }}" 
                                   class="w-14 py-1 text-center text-sm border-0 focus:ring-0 text-gray-700 font-medium">
                        </div>
                        <span class="font-semibold text-gray-900 text-sm">Rp {{ number_format($item['harga'] * $item['qty'], 0, ',', '.') }}</span>
                    </div>
                </div>
            @empty
                <div class="h-full flex flex-col items-center justify-center text-gray-500">
                    <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <p class="text-sm font-medium">Keranjang Kosong</p>
                    <p class="text-xs text-gray-400 mt-1">Pilih produk di sebelah kiri</p>
                </div>
            @endforelse
        </div>

        <div class="p-5 bg-gray-50 border-t border-gray-200">
            
            <div class="flex justify-between items-center mb-4">
                <span class="text-sm font-medium text-gray-600">Total Tagihan</span>
                <span class="text-xl font-bold text-gray-900">Rp {{ number_format($total_harga, 0, ',', '.') }}</span>
            </div>

            <div class="space-y-3 mb-5">
                <div>
                    <label class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wide mb-1">Metode Pembayaran</label>
                    <select wire:model="pembayaran_id" class="w-full py-2 pl-3 pr-8 text-sm border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Pilih Metode</option>
                        @foreach($metodePembayaran as $metode)
                            <option value="{{ $metode->id }}">{{ $metode->nama_pembayaran }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wide mb-1">Uang Diterima</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">Rp</span>
                        </div>
                        <input type="number" wire:model.live.debounce.500ms="bayar" placeholder="0" 
                               class="w-full pl-9 pr-3 py-2 text-sm border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 font-medium">
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center mb-5 px-3 py-2 rounded-md {{ $kembalian < 0 ? 'bg-red-50 text-red-700 border border-red-100' : 'bg-emerald-50 text-emerald-700 border border-emerald-100' }}">
                <span class="text-sm font-medium">Kembalian</span>
                <span class="text-lg font-bold">Rp {{ number_format($kembalian, 0, ',', '.') }}</span>
            </div>

            <button 
                wire:click="simpanTransaksi"
                wire:loading.attr="disabled"
                {{ ($total_harga == 0 || $kembalian < 0 || empty($pembayaran_id)) ? 'disabled' : '' }}
                class="w-full py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                       {{ ($total_harga == 0 || $kembalian < 0 || empty($pembayaran_id)) ? 'bg-gray-300 cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-700' }}">
                <span wire:loading.remove wire:target="simpanTransaksi">Proses Transaksi</span>
                <span wire:loading wire:target="simpanTransaksi">Menyimpan...</span>
            </button>
        </div>

    </div>
    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('buka-struk', (event) => {
                // Membuka tab baru (atau popup) berukuran kecil seperti layar kasir
                window.open(event.url, '_blank', 'width=400,height=600,toolbar=no,scrollbars=yes,resizable=yes');
            });
        });
    </script>
</div>