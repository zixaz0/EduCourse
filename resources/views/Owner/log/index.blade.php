@extends('Layout.owner')

@section('content')
    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-800">Riwayat Aktivitas User</h1>
        <p class="text-sm text-gray-500 mt-0.5">Log aktivitas semua user — owner, admin & kasir</p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-primary-50 border border-primary-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-clipboard-list text-primary-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium">Total Log</p>
                <p class="text-2xl font-bold text-gray-800">{{ count($logs ?? []) }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-green-50 border border-green-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-calendar-day text-green-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium">Aktivitas Hari Ini</p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ collect($logs ?? [])->filter(fn($l) => \Carbon\Carbon::parse($l->created_at)->isToday())->count() }}
                </p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-purple-50 border border-purple-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-user-group text-purple-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium">User Aktif Hari Ini</p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ collect($logs ?? [])->filter(fn($l) => \Carbon\Carbon::parse($l->created_at)->isToday())->pluck('user_id')->unique()->count() }}
                </p>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 mb-5 flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari aktivitas atau username..."
                class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent" />
        </div>
        {{-- Filter User --}}
        <select id="filterUser" onchange="filterTable()"
            class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white text-gray-600">
            <option value="">Semua User</option>
            @foreach($userList ?? [] as $u)
                <option value="{{ strtolower($u->username) }}">{{ $u->username }} ({{ $u->role }})</option>
            @endforeach
        </select>
        {{-- Filter Role --}}
        <select id="filterRole" onchange="filterTable()"
            class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white text-gray-600">
            <option value="">Semua Role</option>
            <option value="owner">Owner</option>
            <option value="admin">Admin</option>
            <option value="kasir">Kasir</option>
        </select>
        {{-- Filter Tanggal --}}
        <input type="date" id="filterWaktu" onchange="filterTable()"
            class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white text-gray-600" />
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-primary-700 text-white text-left">
                        <th class="px-5 py-3.5 font-semibold">No</th>
                        <th class="px-5 py-3.5 font-semibold">User</th>
                        <th class="px-5 py-3.5 font-semibold">Role</th>
                        <th class="px-5 py-3.5 font-semibold">Aktivitas</th>
                        <th class="px-5 py-3.5 font-semibold">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($logs ?? [] as $index => $log)
                        @php
                            $act = strtolower($log->aktivitas);
                            if (str_contains($act, 'login')) {
                                $icon = 'fa-right-to-bracket';
                                $color = 'bg-green-100 text-green-600';
                                $badge = 'bg-green-50 text-green-700 border-green-100';
                                $label = 'Login';
                            } elseif (str_contains($act, 'logout')) {
                                $icon = 'fa-right-from-bracket';
                                $color = 'bg-gray-100 text-gray-500';
                                $badge = 'bg-gray-50 text-gray-500 border-gray-200';
                                $label = 'Logout';
                            } elseif (str_contains($act, 'bayar') || str_contains($act, 'transaksi')) {
                                $icon = 'fa-money-bill';
                                $color = 'bg-blue-100 text-blue-600';
                                $badge = 'bg-blue-50 text-blue-700 border-blue-100';
                                $label = 'Transaksi';
                            } elseif (str_contains($act, 'tambah') || str_contains($act, 'baru')) {
                                $icon = 'fa-plus';
                                $color = 'bg-primary-100 text-primary-600';
                                $badge = 'bg-primary-50 text-primary-700 border-primary-100';
                                $label = 'Tambah';
                            } elseif (str_contains($act, 'edit') || str_contains($act, 'ubah')) {
                                $icon = 'fa-pen';
                                $color = 'bg-yellow-100 text-yellow-600';
                                $badge = 'bg-yellow-50 text-yellow-700 border-yellow-100';
                                $label = 'Edit';
                            } elseif (str_contains($act, 'hapus')) {
                                $icon = 'fa-trash';
                                $color = 'bg-red-100 text-red-500';
                                $badge = 'bg-red-50 text-red-600 border-red-100';
                                $label = 'Hapus';
                            } elseif (str_contains($act, 'nonaktif') || str_contains($act, 'aktif')) {
                                $icon = 'fa-toggle-on';
                                $color = 'bg-orange-100 text-orange-500';
                                $badge = 'bg-orange-50 text-orange-600 border-orange-100';
                                $label = 'Toggle';
                            } else {
                                $icon = 'fa-circle-info';
                                $color = 'bg-gray-100 text-gray-500';
                                $badge = 'bg-gray-50 text-gray-500 border-gray-200';
                                $label = 'Lainnya';
                            }
                        @endphp
                        {{-- data-aktivitas (bukan aktifitas) -- harus konsisten dengan JS --}}
                        <tr class="hover:bg-gray-50 transition log-row"
                            data-aktivitas="{{ strtolower($log->aktivitas) }}"
                            data-user="{{ strtolower($log->user->username ?? '') }}"
                            data-role="{{ strtolower($log->user->role ?? '') }}"
                            data-tanggal="{{ \Carbon\Carbon::parse($log->created_at)->toDateString() }}">

                            <td class="px-5 py-3.5 text-gray-400 font-medium text-xs">{{ $index + 1 }}</td>

                            {{-- User --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full {{ ($log->user->role ?? '') === 'admin' ? 'bg-purple-100' : (($log->user->role ?? '') === 'owner' ? 'bg-yellow-100' : 'bg-primary-100') }} flex items-center justify-center flex-shrink-0">
                                        <span class="font-bold text-xs {{ ($log->user->role ?? '') === 'admin' ? 'text-purple-700' : (($log->user->role ?? '') === 'owner' ? 'text-yellow-700' : 'text-primary-700') }}">
                                            {{ strtoupper(substr($log->user->username ?? 'U', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800 text-xs">{{ $log->user->username ?? '-' }}</p>
                                        <p class="text-xs text-gray-400">{{ $log->user->email ?? '' }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Role --}}
                            <td class="px-5 py-3.5">
                                @if(($log->user->role ?? '') === 'owner')
                                    <span class="inline-flex items-center gap-1 bg-yellow-50 text-yellow-700 text-xs font-semibold px-2.5 py-1 rounded-full border border-yellow-100">
                                        <i class="fa-solid fa-crown text-xs"></i> Owner
                                    </span>
                                @elseif(($log->user->role ?? '') === 'admin')
                                    <span class="inline-flex items-center gap-1 bg-purple-50 text-purple-700 text-xs font-semibold px-2.5 py-1 rounded-full border border-purple-100">
                                        <i class="fa-solid fa-user-shield text-xs"></i> Admin
                                    </span>
                                @elseif(($log->user->role ?? '') === 'kasir')
                                    <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-full border border-blue-100">
                                        <i class="fa-solid fa-cash-register text-xs"></i> Kasir
                                    </span>
                                @endif
                            </td>

                            {{-- Aktivitas --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-lg {{ $color }} flex items-center justify-center flex-shrink-0">
                                        <i class="fa-solid {{ $icon }}" style="font-size:10px;"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-700 font-medium">{{ $log->aktivitas }}</p>
                                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full border {{ $badge }}">{{ $label }}</span>
                                    </div>
                                </div>
                            </td>

                            {{-- Waktu --}}
                            <td class="px-5 py-3.5">
                                <p class="text-xs text-gray-700 font-medium">{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y') }}</p>
                                <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s') }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-16 text-center text-gray-400">
                                <i class="fa-solid fa-clipboard-list text-4xl mb-3 block text-gray-200"></i>
                                <p class="font-medium">Belum ada log aktivitas</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function filterTable() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const user   = document.getElementById('filterUser').value.toLowerCase();
            const role   = document.getElementById('filterRole').value.toLowerCase();
            const waktu  = document.getElementById('filterWaktu').value; // format: "2026-03-29" atau ""

            document.querySelectorAll('.log-row').forEach(row => {
                // Pakai dataset.aktivitas (bukan aktifitas) — sesuai data-aktivitas di HTML
                const ok = (row.dataset.aktivitas.includes(search) || row.dataset.user.includes(search))
                        && (!user  || row.dataset.user.includes(user))
                        && (!role  || row.dataset.role === role)
                        && (!waktu || row.dataset.tanggal === waktu);
                row.style.display = ok ? '' : 'none';
            });
        }
    </script>

@endsection