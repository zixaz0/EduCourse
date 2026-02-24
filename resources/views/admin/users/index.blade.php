@extends('Layout.admin')

@section('content')

    {{-- Page Title --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Manajemen User</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola akun kasir dan admin sistem</p>
        </div>
        <a href="{{ url('/admin/user/add') }}"
            class="flex items-center gap-2 bg-primary-700 hover:bg-primary-800 text-white text-sm font-medium px-4 py-2.5 rounded-xl shadow transition">
            <i class="fa-solid fa-plus"></i> Tambah User
        </a>
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
                        <th class="px-5 py-3.5 font-semibold text-center">Aksi</th>
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
                            <td class="px-5 py-4 text-gray-600">{{ $user->email }}</td>

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

                            {{-- Aksi --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ url('/admin/user/' . $user->id . '/edit') }}"
                                        title="Edit"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-yellow-50 hover:bg-yellow-100 text-yellow-600 transition">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    @if($user->status === 'aktif')
                                        <button onclick="confirmToggle({{ $user->id }}, '{{ $user->username }}', 'nonaktif')"
                                            title="Nonaktifkan"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-orange-50 hover:bg-orange-100 text-orange-500 transition">
                                            <i class="fa-solid fa-toggle-on text-xs"></i>
                                        </button>
                                    @else
                                        <button onclick="confirmToggle({{ $user->id }}, '{{ $user->username }}', 'aktif')"
                                            title="Aktifkan"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-green-50 hover:bg-green-100 text-green-600 transition">
                                            <i class="fa-solid fa-toggle-off text-xs"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center text-gray-400">
                                <i class="fa-solid fa-users text-4xl mb-3 block text-gray-200"></i>
                                <p class="font-medium">Belum ada data user</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($users) && method_exists($users, 'hasPages') && $users->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between text-sm text-gray-500">
                <span>Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} user</span>
                <div>{{ $users->links() }}</div>
            </div>
        @endif
    </div>

    <script>
        function filterTable() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const role   = document.getElementById('filterRole').value;
            const status = document.getElementById('filterStatus').value;
            document.querySelectorAll('.user-row').forEach(row => {
                const matchSearch = row.dataset.username.includes(search) || row.dataset.email.includes(search);
                const matchRole   = !role   || row.dataset.role === role;
                const matchStatus = !status || row.dataset.status === status;
                row.style.display = (matchSearch && matchRole && matchStatus) ? '' : 'none';
            });
        }

        function confirmToggle(id, username, newStatus) {
            const isAktif = newStatus === 'aktif';
            Swal.fire({
                title: isAktif ? 'Aktifkan User?' : 'Nonaktifkan User?',
                html: `User <b>${username}</b> akan di${isAktif ? 'aktifkan' : 'nonaktifkan'}.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: isAktif ? '#16a34a' : '#f97316',
                cancelButtonColor: '#6b7280',
                confirmButtonText: isAktif ? 'Ya, Aktifkan' : 'Ya, Nonaktifkan',
                cancelButtonText: 'Batal',
            }).then(result => {
                if (result.isConfirmed) {
                    window.location.href = `/admin/user/${id}/toggle`;
                }
            });
        }

        @if(session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session("success") }}', confirmButtonColor: '#1e5399', timer: 3000, timerProgressBar: true });
        @endif
        @if(session('error'))
            Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session("error") }}', confirmButtonColor: '#1e5399' });
        @endif
    </script>

@endsection