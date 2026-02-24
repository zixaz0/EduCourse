@extends('Layout.kasir')

@section('content')

    {{-- Page Title --}}
    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-800">Log Aktivitas Saya</h1>
        <p class="text-sm text-gray-500 mt-0.5">
            Riwayat aktivitas akun
            <span class="font-semibold text-primary-700">{{ Auth::user()->name ?? Auth::user()->username ?? 'Kasir' }}</span>
        </p>
    </div>

    {{-- Search & Filter --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 mb-5 flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari aktivitas..."
                class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent" />
        </div>
        <select id="filterTanggal" onchange="filterTable()"
            class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white text-gray-600">
            <option value="">Semua Tanggal</option>
            <option value="hari ini">Hari Ini</option>
            <option value="kemarin">Kemarin</option>
            <option value="minggu ini">Minggu Ini</option>
        </select>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center">
                <i class="fa-solid fa-list-check text-primary-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium">Total Aktivitas Saya</p>
                <p class="text-2xl font-bold text-primary-700">{{ count($logs ?? []) }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                <i class="fa-solid fa-calendar-day text-green-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium">Aktivitas Hari Ini</p>
                <p class="text-2xl font-bold text-green-600">
                    {{ collect($logs ?? [])->filter(fn($l) => \Carbon\Carbon::parse($l->created_at)->isToday())->count() }}
                </p>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-primary-700 text-white text-left">
                        <th class="px-5 py-3.5 font-semibold">No</th>
                        <th class="px-5 py-3.5 font-semibold">Aktivitas</th>
                        <th class="px-5 py-3.5 font-semibold">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($logs ?? [] as $index => $log)
                        @php
                            $aktifitas = strtolower($log->aktifitas ?? '');
                            $isLogin   = str_contains($aktifitas, 'login');
                            $isLogout  = str_contains($aktifitas, 'logout');
                            $isBayar   = str_contains($aktifitas, 'bayar') || str_contains($aktifitas, 'transaksi');
                            $isTambah  = str_contains($aktifitas, 'tambah') || str_contains($aktifitas, 'buat') || str_contains($aktifitas, 'create');
                            $isEdit    = str_contains($aktifitas, 'edit') || str_contains($aktifitas, 'update') || str_contains($aktifitas, 'ubah');
                            $isHapus   = str_contains($aktifitas, 'hapus') || str_contains($aktifitas, 'delete');
                        @endphp
                        <tr class="hover:bg-gray-50 transition log-row"
                            data-aktifitas="{{ strtolower($log->aktifitas ?? '') }}"
                            data-tanggal="{{ \Carbon\Carbon::parse($log->created_at)->format('Y-m-d') }}">

                            <td class="px-5 py-4 text-gray-400 font-medium text-xs">{{ $index + 1 }}</td>

                            {{-- Aktivitas --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    @if($isLogin)
                                        <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center flex-shrink-0">
                                            <i class="fa-solid fa-right-to-bracket text-green-600 text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $log->aktifitas }}</p>
                                            <span class="text-xs text-green-600 font-semibold bg-green-50 px-2 py-0.5 rounded-full border border-green-100">Login</span>
                                        </div>
                                    @elseif($isLogout)
                                        <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                            <i class="fa-solid fa-right-from-bracket text-gray-500 text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $log->aktifitas }}</p>
                                            <span class="text-xs text-gray-500 font-semibold bg-gray-100 px-2 py-0.5 rounded-full border border-gray-200">Logout</span>
                                        </div>
                                    @elseif($isBayar)
                                        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                            <i class="fa-solid fa-money-bill-wave text-blue-600 text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $log->aktifitas }}</p>
                                            <span class="text-xs text-blue-600 font-semibold bg-blue-50 px-2 py-0.5 rounded-full border border-blue-100">Pembayaran</span>
                                        </div>
                                    @elseif($isTambah)
                                        <div class="w-8 h-8 rounded-lg bg-primary-50 flex items-center justify-center flex-shrink-0">
                                            <i class="fa-solid fa-plus text-primary-600 text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $log->aktifitas }}</p>
                                            <span class="text-xs text-primary-600 font-semibold bg-primary-50 px-2 py-0.5 rounded-full border border-primary-100">Tambah Data</span>
                                        </div>
                                    @elseif($isEdit)
                                        <div class="w-8 h-8 rounded-lg bg-yellow-50 flex items-center justify-center flex-shrink-0">
                                            <i class="fa-solid fa-pen text-yellow-600 text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $log->aktifitas }}</p>
                                            <span class="text-xs text-yellow-600 font-semibold bg-yellow-50 px-2 py-0.5 rounded-full border border-yellow-100">Edit Data</span>
                                        </div>
                                    @elseif($isHapus)
                                        <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center flex-shrink-0">
                                            <i class="fa-solid fa-trash text-red-500 text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $log->aktifitas }}</p>
                                            <span class="text-xs text-red-500 font-semibold bg-red-50 px-2 py-0.5 rounded-full border border-red-100">Hapus Data</span>
                                        </div>
                                    @else
                                        <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center flex-shrink-0">
                                            <i class="fa-solid fa-circle-info text-gray-400 text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $log->aktifitas }}</p>
                                            <span class="text-xs text-gray-400 font-semibold bg-gray-50 px-2 py-0.5 rounded-full border border-gray-200">Aktivitas</span>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            {{-- Waktu --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <p class="text-gray-700 text-sm font-medium">
                                    {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y') }}
                                </p>
                                <p class="text-gray-400 text-xs mt-0.5">
                                    {{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s') }}
                                </p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-5 py-16 text-center text-gray-400">
                                <i class="fa-solid fa-clipboard-list text-4xl mb-3 block text-gray-200"></i>
                                <p class="font-medium">Belum ada log aktivitas</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if(isset($logs) && method_exists($logs, 'hasPages') && $logs->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between text-sm text-gray-500">
                <span>Menampilkan {{ $logs->firstItem() }}–{{ $logs->lastItem() }} dari {{ $logs->total() }} aktivitas</span>
                <div>{{ $logs->links() }}</div>
            </div>
        @endif
    </div>

    <script>
        const today     = new Date().toISOString().split('T')[0];
        const yesterday = new Date(Date.now() - 86400000).toISOString().split('T')[0];
        const weekStart = new Date(Date.now() - 7 * 86400000).toISOString().split('T')[0];

        function filterTable() {
            const search  = document.getElementById('searchInput').value.toLowerCase();
            const tanggal = document.getElementById('filterTanggal').value;

            document.querySelectorAll('.log-row').forEach(row => {
                const matchSearch = row.dataset.aktifitas.includes(search);
                let matchTanggal  = true;
                if (tanggal === 'hari ini')   matchTanggal = row.dataset.tanggal === today;
                if (tanggal === 'kemarin')    matchTanggal = row.dataset.tanggal === yesterday;
                if (tanggal === 'minggu ini') matchTanggal = row.dataset.tanggal >= weekStart;
                row.style.display = (matchSearch && matchTanggal) ? '' : 'none';
            });
        }
    </script>

@endsection