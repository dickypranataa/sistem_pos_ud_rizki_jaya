<div class="flex flex-col lg:flex-row h-screen w-full bg-slate-50">

    {{-- Katalog Produk --}}
    <div class="w-full lg:w-2/3 flex flex-col p-4 sm:p-5 lg:border-r border-gray-100 bg-white">

        {{-- Header --}}
        <div class="flex flex-col gap-3 mb-4">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-extrabold text-gray-900 tracking-tight">Katalog Produk</h2>
                    <p class="text-xs text-gray-400 mt-0.5 font-medium">Klik produk untuk menambah ke keranjang</p>
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

            {{-- Baris Filter: Dropdown Kategori + Search --}}
            <div class="flex flex-col sm:flex-row gap-2">

                {{-- Dropdown Kategori --}}
                <div class="relative flex-shrink-0 sm:w-52">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <select wire:model.live="selectedKategori" id="dropdown-kategori"
                        class="w-full pl-9 pr-8 py-2.5 text-sm rounded-2xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 focus:bg-white text-gray-700 font-medium transition-all duration-200 appearance-none cursor-pointer">
                        <option value="">— Semua Kategori —</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                {{-- Search Produk --}}
                <div class="relative flex-1">
                    <input type="text" wire:model.live="search" id="search-produk"
                        placeholder="Cari nama produk atau SKU..."
                        class="w-full pl-9 pr-3 py-2.5 text-sm rounded-2xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 focus:bg-white placeholder-gray-400 transition-all duration-200">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                {{-- Tombol Reset (muncul jika ada filter aktif) --}}
                @if(!empty($selectedKategori) || !empty($search))
                    <button type="button" wire:click="resetFilter" title="Reset semua filter"
                        class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 rounded-2xl text-xs font-semibold bg-red-50 text-red-500 border border-red-100 hover:bg-red-100 active:scale-95 transition-all duration-200 flex-shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Reset
                    </button>
                @endif
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

        {{-- Info Filter Aktif --}}
        <div class="flex items-center justify-between mb-2">
            <p class="text-xs text-gray-400 font-medium">
                Menampilkan <span class="font-bold text-gray-700">{{ $produks->count() }}</span> produk
                @if(!empty($selectedKategori))
                    @php $namaKat = $kategoris->firstWhere('id', $selectedKategori)?->nama_kategori; @endphp
                    @if($namaKat)
                        &mdash; kategori <span class="text-blue-600 font-bold">{{ $namaKat }}</span>
                    @endif
                @endif
            </p>
        </div>

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

                @php
                    // Tentukan warna & label stok berdasarkan jumlah
                    if ($produk->stok == 0) {
                        $stokColor = 'bg-red-50 text-red-600 border-red-100';
                        $stokDotColor = 'bg-red-500';
                        $stokLabel = 'Habis';
                    } elseif ($produk->stok < 5) {
                        $stokColor = 'bg-amber-50 text-amber-700 border-amber-100';
                        $stokDotColor = 'bg-amber-500';
                        $stokLabel = 'Kritis';
                    } elseif ($produk->stok < 20) {
                        $stokColor = 'bg-blue-50 text-blue-600 border-blue-100';
                        $stokDotColor = 'bg-blue-400';
                        $stokLabel = 'Cukup';
                    } else {
                        $stokColor = 'bg-emerald-50 text-emerald-700 border-emerald-100';
                        $stokDotColor = 'bg-emerald-500';
                        $stokLabel = 'Aman';
                    }
                @endphp

                <div wire:key="produk-{{ $produk->id }}"
                    wire:click="{{ $habis ? '' : 'tambahKeKeranjang(' . $produk->id . ')' }}"
                    class="min-h-[250px] relative flex flex-col rounded-2xl border bg-white overflow-hidden transition-all duration-200
                            {{ $habis ? 'border-gray-100 opacity-50 grayscale cursor-not-allowed' : 'border-gray-100 hover:border-blue-400 hover:shadow-lg hover:-translate-y-0.5 cursor-pointer group active:scale-[0.98]' }}">

                    {{-- Badge HABIS di pojok (tetap ada untuk visibilitas cepat) --}}
                    @if ($produk->stok == 0)
                        <span
                            class="absolute top-2 left-2 z-10 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm">
                            HABIS
                        </span>
                    @endif

                    {{-- Gambar --}}
                    <div class="w-full bg-gray-50 flex items-center justify-center overflow-hidden" style="height: 120px;">
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
                        <h3 class="text-sm font-bold text-gray-900 leading-snug line-clamp-2"
                            title="{{ $produk->nama_produk }}">
                            {{ $produk->nama_produk }}
                        </h3>

                        {{-- Badge Kategori --}}
                        @if($produk->kategori)
                            <span
                                class="inline-flex items-center gap-1 mt-0.5 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-violet-50 text-violet-500 border border-violet-100 w-fit leading-none">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                {{ $produk->kategori->nama_kategori }}
                            </span>
                        @endif

                        {{-- Divider + Harga + Stok --}}
                        <div class="pt-2 mt-auto border-t border-gray-100">

                            {{-- Baris Harga + Tombol Tambah --}}
                            <div class="flex items-center justify-between gap-1 mb-1.5">
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

                            {{-- Baris Stok (selalu tampil) --}}
                            <div class="flex items-center gap-1.5 px-2 py-1 rounded-lg border {{ $stokColor }} w-full">
                                <span class="w-1.5 h-1.5 rounded-full flex-shrink-0 {{ $stokDotColor }}"></span>
                                <span class="text-[10px] font-bold flex-1 leading-none">Stok</span>
                                <span class="text-[10px] font-extrabold leading-none tabular-nums">
                                    {{ $produk->stok }} {{ $produk->satuan }}
                                </span>
                                <span class="text-[9px] font-semibold leading-none opacity-60">({{ $stokLabel }})</span>
                            </div>

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
                    <select id="select-pembayaran" onchange="pilihanMetode(this.value)"
                        class="w-full py-2.5 pl-3 pr-8 text-sm border-gray-200 rounded-2xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white font-medium text-gray-700 transition-all duration-200">
                        <option value="">-- Pilih Metode --</option>
                        @foreach ($metodePembayaran as $metode)
                            <option value="{{ $metode->id }}" {{ $pembayaran_id == $metode->id ? 'selected' : '' }}>
                                {{ $metode->nama_pembayaran }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" wire:model.live="pembayaran_id" id="hidden-pembayaran">
                </div>

                {{-- ======= FORM PIUTANG (muncul jika pilih Piutang/Bon) ======= --}}
                @if($isPiutang)
                    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-3.5 space-y-3">
                        <p class="text-[11px] font-bold text-amber-700 uppercase tracking-widest flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Data Piutang
                        </p>

                        {{-- Pencarian Pelanggan --}}
                        @if(!$pelanggan_id)
                            <div class="relative">
                                <label class="block text-[10px] font-bold text-amber-600 uppercase tracking-widest mb-1">Cari
                                    Pelanggan</label>
                                <input type="text" wire:model.live.debounce.300ms="pelanggan_search"
                                    placeholder="Ketik nama / no. HP..."
                                    class="w-full px-3 py-2 text-sm border border-amber-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-400 bg-white">
                                {{-- Hasil Pencarian --}}
                                @if(!empty($hasil_cari_pelanggan))
                                    <div
                                        class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden">
                                        @foreach($hasil_cari_pelanggan as $p)
                                            <button type="button"
                                                wire:click="pilihPelanggan({{ $p['id'] }}, '{{ $p['nama_pelanggan'] }}')"
                                                class="w-full text-left px-3 py-2 text-sm hover:bg-amber-50 transition-colors border-b border-gray-100 last:border-0">
                                                <span class="font-semibold text-gray-800">{{ $p['nama_pelanggan'] }}</span>
                                                @if($p['no_hp'])<span class="text-gray-400 text-xs ml-2">{{ $p['no_hp'] }}</span>@endif
                                                @if($p['alamat'])<br><span
                                                class="text-gray-400 text-xs">{{ Str::limit($p['alamat'], 40) }}</span>@endif
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            {{-- Toggle form pelanggan baru --}}
                            <button type="button" wire:click="toggleFormBaru"
                                class="text-[11px] font-semibold text-amber-600 hover:text-amber-800 underline flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                {{ $show_form_baru ? 'Batal Tambah Baru' : 'Tambah Pelanggan Baru' }}
                            </button>

                            @if($show_form_baru)
                                <div class="space-y-2 pt-1">
                                    <input type="text" wire:model="pelanggan_baru_nama" placeholder="Nama Pelanggan *"
                                        class="w-full px-3 py-2 text-sm border border-amber-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-400 bg-white">
                                    <input type="text" wire:model="pelanggan_baru_alamat" placeholder="Alamat (opsional)"
                                        class="w-full px-3 py-2 text-sm border border-amber-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-400 bg-white">
                                    <input type="text" wire:model="pelanggan_baru_no_hp" placeholder="No. HP (opsional)"
                                        class="w-full px-3 py-2 text-sm border border-amber-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-400 bg-white">
                                </div>
                            @endif

                        @else
                            {{-- Pelanggan sudah dipilih --}}
                            <div
                                class="flex items-center justify-between bg-white border border-amber-200 rounded-xl px-3 py-2">
                                <div>
                                    <p class="text-xs font-bold text-gray-700">{{ $pelanggan_nama }}</p>
                                    <p class="text-[10px] text-gray-400">Pelanggan terpilih</p>
                                </div>
                                <button type="button" wire:click="batalPilihPelanggan" class="text-red-400 hover:text-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        @endif

                        {{-- Uang Muka (DP) --}}
                        <div>
                            <label class="block text-[10px] font-bold text-amber-600 uppercase tracking-widest mb-1">Uang
                                Muka / DP (isi 0 jika tidak ada)</label>
                            <div class="relative">
                                <span
                                    class="absolute inset-y-0 left-3 flex items-center text-amber-500 text-sm font-bold">Rp</span>
                                <input type="text" id="dp-display" oninput="formatDP(this)" placeholder="0"
                                    autocomplete="off"
                                    class="w-full pl-10 pr-3 py-2 text-sm border border-amber-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-400 bg-white font-bold">
                                <input type="hidden" wire:model.live="dp" id="dp-hidden">
                            </div>
                            @php $dp = (int) str_replace('.', '', $this->dp);
                            $sisa = $total_harga - $dp; @endphp
                            @if($dp > 0 && $sisa > 0)
                                <p class="text-[11px] text-amber-700 mt-1 font-semibold">Kekurangan: Rp
                                    {{ number_format($sisa, 0, ',', '.') }}</p>
                            @endif
                        </div>

                        {{-- Jatuh Tempo --}}
                        <div>
                            <label class="block text-[10px] font-bold text-amber-600 uppercase tracking-widest mb-1">Jatuh
                                Tempo</label>
                            <input type="date" wire:model.live="jatuh_tempo" min="{{ now()->format('Y-m-d') }}"
                                class="w-full px-3 py-2 text-sm border border-amber-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-400 bg-white">
                        </div>
                    </div>
                @else
                    {{-- Input Uang Diterima (hanya tampil jika bukan piutang) --}}
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Uang
                            Diterima</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <span class="text-gray-400 text-sm font-bold">Rp</span>
                            </div>
                            <input type="text" id="bayar-display" oninput="formatUang(this)" placeholder="0"
                                autocomplete="off"
                                class="w-full pl-11 pr-3 py-2.5 text-sm border-gray-200 rounded-2xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent font-bold bg-white transition-all duration-200">
                            <input type="hidden" wire:model.live.debounce.300ms="bayar" id="bayar-hidden">
                        </div>
                    </div>
                @endif
            </div>

            @if(!$isPiutang)
                <div
                    class="flex justify-between items-center mb-4 px-4 py-3 rounded-2xl font-semibold border
                    {{ $kembalian < 0 ? 'bg-red-50 text-red-700 border-red-100' : 'bg-emerald-50 text-emerald-700 border-emerald-100' }}">
                    <span class="text-sm font-bold">Kembalian</span>
                    <span class="text-lg font-extrabold">Rp {{ number_format($kembalian, 0, ',', '.') }}</span>
                </div>
            @endif

            @php
                $dpInt = (int) str_replace('.', '', $this->dp);
                $piutangValid = $isPiutang && (!empty($pelanggan_id) || !empty($pelanggan_baru_nama)) && !empty($jatuh_tempo) && $dpInt >= 0 && $dpInt <= $total_harga;
                $tunaiValid = !$isPiutang && $kembalian >= 0;
                $canProcess = $total_harga > 0 && !empty($pembayaran_id) && !$this->hasInvalidQty() && ($piutangValid || $tunaiValid);
            @endphp
            <button wire:click="simpanTransaksi" wire:loading.attr="disabled" {{ $canProcess ? '' : 'disabled' }} class="w-full py-3.5 px-4 rounded-2xl text-sm font-bold text-white transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 active:scale-[0.98]
                    {{ $canProcess
    ? 'bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-200 hover:-translate-y-0.5'
    : 'bg-gray-200 text-gray-400 cursor-not-allowed' }}">
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
            let hidden = document.getElementById('bayar-hidden');
            if (hidden) { hidden.value = clean; hidden.dispatchEvent(new Event('input')); }
        }

        // Format DP dengan pemisah titik ribuan
        function formatDP(el) {
            let clean = el.value.replace(/\D/g, '');
            el.value = clean === '' ? '' : clean.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            let hidden = document.getElementById('dp-hidden');
            if (hidden) { hidden.value = clean; hidden.dispatchEvent(new Event('input')); }
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

            // Paksa reset visual input pencarian & dropdown kategori saat tombol Reset ditekan.
            // Diperlukan karena morphdom Livewire kadang tidak memperbarui elemen input
            // yang nilainya dianggap sudah sinkron oleh diffing algorithm.
            @this.on('reset-filter-inputs', () => {
                const searchInput = document.getElementById('search-produk');
                const kategoriSelect = document.getElementById('dropdown-kategori');

                if (searchInput) {
                    searchInput.value = '';
                }
                if (kategoriSelect) {
                    kategoriSelect.value = '';
                }
            });
        });
    </script>
</div>