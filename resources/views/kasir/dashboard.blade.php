@extends('Layout.kasir')

@section('content')

    {{-- Header --}}
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold tracking-widest text-primary-500 uppercase mb-1">
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </p>
                <h1 class="text-2xl font-bold text-gray-800">
                    Selamat datang, <span class="text-primary-600">{{ Auth::user()->username ?? Auth::user()->name ?? 'Kasir' }}</span> 👋
                </h1>
                <p class="text-sm text-gray-400 mt-1">Berikut ringkasan aktivitas kasir hari ini.</p>
            </div>
        </div>
    </div>

    {{-- ===== STAT CARDS ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        {{-- Total Kelas --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-primary-50 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-chalkboard-user text-primary-600 text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium">Total Kelas</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalKelas ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-0.5">kelas tersedia</p>
            </div>
        </div>

        {{-- Peserta Aktif --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-teal-50 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-user-group text-teal-600 text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium">Peserta Aktif</p>
                <p class="text-2xl font-bold text-gray-800">{{ $pesertaAktif ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-0.5">sedang aktif</p>
            </div>
        </div>

        {{-- Tagihan Belum Dibayar --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-file-invoice-dollar text-orange-500 text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium">Belum Dibayar</p>
                <p class="text-2xl font-bold text-gray-800">{{ $tagihanBelumDibayar ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-0.5">tagihan pending</p>
            </div>
        </div>

        {{-- Pemasukan Bulan Ini --}}
        <div class="bg-gradient-to-br from-primary-600 to-primary-700 rounded-2xl shadow-sm p-5 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-money-bill-trend-up text-white text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-white/70 font-medium">Pemasukan Bulan Ini</p>
                <p class="text-lg font-bold text-white leading-tight">
                    Rp {{ number_format($pemasukanBulanIni ?? 0, 0, ',', '.') }}
                </p>
                <p class="text-xs text-white/60 mt-0.5">dari transaksimu</p>
            </div>
        </div>

    </div>

    {{-- ===== ROW 2: Tabel ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- Tagihan Belum Lunas --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-1 h-5 bg-orange-400 rounded-full"></div>
                    <h2 class="text-sm font-bold text-gray-700">Tagihan Belum Lunas</h2>
                </div>
                <a href="{{ route('kasir.transaksi.index') }}"
                   class="text-xs text-primary-600 hover:text-primary-800 font-medium transition flex items-center gap-1">
                    Lihat Semua <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($recentTagihan ?? [] as $tagihan)
                    <div class="px-5 py-3 flex items-center gap-3 hover:bg-gray-50 transition">
                        <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-primary-700 font-bold text-xs">
                                {{ strtoupper(substr($tagihan->peserta->nama ?? 'P', 0, 1)) }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-800 truncate">{{ $tagihan->peserta->nama ?? '-' }}</p>
                            <p class="text-xs text-gray-400">{{ $tagihan->bulan_tahun }}</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-xs font-bold text-orange-500">
                                Rp {{ number_format($tagihan->total_tagihan ?? 0, 0, ',', '.') }}
                            </p>
                            <a href="{{ route('kasir.transaksi.bayar', $tagihan->id) }}"
                               class="text-xs text-white bg-primary-600 hover:bg-primary-700 px-2 py-0.5 rounded-full transition">
                                Bayar
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-10 text-center text-gray-300 text-xs">
                        <i class="fa-solid fa-circle-check text-2xl mb-2 block text-green-200"></i>
                        Semua tagihan sudah lunas!
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Transaksi Terbaru --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-1 h-5 bg-primary-600 rounded-full"></div>
                    <h2 class="text-sm font-bold text-gray-700">Transaksi Terakhirmu</h2>
                </div>
                <a href="{{ route('kasir.riwayat.index') }}"
                   class="text-xs text-primary-600 hover:text-primary-800 font-medium transition flex items-center gap-1">
                    Lihat Semua <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($recentTransaksi ?? [] as $t)
                    <div class="px-5 py-3 flex items-center gap-3 hover:bg-gray-50 transition">
                        <div class="w-8 h-8 rounded-full bg-teal-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-teal-700 font-bold text-xs">
                                {{ strtoupper(substr($t->tagihan->peserta->nama ?? 'P', 0, 1)) }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-800 truncate">
                                {{ $t->tagihan->peserta->nama ?? '-' }}
                            </p>
                            <p class="text-xs text-gray-400">{{ $t->tagihan->bulan_tahun ?? '-' }}</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-xs font-bold text-teal-600">
                                Rp {{ number_format($t->uang_bayar ?? 0, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-gray-300">
                                {{ \Carbon\Carbon::parse($t->created_at)->format('d M, H:i') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-10 text-center text-gray-300 text-xs">
                        <i class="fa-solid fa-receipt text-2xl mb-2 block"></i>
                        Belum ada transaksi
                    </div>
                @endforelse
            </div>
        </div>

    </div>

@endsection