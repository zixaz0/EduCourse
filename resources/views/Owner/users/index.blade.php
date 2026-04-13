@extends('Layout.owner')

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Data User</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola semua akun admin & kasir di sistem</p>
        </div>
        <a href="{{ route('owner.users.add') }}"
            class="flex items-center gap-2 bg-primary-700 hover:bg-primary-800 text-white text-sm font-medium px-4 py-2.5 rounded-xl shadow transition">
            <i class="fa-solid fa-plus"></i> Tambah User
        </a>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-primary-50 border border-primary-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-users text-primary-600 text-sm"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">Total User</p>
                <p class="text-xl font-bold text-gray-800">{{ $users->total() }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-purple-50 border border-purple-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-user-shield text-purple-600 text-sm"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">Admin</p>
                <p class="text-xl font-bold text-gray-800">{{ $users->getCollection()->where('role','admin')->count() }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-cash-register text-blue-600 text-sm"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">Kasir</p>
                <p class="text-xl font-bold text-gray-800">{{ $users->getCollection()->where('role','kasir')->count() }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-green-50 border border-green-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-circle-check text-green-600 text-sm"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">Aktif</p>
                <p class="text-xl font-bold text-gray-800">{{ $users->getCollection()->where('status','aktif')->count() }}</p>
            </div>
        </div>
    </div>
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
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-primary-700 text-white text-left">
                        <th class="px-5 py-3.5 font-semibold">No</th>
                        <th class="px-5 py-3.5 font-semibold">Username</th>
                        <th class="px-5 py-3.5 font-semibold">Nama</th>
                        <th class="px-5 py-3.5 font-semibold">Email</th>
                        <th class="px-5 py-3.5 font-semibold">Role</th>
                        <th class="px-5 py-3.5 font-semibold">Status</th>
                        <th class="px-5 py-3.5 font-semibold">Dibuat</th>
                        <th class="px-5 py-3.5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $index => $user)
                        <tr class="hover:bg-gray-50 transition user-row"
                            data-username="{{ strtolower($user->username) }}"
                            data-email="{{ strtolower($user->email) }}"
                            data-role="{{ $user->role }}"
                            data-status="{{ $user->status }}">
                            <td class="px-5 py-4 text-gray-400 font-medium text-xs">{{ $users->firstItem() + $index }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0
                                        {{ $user->role === 'admin' ? 'bg-purple-100' : 'bg-blue-100' }}">
                                        <span class="font-bold text-sm {{ $user->role === 'admin' ? 'text-purple-700' : 'text-blue-700' }}">
                                            {{ strtoupper(substr($user->username, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $user->username }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">ID #{{ $user->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-gray-600 text-sm">{{ $user->nama }}</td>
                            <td class="px-5 py-4 text-gray-600 text-sm">{{ $user->email }}</td>
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
                            <td class="px-5 py-4 text-gray-400 text-xs">
                                {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('owner.users.edit', $user->id) }}" title="Edit"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-yellow-50 hover:bg-yellow-100 text-yellow-500 border border-yellow-100 transition">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    <button onclick="confirmToggle({{ $user->id }}, '{{ addslashes($user->username) }}', '{{ $user->status }}', '{{ route('owner.users.toggle', $user->id) }}')"
                                        title="{{ $user->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg border transition
                                            {{ $user->status === 'aktif' ? 'bg-red-50 hover:bg-red-100 text-red-500 border-red-100' : 'bg-green-50 hover:bg-green-100 text-green-500 border-green-100' }}">
                                        <i class="fa-solid {{ $user->status === 'aktif' ? 'fa-ban' : 'fa-circle-check' }} text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-16 text-center text-gray-400">
                                <i class="fa-solid fa-users text-4xl mb-3 block text-gray-200"></i>
                                <p class="font-medium">Belum ada data user</p>
                                <p class="text-xs mt-1">Klik "Tambah User" untuk menambahkan akun baru</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->total() > 0)
        <div class="px-5 py-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-sm text-gray-500">
            <div class="flex items-center gap-2">
                <span>Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} user. Tampilkan</span>
                <select onchange="changePerPage(this.value)"
                    class="border border-gray-200 rounded-lg px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white">
                    @foreach([5, 10, 25, 50] as $opt)
                        <option value="{{ $opt }}" {{ $perPage == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
                <span>data</span>
            </div>
            <div class="flex items-center gap-1">
                @if($users->onFirstPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 cursor-not-allowed text-xs">«</span>
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 cursor-not-allowed text-xs">‹</span>
                @else
                    <a href="{{ $users->url(1) }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500 transition text-xs">«</a>
                    <a href="{{ $users->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500 transition text-xs">‹</a>
                @endif
                @php $current = $users->currentPage(); $last = $users->lastPage(); $start = max(1,$current-1); $end = min($last,$current+1); @endphp
                @if($start > 1)
                    <a href="{{ $users->url(1) }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-600 transition text-xs">1</a>
                    @if($start > 2)<span class="text-gray-400 text-xs">…</span>@endif
                @endif
                @for($page = $start; $page <= $end; $page++)
                    @if($page == $current)
                        <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-primary-700 text-white font-semibold text-xs">{{ $page }}</span>
                    @else
                        <a href="{{ $users->url($page) }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-600 transition text-xs">{{ $page }}</a>
                    @endif
                @endfor
                @if($end < $last)
                    @if($end < $last - 1)<span class="text-gray-400 text-xs">…</span>@endif
                    <a href="{{ $users->url($last) }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-600 transition text-xs">{{ $last }}</a>
                @endif
                @if($users->hasMorePages())
                    <a href="{{ $users->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500 transition text-xs">›</a>
                    <a href="{{ $users->url($users->lastPage()) }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500 transition text-xs">»</a>
                @else
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 cursor-not-allowed text-xs">›</span>
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 cursor-not-allowed text-xs">»</span>
                @endif
            </div>
        </div>
        @endif
    </div>

    <form id="formToggle" method="POST" class="hidden">
        @csrf
        @method('PATCH')
    </form>
    <form id="formDelete" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function changePerPage(val) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', val);
            url.searchParams.set('page', 1);
            window.location.href = url.toString();
        }

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

        function confirmToggle(id, username, status, url) {
            const isAktif = status === 'aktif';
            Swal.fire({
                title: isAktif ? 'Nonaktifkan User?' : 'Aktifkan User?',
                html: `User <b>${username}</b> akan di${isAktif ? 'nonaktif' : 'aktif'}kan.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: isAktif ? '#dc2626' : '#16a34a',
                cancelButtonColor: '#6b7280',
                confirmButtonText: isAktif ? 'Ya, Nonaktifkan!' : 'Ya, Aktifkan!',
                cancelButtonText: 'Batal',
            }).then(result => {
                if (result.isConfirmed) {
                    const form = document.getElementById('formToggle');
                    form.action = url;
                    form.submit();
                }
            });
        }

        function confirmDelete(id, username, url) {
            Swal.fire({
                title: 'Hapus User?',
                html: `Akun <b>${username}</b> akan dihapus permanen.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
            }).then(result => {
                if (result.isConfirmed) {
                    const form = document.getElementById('formDelete');
                    form.action = url;
                    form.submit();
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