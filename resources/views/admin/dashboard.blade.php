@extends('Layout.admin')

@section('content')

    {{-- Greeting --}}
    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-800">
            Selamat datang, {{ Auth::user()->nama ?? Auth::user()->username ?? 'Admin' }} 👋
        </h1>
        <p class="text-sm text-gray-500 mt-0.5">Berikut ringkasan data sistem EduCourse hari ini</p>
    </div>

    {{-- ===== STAT CARDS ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

        {{-- Total Peserta --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-users text-blue-600 text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium">Total Peserta</p>
                <p class="text-2xl font-bold text-blue-600">{{ $totalPeserta ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-0.5">peserta terdaftar</p>
            </div>
        </div>

        {{-- Total Kelas --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-chalkboard-user text-purple-600 text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium">Total Kelas</p>
                <p class="text-2xl font-bold text-purple-600">{{ $totalKelas ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-0.5">kelas tersedia</p>
            </div>
        </div>

        {{-- Total Transaksi --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-sky-50 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-receipt text-sky-600 text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium">Total Transaksi</p>
                <p class="text-2xl font-bold text-sky-600">{{ $totalTransaksi ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-0.5">semua waktu</p>
            </div>
        </div>

    </div>

    {{-- ===== ROW 3: Tabel Recent + Info Kelas ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5">

        {{-- Transaksi Terbaru --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-1 h-5 bg-primary-700 rounded-full"></div>
                    <h2 class="text-sm font-bold text-gray-700">Transaksi Terbaru</h2>
                </div>
                <a href="{{ url('/admin/transaksi') }}" class="text-xs text-primary-600 hover:text-primary-800 font-medium transition">
                    Lihat Semua <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-left text-xs text-gray-500 font-semibold">
                            <th class="px-5 py-3">Peserta</th>
                            <th class="px-5 py-3">Kursus</th>
                            <th class="px-5 py-3">Jumlah</th>
                            <th class="px-5 py-3">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($recentTransaksi ?? [] as $t)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                                            <span class="text-primary-700 font-bold text-xs">
                                                {{ strtoupper(substr( $t->tagihan->peserta->nama ?? 'P', 0, 1)) }}
                                            </span>
                                        </div>
                                        <span class="font-medium text-gray-800 text-xs">{{  $t->tagihan->peserta->nama ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-xs text-gray-600">
                                    {{ $t->tagihan->peserta->kelas->pluck('nama_kelas')->implode(', ') ?? '-' }}
                                </td>
                                <td class="px-5 py-3 text-xs font-semibold text-green-700">
                                    Rp {{ number_format($t->uang_bayar ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3 text-xs text-gray-400">
                                    {{ \Carbon\Carbon::parse($t->created_at)->format('d M, H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-gray-300 text-xs">
                                    <i class="fa-solid fa-receipt text-2xl mb-2 block"></i>Belum ada transaksi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Kelas Terpopuler --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <div class="w-1 h-5 bg-primary-700 rounded-full"></div>
                <h2 class="text-sm font-bold text-gray-700">Kelas Terpopuler</h2>
            </div>
            <div class="p-5 space-y-4">
                @forelse($kelasTerpopuler ?? [] as $kelas)
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1.5">
                            <span class="font-semibold text-gray-700">{{ $kelas->nama_kelas }}</span>
                            <span class="text-gray-400 font-medium">{{ $kelas->jumlah_peserta }} peserta</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-primary-600 h-2 rounded-full transition-all duration-500"
                                style="width: {{ $kelas->persentase ?? 0 }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-300 text-center py-6">Belum ada data kelas</p>
                @endforelse
            </div>
        </div>

    </div>

    {{-- ===== ROW 4: Log Terbaru + Tagihan Belum Lunas ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- Log Aktivitas Terbaru --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-1 h-5 bg-primary-700 rounded-full"></div>
                    <h2 class="text-sm font-bold text-gray-700">Log Aktivitas Terbaru</h2>
                </div>
                <a href="{{ url('/admin/log') }}" class="text-xs text-primary-600 hover:text-primary-800 font-medium transition">
                    Lihat Semua <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
                </a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($recentLog ?? [] as $log)
                    @php
                        $akt = strtolower($log->aktivitas ?? '');
                        $icon = 'fa-circle-info'; $color = 'text-gray-400'; $bg = 'bg-gray-50';
                        if (str_contains($akt, 'login'))   { $icon = 'fa-right-to-bracket'; $color = 'text-green-600'; $bg = 'bg-green-50'; }
                        if (str_contains($akt, 'logout'))  { $icon = 'fa-right-from-bracket'; $color = 'text-gray-500'; $bg = 'bg-gray-100'; }
                        if (str_contains($akt, 'bayar'))   { $icon = 'fa-money-bill-wave'; $color = 'text-blue-600'; $bg = 'bg-blue-50'; }
                        if (str_contains($akt, 'tambah'))  { $icon = 'fa-plus'; $color = 'text-primary-600'; $bg = 'bg-primary-50'; }
                        if (str_contains($akt, 'edit'))    { $icon = 'fa-pen'; $color = 'text-yellow-600'; $bg = 'bg-yellow-50'; }
                        if (str_contains($akt, 'hapus'))   { $icon = 'fa-trash'; $color = 'text-red-500'; $bg = 'bg-red-50'; }
                    @endphp
                    <div class="px-5 py-3 flex items-center gap-3 hover:bg-gray-50 transition">
                        <div class="w-7 h-7 rounded-lg {{ $bg }} flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid {{ $icon }} {{ $color }} text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-gray-700 truncate">{{ $log->aktivitas }}</p>
                            <p class="text-xs text-gray-400">{{ $log->user->username ?? '-' }}</p>
                        </div>
                        <span class="text-xs text-gray-300 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                        </span>
                    </div>
                @empty
                    <div class="px-5 py-10 text-center text-gray-300 text-xs">
                        <i class="fa-solid fa-clipboard-list text-2xl mb-2 block"></i>Belum ada log
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Tagihan Belum Lunas --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-1 h-5 bg-red-400 rounded-full"></div>
                    <h2 class="text-sm font-bold text-gray-700">Tagihan Belum Lunas</h2>
                </div>
                <a href="{{ url('/admin/tagihan') }}" class="text-xs text-primary-600 hover:text-primary-800 font-medium transition">
                    Lihat Semua <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
                </a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($recentTagihanBelumLunas ?? [] as $t)
                    <div class="px-5 py-3 flex items-center gap-3 hover:bg-gray-50 transition">
                        <div class="w-7 h-7 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-primary-700 font-bold text-xs">
                                {{ strtoupper(substr($t->peserta->nama ?? 'P', 0, 1)) }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-800 truncate">{{ $t->peserta->nama ?? '-' }}</p>
                            <p class="text-xs text-gray-400">{{ $t->bulan_tahun }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold text-red-500">Rp {{ number_format($t->total_tagihan ?? 0, 0, ',', '.') }}</p>
                            <span class="text-xs text-red-400 bg-red-50 px-2 py-0.5 rounded-full border border-red-100">Belum Lunas</span>
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

    </div>

@endsection