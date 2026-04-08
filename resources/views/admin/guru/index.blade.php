@extends('Layout.admin')

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Data Guru</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola semua data guru kursus</p>
        </div>
        <a href="{{ route('admin.guru.add') }}"
            class="inline-flex items-center gap-2 bg-primary-700 hover:bg-primary-800 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition">
            <i class="fa-solid fa-plus text-xs"></i> Tambah Guru
        </a>
    </div>

    {{-- Search & Filter --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 mb-5 flex flex-col sm:flex-row gap-3 flex-wrap">
        <div class="relative flex-1 min-w-48">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari nama, email, atau no. HP..."
                class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent" />
        </div>
        <select id="filterKelas" onchange="filterTable()"
            class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white text-gray-600">
            <option value="">Semua Kelas</option>
            @foreach($kelasList as $k)
                <option value="{{ strtolower($k->nama_kelas) }}">{{ $k->nama_kelas }}</option>
            @endforeach
        </select>
        <select id="filterJK" onchange="filterTable()"
            class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white text-gray-600">
            <option value="">Semua J. Kelamin</option>
            <option value="laki-laki">Laki-laki</option>
            <option value="perempuan">Perempuan</option>
        </select>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-primary-700 text-white text-left">
                        <th class="px-5 py-3.5 font-semibold">No</th>
                        <th class="px-5 py-3.5 font-semibold">Nama</th>
                        <th class="px-5 py-3.5 font-semibold">No. HP</th>
                        <th class="px-5 py-3.5 font-semibold">Jenis Kelamin</th>
                        <th class="px-5 py-3.5 font-semibold">Kelas Diajarkan</th>
                        <th class="px-5 py-3.5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="tableBody">
                    @forelse($guru as $index => $g)
                        @php
                            $kelasGuru  = $g->kelas;
                            $totalKelas = $kelasGuru->count();
                            $isPerempuan = strtolower($g->jenis_kelamin ?? '') === 'perempuan';
                        @endphp
                        <tr class="hover:bg-gray-50 transition guru-row"
                            data-nama="{{ strtolower($g->nama) }}"
                            data-email="{{ strtolower($g->email) }}"
                            data-hp="{{ $g->no_hp }}"
                            data-kelas="{{ strtolower($g->kelas->pluck('nama_kelas')->implode('|')) }}"
                            data-jk="{{ strtolower($g->jenis_kelamin ?? '') }}">

                            <td class="px-5 py-3.5 text-gray-400 font-medium text-xs">{{ $guru->firstItem() + $index }}</td>

                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0
                                        {{ $isPerempuan ? 'bg-pink-100' : 'bg-primary-100' }}">
                                        <span class="font-bold text-xs {{ $isPerempuan ? 'text-pink-600' : 'text-primary-700' }}">
                                            {{ strtoupper(substr($g->nama, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $g->nama }}</p>
                                        <p class="text-xs text-gray-400">{{ $g->email }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-5 py-3.5 text-gray-600">{{ $g->no_hp }}</td>

                            <td class="px-5 py-3.5">
                                @if($isPerempuan)
                                    <span class="inline-flex items-center gap-1.5 bg-pink-50 text-pink-600 text-xs font-semibold px-3 py-1 rounded-full border border-pink-100">
                                        <i class="fa-solid fa-venus text-xs"></i> Perempuan
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-600 text-xs font-semibold px-3 py-1 rounded-full border border-blue-100">
                                        <i class="fa-solid fa-mars text-xs"></i> Laki-laki
                                    </span>
                                @endif
                            </td>

                            {{-- Kelas Diajarkan: tampil 1 + badge +N --}}
                            <td class="px-5 py-3.5">
                                <div class="flex flex-wrap gap-1 items-center">
                                    @if($totalKelas === 0)
                                        <span class="text-gray-300 text-xs italic">Belum ada kelas</span>
                                    @else
                                        <span class="inline-flex items-center bg-primary-50 text-primary-700 text-xs font-medium px-2 py-0.5 rounded-full border border-primary-100">
                                            {{ $kelasGuru->first()->nama_kelas }}
                                        </span>
                                        @if($totalKelas > 1)
                                            <span
                                                class="bg-gray-100 text-gray-600 text-xs font-semibold px-2 py-0.5 rounded-full cursor-pointer hover:bg-primary-100 hover:text-primary-700 transition"
                                                title="{{ $kelasGuru->skip(1)->pluck('nama_kelas')->implode(', ') }}">
                                                +{{ $totalKelas - 1 }}
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </td>

                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-1.5">
                                    {{-- Tombol View Detail --}}
                                    <button
                                        onclick="openDetail({
                                            nama: '{{ addslashes($g->nama) }}',
                                            email: '{{ addslashes($g->email) }}',
                                            no_hp: '{{ $g->no_hp }}',
                                            jenis_kelamin: '{{ strtolower($g->jenis_kelamin ?? '') }}',
                                            kelas: [{{ $kelasGuru->map(fn($k) => '"' . addslashes($k->nama_kelas) . '"')->implode(',') }}],
                                            edit_url: '{{ route('admin.guru.edit', $g->id) }}'
                                        })"
                                        title="Detail"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 transition">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </button>
                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('admin.guru.edit', $g->id) }}"
                                        title="Edit"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-yellow-50 hover:bg-yellow-100 text-yellow-600 transition">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    {{-- Tombol Hapus --}}
                                    <button onclick="confirmDelete({{ $g->id }}, '{{ addslashes($g->nama) }}')"
                                        title="Hapus"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 hover:bg-red-100 text-red-500 transition">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center text-gray-400">
                                <i class="fa-solid fa-chalkboard-user text-4xl mb-3 block text-gray-200"></i>
                                <p class="font-medium">Belum ada data guru</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($guru->total() > 0)
        <div class="px-5 py-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-sm text-gray-500">
            <div class="flex items-center gap-2">
                <span>Menampilkan {{ $guru->firstItem() }}–{{ $guru->lastItem() }} dari {{ $guru->total() }} guru. Tampilkan</span>
                <select onchange="changePerPage(this.value)"
                    class="border border-gray-200 rounded-lg px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white">
                    @foreach([5, 10, 25, 50] as $opt)
                        <option value="{{ $opt }}" {{ $perPage == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
                <span>data</span>
            </div>
            <div class="flex items-center gap-1">
                @if($guru->onFirstPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 cursor-not-allowed text-xs">«</span>
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 cursor-not-allowed text-xs">‹</span>
                @else
                    <a href="{{ $guru->url(1) }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500 transition text-xs">«</a>
                    <a href="{{ $guru->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500 transition text-xs">‹</a>
                @endif

                @php
                    $current = $guru->currentPage();
                    $last    = $guru->lastPage();
                    $start   = max(1, $current - 1);
                    $end     = min($last, $current + 1);
                @endphp

                @if($start > 1)
                    <a href="{{ $guru->url(1) }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-600 transition text-xs">1</a>
                    @if($start > 2)<span class="w-8 h-8 flex items-center justify-center text-gray-400 text-xs">…</span>@endif
                @endif

                @for($page = $start; $page <= $end; $page++)
                    @if($page == $current)
                        <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-primary-700 text-white font-semibold text-xs">{{ $page }}</span>
                    @else
                        <a href="{{ $guru->url($page) }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-600 transition text-xs">{{ $page }}</a>
                    @endif
                @endfor

                @if($end < $last)
                    @if($end < $last - 1)<span class="w-8 h-8 flex items-center justify-center text-gray-400 text-xs">…</span>@endif
                    <a href="{{ $guru->url($last) }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-600 transition text-xs">{{ $last }}</a>
                @endif

                @if($guru->hasMorePages())
                    <a href="{{ $guru->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500 transition text-xs">›</a>
                    <a href="{{ $guru->url($guru->lastPage()) }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500 transition text-xs">»</a>
                @else
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 cursor-not-allowed text-xs">›</span>
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 cursor-not-allowed text-xs">»</span>
                @endif
            </div>
        </div>
        @endif
    </div>

    <form id="formDelete" method="POST" class="hidden">@csrf @method('DELETE')</form>


    {{-- ==================== MODAL DETAIL GURU ==================== --}}
    <div id="modalDetail" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 bg-primary-700">
                <h2 class="text-white font-bold text-base">Detail Guru</h2>
                <button onclick="closeModal()" class="text-white/70 hover:text-white transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            {{-- Body --}}
            <div class="px-6 py-5 space-y-4">
                {{-- Avatar --}}
                <div class="flex justify-center">
                    <div id="modal_avatar_wrap" class="w-16 h-16 rounded-full flex items-center justify-center">
                        <span id="modal_avatar" class="font-bold text-2xl"></span>
                    </div>
                </div>
                {{-- Grid info --}}
                <div class="grid grid-cols-2 gap-y-4 gap-x-4 text-sm">
                    <div>
                        <p class="text-xs text-gray-400 font-medium">Nama</p>
                        <p id="modal_nama" class="font-semibold text-gray-800 mt-0.5">—</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium">Email</p>
                        <p id="modal_email" class="font-semibold text-gray-800 mt-0.5 break-all">—</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium">No. HP</p>
                        <p id="modal_nohp" class="font-semibold text-gray-800 mt-0.5">—</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium">Jenis Kelamin</p>
                        <p id="modal_jk" class="font-semibold text-gray-800 mt-0.5">—</p>
                    </div>
                </div>
                {{-- Kelas --}}
                <div>
                    <p class="text-xs text-gray-400 font-medium mb-1.5">Kelas Diajarkan</p>
                    <div id="modal_kelas" class="flex flex-wrap gap-1.5"></div>
                </div>
                {{-- Footer --}}
                <div class="flex justify-end gap-3 pt-1">
                    <button onclick="closeModal()"
                        class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                        Tutup
                    </button>
                    <a id="modal_edit_link" href="#"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-yellow-500 hover:bg-yellow-600 rounded-xl shadow transition">
                        <i class="fa-solid fa-pen mr-1.5"></i> Edit
                    </a>
                </div>
            </div>
        </div>
    </div>


    <script>
        function changePerPage(val) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', val);
            url.searchParams.set('page', 1);
            window.location.href = url.toString();
        }

        function filterTable() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const kelas  = document.getElementById('filterKelas').value.toLowerCase();
            const jk     = document.getElementById('filterJK').value.toLowerCase();

            document.querySelectorAll('.guru-row').forEach(row => {
                const matchSearch = row.dataset.nama.includes(search)
                                 || row.dataset.email.includes(search)
                                 || row.dataset.hp.includes(search);
                const matchKelas  = !kelas || row.dataset.kelas.split('|').some(k => k === kelas);
                const matchJK     = !jk || row.dataset.jk === jk;

                row.style.display = (matchSearch && matchKelas && matchJK) ? '' : 'none';
            });
        }

        function openDetail(data) {
            const isPerempuan = data.jenis_kelamin === 'perempuan';

            // Avatar
            document.getElementById('modal_avatar').textContent = data.nama.charAt(0).toUpperCase();
            const avatarWrap = document.getElementById('modal_avatar_wrap');
            avatarWrap.className = `w-16 h-16 rounded-full flex items-center justify-center ${isPerempuan ? 'bg-pink-100' : 'bg-primary-100'}`;
            document.getElementById('modal_avatar').className = `font-bold text-2xl ${isPerempuan ? 'text-pink-600' : 'text-primary-700'}`;

            // Info
            document.getElementById('modal_nama').textContent   = data.nama;
            document.getElementById('modal_email').textContent  = data.email;
            document.getElementById('modal_nohp').textContent   = data.no_hp;
            document.getElementById('modal_jk').textContent     = isPerempuan ? 'Perempuan' : 'Laki-laki';
            document.getElementById('modal_edit_link').href     = data.edit_url;

            // Kelas
            const kelasEl = document.getElementById('modal_kelas');
            kelasEl.innerHTML = data.kelas.length
                ? data.kelas.map(k => `<span class="bg-primary-50 text-primary-700 text-xs font-medium px-2.5 py-1 rounded-full border border-primary-100">${k}</span>`).join('')
                : '<span class="text-gray-400 text-sm">Belum ada kelas</span>';

            // Show modal
            const modal = document.getElementById('modalDetail');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            const modal = document.getElementById('modalDetail');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Tutup modal klik di luar
        document.getElementById('modalDetail').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        function confirmDelete(id, nama) {
            Swal.fire({
                title: 'Hapus Guru?',
                html: `Data <b>${nama}</b> akan dihapus dari sistem.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
            }).then(result => {
                if (result.isConfirmed) {
                    const form = document.getElementById('formDelete');
                    form.action = `/admin/guru/${id}`;
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