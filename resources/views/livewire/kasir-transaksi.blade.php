<div class="flex flex-col lg:flex-row h-screen w-full bg-slate-50">

    {{-- Katalog Produk --}}
    <div class="w-full lg:w-2/3 flex flex-col p-4 sm:p-5 lg:border-r border-gray-100 bg-white">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-5">
            <div>
                <h2 class="text-lg font-extrabold text-gray-900 tracking-tight">Katalog Produk</h2>
                <p class="text-xs text-gray-400 mt-0.5 font-medium">Klik produk untuk menambah ke keranjang</p>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <div class="relative flex-1 sm:w-64">
                    <input type="text" wire:model.live="search" placeholder="Cari produk atau SKU..."
                        class="w-full pl-9 pr-3 py-2.5 text-sm rounded-2xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 focus:bg-white placeholder-gray-400 transition-all duration-200">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <a href="{{ route('kasir.dashboard') }}"
                    class="inline-flex items-center gap-1.5 bg-gray-100 hover:bg-gray-200 active:scale-95 text-gray-600 py-2.5 px-3.5 rounded-2xl text-sm font-semibold transition-all duration-200 flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        @if (session()->has('error'))
            <div
                class="bg-red-50 border border-red-200 text-red-600 px-4 py-2.5 text-sm rounded-2xl mb-4 flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Grid Produk --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-3 overflow-y-auto pr-1 pb-4">
            @forelse($produks as $produk)
                @php
                    $hargaTampil = $produk->harga_retail;
                    if ($tipe_harga == 'semi_grosir')
                        $hargaTampil = $produk->harga_semi_grosir;
                    if ($tipe_harga == 'grosir')
                        $hargaTampil = $produk->harga_grosir;
                    $habis = $produk->stok < 1;
                @endphp

                <div wire:key="produk-{{ $produk->id }}"
                    wire:click="{{ $habis ? '' : 'tambahKeKeranjang(' . $produk->id . ')' }}"
                    class="min-h-[250px] relative flex flex-col rounded-2xl border bg-white overflow-hidden transition-all duration-200
                        {{ $habis ? 'border-gray-100 opacity-50 grayscale cursor-not-allowed' : 'border-gray-100 hover:border-blue-400 hover:shadow-lg hover:-translate-y-0.5 cursor-pointer group active:scale-[0.98]' }}">

                    {{-- Badge Stok --}}
                    @if ($produk->stok == 0)
                        <span
                            class="absolute top-2 right-2 z-10 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm">
                            Habis
                        </span>
                    @elseif($produk->stok < 5)
                        <span
                            class="absolute top-2 right-2 z-10 bg-amber-400 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm">
                            Sisa {{ $produk->stok }}
                        </span>
                    @endif

                    {{-- Gambar --}}
                    <div class="w-full bg-gray-50 flex items-center justify-center overflow-hidden" style="height: 130px;">
                        @if ($produk->gambar)
                            <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_produk }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($produk->nama_produk) }}&background=EFF6FF&color=2563EB&size=256"
                                alt="{{ $produk->nama_produk }}" class="w-full h-full object-cover">
                        @endif
                    </div>

                    {{-- Info Produk --}}
                    <div class="flex flex-col flex-1 p-3 gap-1">

                        {{-- SKU --}}
                        <span
                            class="text-[10px] font-mono font-semibold text-gray-400 uppercase tracking-wider leading-none">
                            {{ $produk->sku ?? 'No SKU' }}
                        </span>

                        {{-- Nama Produk --}}
                        <h3 class="text-sm font-bold text-gray-900 leading-snug line-clamp-2 flex-1"
                            title="{{ $produk->nama_produk }}">
                            {{ $produk->nama_produk }}
                        </h3>

                        {{-- Divider + Harga --}}
                        <div class="pt-2 mt-1 border-t border-gray-100 flex items-center justify-between gap-1">
                            <span class="text-sm font-extrabold text-blue-600 leading-none">
                                Rp {{ number_format($hargaTampil, 0, ',', '.') }}
                            </span>
                            @if(!$habis)
                                <div
                                    class="w-6 h-6 rounded-lg bg-blue-50 group-hover:bg-blue-600 flex items-center justify-center transition-all duration-200 flex-shrink-0">
                                    <svg class="w-3.5 h-3.5 text-blue-400 group-hover:text-white transition-colors duration-200"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            @empty
                <div class="col-span-full flex flex-col items-center justify-center py-16 text-gray-400">
                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                    <p class="text-sm font-bold text-gray-400">Produk tidak ditemukan</p>
                    <p class="text-xs text-gray-300 mt-1 font-medium">Coba kata kunci lain</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Keranjang --}}
    <div
        class="w-full lg:w-1/3 bg-white flex flex-col border-t lg:border-t-0 border-gray-100 shadow-2xl lg:shadow-none z-20">

        {{-- Tipe Harga --}}
        <div class="px-5 py-4 border-b border-gray-100 bg-blue-50/60">
            <label class="block text-[11px] font-bold text-blue-700 uppercase tracking-widest mb-2">Kategori Harga
                Pelanggan</label>
            <select wire:model.live="tipe_harga"
                class="w-full py-2.5 pl-3 pr-8 text-sm border-blue-200 bg-white rounded-2xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent font-semibold text-gray-700 transition-all duration-200">
                <option value="retail">🛒 Retail (Eceran)</option>
                <option value="semi_grosir">🤝 Langganan (Semi Grosir)</option>
                <option value="grosir">🏢 Mitra (Grosir)</option>
            </select>
        </div>

        {{-- Item List --}}
        <div class="flex-1 px-5 py-4 overflow-y-auto space-y-3">
            @forelse($keranjang as $index => $item)
                <div wire:key="cart-item-{{ $index }}" class="flex flex-col py-3.5 border-b border-gray-100 last:border-0">
                    <div class="flex justify-between items-start mb-2.5">
                        <div class="pr-2 flex-1 min-w-0">
                            <h4 class="font-bold text-gray-900 text-sm leading-tight truncate">{{ $item['nama'] }}</h4>
                            <p class="text-[11px] text-gray-400 mt-0.5 font-medium">@ Rp
                                {{ number_format($item['harga'], 0, ',', '.') }}
                            </p>
                        </div>
                        <button wire:click="hapusDariKeranjang({{ $index }})"
                            class="text-gray-300 hover:text-red-500 active:scale-95 transition-all duration-150 flex-shrink-0 p-1.5 rounded-xl hover:bg-red-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden bg-gray-50">
                            <input type="number" wire:model.live="keranjang.{{ $index }}.qty"
                                wire:key="input-qty-{{ $index }}-{{ $item['qty'] }}" min="1" max="{{ $item['stok_asli'] }}"
                                class="w-16 py-2 text-center text-sm border-0 focus:ring-0 text-gray-900 font-bold bg-transparent">
                        </div>
                        <span class="font-extrabold text-gray-900 text-sm">
                            Rp {{ number_format($item['harga'] * $item['qty'], 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="h-full flex flex-col items-center justify-center text-gray-400 py-10">
                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <p class="text-sm font-bold text-gray-400">Keranjang Kosong</p>
                    <p class="text-xs text-gray-300 mt-1 font-medium">Klik produk untuk menambahkan</p>
                </div>
            @endforelse
        </div>

        {{-- Summary & Checkout --}}
        <div class="p-5 bg-slate-50 border-t border-gray-100">

            <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray-200">
                <span class="text-sm font-bold text-gray-500 uppercase tracking-wider">Total Tagihan</span>
                <span class="text-2xl font-extrabold text-gray-900">Rp
                    {{ number_format($total_harga, 0, ',', '.') }}</span>
            </div>

            <div class="space-y-3.5 mb-4">
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Metode
                        Pembayaran</label>
                    <select id="select-pembayaran"
                        onchange="pilihanMetode(this.value)"
                        class="w-full py-2.5 pl-3 pr-8 text-sm border-gray-200 rounded-2xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white font-medium text-gray-700 transition-all duration-200">
                        <option value="">-- Pilih Metode --</option>
                        @foreach ($metodePembayaran as $metode)
                            <option value="{{ $metode->id }}" {{ $pembayaran_id == $metode->id ? 'selected' : '' }}>{{ $metode->nama_pembayaran }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" wire:model="pembayaran_id" id="hidden-pembayaran">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Uang
                        Diterima</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <span class="text-gray-400 text-sm font-bold">Rp</span>
                        </div>
                        {{-- Input tampilan dengan format titik ribuan --}}
                        <input type="text" id="bayar-display"
                            oninput="formatUang(this)"
                            placeholder="0"
                            autocomplete="off"
                            class="w-full pl-11 pr-3 py-2.5 text-sm border-gray-200 rounded-2xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent font-bold bg-white transition-all duration-200">
                        {{-- Input tersembunyi untuk Livewire binding --}}
                        <input type="hidden" wire:model.live.debounce.300ms="bayar" id="bayar-hidden">
                    </div>
                </div>
            </div>

            <div
                class="flex justify-between items-center mb-4 px-4 py-3 rounded-2xl font-semibold border
                {{ $kembalian < 0 ? 'bg-red-50 text-red-700 border-red-100' : 'bg-emerald-50 text-emerald-700 border-emerald-100' }}">
                <span class="text-sm font-bold">Kembalian</span>
                <span class="text-lg font-extrabold">Rp {{ number_format($kembalian, 0, ',', '.') }}</span>
            </div>

            <button wire:click="simpanTransaksi" wire:loading.attr="disabled" {{ $total_harga == 0 || $kembalian < 0 || empty($pembayaran_id) || $this->hasInvalidQty() ? 'disabled' : '' }} class="w-full py-3.5 px-4 rounded-2xl text-sm font-bold text-white transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 active:scale-[0.98]
                    {{ $total_harga == 0 || $kembalian < 0 || empty($pembayaran_id) || $this->hasInvalidQty()
    ? 'bg-gray-200 text-gray-400 cursor-not-allowed'
    : 'bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-200 hover:-translate-y-0.5' }}">
                <span wire:loading.remove wire:target="simpanTransaksi" class="flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Proses Transaksi
                </span>
                <span wire:loading wire:target="simpanTransaksi" class="flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    Menyimpan...
                </span>
            </button>
        </div>

    </div>

    <script>
        // Format angka dengan pemisah titik ribuan
        function formatUang(el) {
            let clean = el.value.replace(/\D/g, '');
            el.value = clean === '' ? '' : clean.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            // Kirim nilai bersih (tanpa titik) ke hidden input Livewire
            let hidden = document.getElementById('bayar-hidden');
            hidden.value = clean;
            hidden.dispatchEvent(new Event('input'));
        }

        // Kirim pilihan metode pembayaran langsung ke Livewire
        function pilihanMetode(val) {
            let hidden = document.getElementById('hidden-pembayaran');
            hidden.value = val;
            hidden.dispatchEvent(new Event('input'));
            // Paksa Livewire request segera tanpa debounce
            setTimeout(() => Livewire.all()[0]?.commit(), 50);
        }

        document.addEventListener('livewire:initialized', () => {
            @this.on('buka-struk', (event) => {
                window.open(event.url, '_blank', 'width=400,height=600,toolbar=no,scrollbars=yes,resizable=yes');
            });

            // Reset tampilan form setelah transaksi berhasil
            @this.on('livewire:update', () => {
                let display = document.getElementById('bayar-display');
                let hidden = document.getElementById('bayar-hidden');
                if (display && hidden && hidden.value === '' && @this.bayar == 0) {
                    display.value = '';
                }
            });
        });
    </script>
</div>