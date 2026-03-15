@extends('Layout.owner')

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Data User</h1>
            <p class="text-sm text-gray-500 mt-0.5">Daftar akun admin & kasir yang terdaftar di sistem</p>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-primary-50 border border-primary-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-users text-primary-600 text-sm"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">Total User</p>
                <p class="text-xl font-bold text-gray-800">{{ count($users ?? []) }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-purple-50 border border-purple-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-user-shield text-purple-600 text-sm"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">Admin</p>
                <p class="text-xl font-bold text-gray-800">{{ collect($users ?? [])->where('role','admin')->count() }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-cash-register text-blue-600 text-sm"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">Kasir</p>
                <p class="text-xl font-bold text-gray-800">{{ collect($users ?? [])->where('role','kasir')->count() }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-green-50 border border-green-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-circle-check text-green-600 text-sm"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">Aktif</p>
                <p class="text-xl font-bold text-gray-800">{{ collect($users ?? [])->where('status','aktif')->count() }}</p>
            </div>
        </div>
    </div>

    {{-- Search & Filter --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 mb-5 flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari username atau email..."
                class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent" />
        </div>
        <select id="filterRole" onchange="filterTable()"
            class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white text-gray-600">
            <option value="">Semua Role</option>
            <option value="admin">Admin</option>
            <option value="kasir">Kasir</option>
        </select>
        <select id="filterStatus" onchange="filterTable()"
            class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white text-gray-600">
            <option value="">Semua Status</option>
            <option value="aktif">Aktif</option>
            <option value="nonaktif">Non-Aktif</option>
        </select>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-primary-700 text-white text-left">
                        <th class="px-5 py-3.5 font-semibold">No</th>
                        <th class="px-5 py-3.5 font-semibold">User</th>
                        <th class="px-5 py-3.5 font-semibold">Email</th>
                        <th class="px-5 py-3.5 font-semibold">Role</th>
                        <th class="px-5 py-3.5 font-semibold">Status</th>
                        <th class="px-5 py-3.5 font-semibold">Dibuat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users ?? [] as $index => $user)
                        <tr class="hover:bg-gray-50 transition user-row"
                            data-username="{{ strtolower($user->username) }}"
                            data-email="{{ strtolower($user->email) }}"
                            data-role="{{ $user->role }}"
                            data-status="{{ $user->status }}">

                            <td class="px-5 py-4 text-gray-400 font-medium text-xs">{{ $index + 1 }}</td>

                            {{-- User --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0
                                        {{ $user->role === 'admin' ? 'bg-purple-100' : 'bg-primary-100' }}">
                                        <span class="font-bold text-sm
                                            {{ $user->role === 'admin' ? 'text-purple-700' : 'text-primary-700' }}">
                                            {{ strtoupper(substr($user->username, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $user->username }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">ID #{{ $user->id }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Email --}}
                            <td class="px-5 py-4 text-gray-600 text-sm">{{ $user->email }}</td>

                            {{-- Role --}}
                            <td class="px-5 py-4">
                                @if($user->role === 'admin')
                                    <span class="inline-flex items-center gap-1.5 bg-purple-50 text-purple-700 text-xs font-semibold px-3 py-1 rounded-full border border-purple-100">
                                        <i class="fa-solid fa-user-shield text-xs"></i> Admin
                                    </span>
                                @elseif($user->role === 'kasir')
                                    <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full border border-blue-100">
                                        <i class="fa-solid fa-cash-register text-xs"></i> Kasir
                                    </span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-4">
                                @if($user->status === 'aktif')
                                    <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 text-xs font-semibold px-3 py-1 rounded-full border border-green-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 text-xs font-semibold px-3 py-1 rounded-full border border-red-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span> Non-Aktif
                                    </span>
                                @endif
                            </td>

                            {{-- Dibuat --}}
                            <td class="px-5 py-4 text-gray-400 text-xs">
                                {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center text-gray-400">
                                <i class="fa-solid fa-users text-4xl mb-3 block text-gray-200"></i>
                                <p class="font-medium">Belum ada data user</p>
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
            const role   = document.getElementById('filterRole').value;
            const status = document.getElementById('filterStatus').value;
            document.querySelectorAll('.user-row').forEach(row => {
                const ok = (row.dataset.username.includes(search) || row.dataset.email.includes(search))
                        && (!role   || row.dataset.role === role)
                        && (!status || row.dataset.status === status);
                row.style.display = ok ? '' : 'none';
            });
        }
    </script>

@endsection