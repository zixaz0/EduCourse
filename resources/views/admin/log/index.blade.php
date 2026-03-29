@extends('Layout.admin')

@section('content')

    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-800">Log Aktivitas</h1>
        <p class="text-sm text-gray-500 mt-0.5">Riwayat semua aktivitas sistem</p>
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
                <p class="text-xs text-gray-400 font-medium">Total Aktivitas</p>
                <p class="text-2xl font-bold text-primary-700">{{ $totalAktivitas }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                <i class="fa-solid fa-calendar-day text-green-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium">Aktivitas Hari Ini</p>
                <p class="text-2xl font-bold text-green-600">{{ $aktivitasHariIni }}</p>
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
                        <th class="px-5 py-3.5 font-semibold">User</th>
                        <th class="px-5 py-3.5 font-semibold">Aktivitas</th>
                        <th class="px-5 py-3.5 font-semibold">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($logs as $index => $log)
                        @php
                            $akt      = strtolower($log->aktivitas ?? '');
                            $isLogin  = str_contains($akt, 'login');
                            $isLogout = str_contains($akt, 'logout');
                            $isBayar  = str_contains($akt, 'bayar') || str_contains($akt, 'transaksi');
                            $isTambah = str_contains($akt, 'tambah') || str_contains($akt, 'buat') || str_contains($akt, 'create');
                            $isEdit   = str_contains($akt, 'edit') || str_contains($akt, 'update') || str_contains($akt, 'ubah');
                            $isHapus  = str_contains($akt, 'hapus') || str_contains($akt, 'delete');

                            if ($isLogin)       { $icon = 'fa-right-to-bracket';  $color = 'green';   $label = 'Login'; }
                            elseif ($isLogout)  { $icon = 'fa-right-from-bracket'; $color = 'gray';   $label = 'Logout'; }
                            elseif ($isBayar)   { $icon = 'fa-money-bill-wave';   $color = 'blue';    $label = 'Pembayaran'; }
                            elseif ($isTambah)  { $icon = 'fa-plus';              $color = 'primary'; $label = 'Tambah Data'; }
                            elseif ($isEdit)    { $icon = 'fa-pen';               $color = 'yellow';  $label = 'Edit Data'; }
                            elseif ($isHapus)   { $icon = 'fa-trash';             $color = 'red';     $label = 'Hapus Data'; }
                            else                { $icon = 'fa-circle-info';       $color = 'gray';    $label = 'Aktivitas'; }

                            $colorMap = [
                                'green'   => ['bg' => 'bg-green-50',   'text' => 'text-green-600',   'border' => 'border-green-100'],
                                'gray'    => ['bg' => 'bg-gray-100',   'text' => 'text-gray-500',    'border' => 'border-gray-200'],
                                'blue'    => ['bg' => 'bg-blue-50',    'text' => 'text-blue-600',    'border' => 'border-blue-100'],
                                'primary' => ['bg' => 'bg-primary-50', 'text' => 'text-primary-600', 'border' => 'border-primary-100'],
                                'yellow'  => ['bg' => 'bg-yellow-50',  'text' => 'text-yellow-600',  'border' => 'border-yellow-100'],
                                'red'     => ['bg' => 'bg-red-50',     'text' => 'text-red-500',     'border' => 'border-red-100'],
                            ];
                            $c = $colorMap[$color];
                        @endphp
                        <tr class="hover:bg-gray-50 transition log-row"
                            data-aktivitas="{{ strtolower($log->aktivitas ?? '') }}"
                            data-tanggal="{{ \Carbon\Carbon::parse($log->created_at)->format('Y-m-d') }}">

                            <td class="px-5 py-4 text-gray-400 font-medium text-xs">{{ $logs->firstItem() + $index }}</td>

                            {{-- User --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                                        <span class="text-primary-700 font-bold text-xs">
                                            {{ strtoupper(substr($log->user->username ?? 'U', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-700">{{ $log->user->username ?? '-' }}</p>
                                        <p class="text-xs text-gray-400">{{ $log->user->role ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Aktivitas --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg {{ $c['bg'] }} flex items-center justify-center flex-shrink-0">
                                        <i class="fa-solid {{ $icon }} {{ $c['text'] }} text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $log->aktivitas }}</p>
                                        <span class="text-xs {{ $c['text'] }} font-semibold {{ $c['bg'] }} px-2 py-0.5 rounded-full border {{ $c['border'] }}">
                                            {{ $label }}
                                        </span>
                                    </div>
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
                            <td colspan="4" class="px-5 py-16 text-center text-gray-400">
                                <i class="fa-solid fa-clipboard-list text-4xl mb-3 block text-gray-200"></i>
                                <p class="font-medium">Belum ada log aktivitas</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ===== PAGINATION ===== --}}
        @if($logs->total() > 0)
        <div class="px-5 py-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-sm text-gray-500">
            <div class="flex items-center gap-2">
                <span>Menampilkan {{ $logs->firstItem() }}–{{ $logs->lastItem() }} dari {{ $logs->total() }} aktivitas. Tampilkan</span>
                <select onchange="changePerPage(this.value)"
                    class="border border-gray-200 rounded-lg px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white">
                    @foreach([5, 10, 25, 50] as $opt)
                        <option value="{{ $opt }}" {{ $perPage == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
                <span>data</span>
            </div>
            <div class="flex items-center gap-1">
                @if($logs->onFirstPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 cursor-not-allowed text-xs">«</span>
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 cursor-not-allowed text-xs">‹</span>
                @else
                    <a href="{{ $logs->url(1) }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500 transition text-xs">«</a>
                    <a href="{{ $logs->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500 transition text-xs">‹</a>
                @endif

                @php $current = $logs->currentPage(); $last = $logs->lastPage(); $start = max(1, $current-1); $end = min($last, $current+1); @endphp

                @if($start > 1)
                    <a href="{{ $logs->url(1) }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-600 transition text-xs">1</a>
                    @if($start > 2)<span class="w-8 h-8 flex items-center justify-center text-gray-400 text-xs">…</span>@endif
                @endif

                @for($page = $start; $page <= $end; $page++)
                    @if($page == $current)
                        <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-primary-700 text-white font-semibold text-xs">{{ $page }}</span>
                    @else
                        <a href="{{ $logs->url($page) }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-600 transition text-xs">{{ $page }}</a>
                    @endif
                @endfor

                @if($end < $last)
                    @if($end < $last - 1)<span class="w-8 h-8 flex items-center justify-center text-gray-400 text-xs">…</span>@endif
                    <a href="{{ $logs->url($last) }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-600 transition text-xs">{{ $last }}</a>
                @endif

                @if($logs->hasMorePages())
                    <a href="{{ $logs->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500 transition text-xs">›</a>
                    <a href="{{ $logs->url($logs->lastPage()) }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500 transition text-xs">»</a>
                @else
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 cursor-not-allowed text-xs">›</span>
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 cursor-not-allowed text-xs">»</span>
                @endif
            </div>
        </div>
        @endif
    </div>

    <script>
        const today     = new Date().toISOString().split('T')[0];
        const yesterday = new Date(Date.now() - 86400000).toISOString().split('T')[0];
        const weekStart = new Date(Date.now() - 7 * 86400000).toISOString().split('T')[0];

        function changePerPage(val) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', val);
            url.searchParams.set('page', 1);
            window.location.href = url.toString();
        }

        function filterTable() {
            const search  = document.getElementById('searchInput').value.toLowerCase();
            const tanggal = document.getElementById('filterTanggal').value;
            document.querySelectorAll('.log-row').forEach(row => {
                const matchSearch  = row.dataset.aktivitas.includes(search);
                let   matchTanggal = true;
                if (tanggal === 'hari ini')   matchTanggal = row.dataset.tanggal === today;
                if (tanggal === 'kemarin')    matchTanggal = row.dataset.tanggal === yesterday;
                if (tanggal === 'minggu ini') matchTanggal = row.dataset.tanggal >= weekStart;
                row.style.display = (matchSearch && matchTanggal) ? '' : 'none';
            });
        }
    </script>

@endsection