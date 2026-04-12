@extends('Layout.owner')

@section('content')

    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-800">Dashboard Owner</h1>
        <p class="text-sm text-gray-500 mt-0.5">Ringkasan data & performa EduCourse</p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-primary-50 border border-primary-100 flex items-center justify-center">
                    <i class="fa-solid fa-chalkboard-user text-primary-600"></i>
                </div>
                <span class="text-xs text-gray-400">Kelas</span>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['totalKelas'] }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Total kelas aktif</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-green-50 border border-green-100 flex items-center justify-center">
                    <i class="fa-solid fa-users text-green-600"></i>
                </div>
                <span class="text-xs text-gray-400">Peserta</span>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['totalPeserta'] }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Total peserta terdaftar</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-yellow-50 border border-yellow-100 flex items-center justify-center">
                    <i class="fa-solid fa-money-bill-wave text-yellow-600"></i>
                </div>
                <span class="text-xs text-gray-400">Pemasukan</span>
            </div>
            <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($stats['pemasukanBulanIni'], 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Bulan ini</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center">
                    <i class="fa-solid fa-clock-rotate-left text-blue-600"></i>
                </div>
                <span class="text-xs text-gray-400">Transaksi</span>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['totalTransaksi'] }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Transaksi bulan ini</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Kelas Terpopuler --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-1 h-5 bg-primary-700 rounded-full"></div>
                    <h3 class="text-sm font-bold text-gray-700">Kelas Terpopuler</h3>
                </div>
                <a href="{{ url('/owner/kelas') }}" class="text-xs text-primary-600 hover:text-primary-800 font-medium">Lihat Semua →</a>
            </div>
            <div class="p-5 space-y-4">
                @foreach($kelasTerpopuler ?? [] as $k)
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-sm font-semibold text-gray-700">{{ $k->nama_kelas }}</span>
                            <span class="text-xs text-gray-500 font-medium">{{ $k->jumlah_peserta }} peserta</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-primary-600 h-2 rounded-full transition-all" style="width: {{ $k->persentase }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Log Aktivitas Terbaru --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-1 h-5 bg-primary-700 rounded-full"></div>
                    <h3 class="text-sm font-bold text-gray-700">Aktivitas Terbaru</h3>
                </div>
                <a href="{{ url('/owner/log') }}" class="text-xs text-primary-600 hover:text-primary-800 font-medium">Lihat Semua →</a>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($recentLog ?? [] as $log)
                    @php
                        $act = strtolower($log->aktivitas);
                        if (str_contains($act, 'login'))       { $icon = 'fa-right-to-bracket'; $color = 'bg-green-100 text-green-600'; }
                        elseif (str_contains($act, 'logout'))  { $icon = 'fa-right-from-bracket'; $color = 'bg-gray-100 text-gray-500'; }
                        elseif (str_contains($act, 'bayar') || str_contains($act, 'transaksi')) { $icon = 'fa-money-bill'; $color = 'bg-blue-100 text-blue-600'; }
                        elseif (str_contains($act, 'tambah') || str_contains($act, 'baru'))     { $icon = 'fa-plus'; $color = 'bg-primary-100 text-primary-600'; }
                        elseif (str_contains($act, 'edit') || str_contains($act, 'ubah'))       { $icon = 'fa-pen'; $color = 'bg-yellow-100 text-yellow-600'; }
                        elseif (str_contains($act, 'hapus'))   { $icon = 'fa-trash'; $color = 'bg-red-100 text-red-500'; }
                        else { $icon = 'fa-circle-info'; $color = 'bg-gray-100 text-gray-500'; }
                    @endphp
                    <div class="px-5 py-3 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg {{ $color }} flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid {{ $icon }} text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-700 truncate">{{ $log->aktivitas }}</p>
                            <p class="text-xs text-gray-400">{{ $log->user->username ?? '-' }}</p>
                        </div>
                        <span class="text-xs text-gray-400 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Transaksi Terbaru --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden lg:col-span-2">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-1 h-5 bg-primary-700 rounded-full"></div>
                    <h3 class="text-sm font-bold text-gray-700">Transaksi Terbaru</h3>
                </div>
                <a href="{{ url('/owner/laporan') }}" class="text-xs text-primary-600 hover:text-primary-800 font-medium">Lihat Semua →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-left">
                            <th class="px-5 py-3 text-xs font-semibold text-gray-500">Peserta</th>
                            <th class="px-5 py-3 text-xs font-semibold text-gray-500">Kursus</th>
                            <th class="px-5 py-3 text-xs font-semibold text-gray-500">Jumlah</th>
                            <th class="px-5 py-3 text-xs font-semibold text-gray-500">Kasir</th>
                            <th class="px-5 py-3 text-xs font-semibold text-gray-500">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($recentTransaksi ?? [] as $t)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                                            <span class="text-primary-700 text-xs font-bold">{{ strtoupper(substr($t->peserta, 0, 1)) }}</span>
                                        </div>
                                        <span class="font-semibold text-gray-800 text-xs">{{ $t->peserta }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="bg-primary-50 text-primary-700 text-xs font-medium px-2.5 py-1 rounded-full border border-primary-100">{{ $t->kursus }}</span>
                                </td>
                                <td class="px-5 py-3 font-semibold text-gray-800 text-xs">Rp {{ number_format($t->jumlah, 0, ',', '.') }}</td>
                                <td class="px-5 py-3 text-xs text-gray-500">{{ $t->kasir }}</td>
                                <td class="px-5 py-3 text-xs text-gray-400">{{ \Carbon\Carbon::parse($t->waktu)->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

@endsection