@extends('Layout.kasir')

@section('content')

    {{-- Page Title --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Data Peserta</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola semua data peserta kursus</p>
        </div>
        <a href="{{ url('/kasir/peserta/add') }}"
            class="flex items-center gap-2 bg-primary-700 hover:bg-primary-800 text-white text-sm font-medium px-4 py-2.5 rounded-xl shadow transition">
            <i class="fa-solid fa-plus"></i>
            Tambah
        </a>
    </div>

    {{-- Search & Filter --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 mb-5 flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" id="searchInput" oninput="applyFilter()" placeholder="Cari nama atau no HP..."
                class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent" />
        </div>
        <select id="filterJK" onchange="applyFilter()"
            class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent bg-white text-gray-600">
            <option value="">Semua J Kelamin</option>
            <option value="laki-laki">Laki-laki</option>
            <option value="perempuan">Perempuan</option>
        </select>
        <select id="filterLevel" onchange="applyFilter()"
            class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent bg-white text-gray-600">
            <option value="">Semua Level</option>
            {{-- diisi dinamis dari JS --}}
        </select>
        <select id="filterKelas" onchange="applyFilter()"
            class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent bg-white text-gray-600">
            <option value="">Semua Kursus</option>
            @foreach($kelasList ?? [] as $kelas)
                <option value="{{ strtolower($kelas->nama_kelas) }}">{{ $kelas->nama_kelas }}</option>
            @endforeach
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
                        <th class="px-5 py-3.5 font-semibold">Level</th>
                        <th class="px-5 py-3.5 font-semibold">Kelas Kursus</th>
                        <th class="px-5 py-3.5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="divide-y divide-gray-100">
                    {{-- diisi JS --}}
                </tbody>
            </table>
        </div>

        {{-- Pagination Bar --}}
        <div class="px-5 py-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-3 text-sm text-gray-500">
                <span id="paginationInfo" class="text-xs">—</span>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-400">Tampilkan</span>
                    <select id="perPage" onchange="changePerPage()"
                        class="text-xs border border-gray-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white text-gray-700 font-semibold cursor-pointer">
                        <option value="5" selected>5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="9999">Semua</option>
                    </select>
                    <span class="text-xs text-gray-400">data</span>
                </div>
            </div>
            <div id="paginationNav" class="flex items-center gap-1">
                {{-- diisi JS --}}
            </div>
        </div>
    </div>


    {{-- ==================== MODAL DETAIL ==================== --}}
    <div id="modalDetail" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 bg-primary-700">
                <h2 class="text-white font-bold text-base">Detail Peserta</h2>
                <button onclick="closeModal('modalDetail')" class="text-white/70 hover:text-white transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <div class="px-6 py-5 space-y-4">
                <div class="flex justify-center">
                    <div class="w-16 h-16 rounded-full bg-primary-100 flex items-center justify-center">
                        <span id="detail_avatar" class="text-primary-700 font-bold text-2xl"></span>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-y-4 gap-x-4 text-sm">
                    <div>
                        <p class="text-xs text-gray-400 font-medium">Nama</p>
                        <p id="detail_nama" class="font-semibold text-gray-800 mt-0.5">—</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium">Email</p>
                        <p id="detail_email" class="font-semibold text-gray-800 mt-0.5 break-all">—</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium">No. HP</p>
                        <p id="detail_nohp" class="font-semibold text-gray-800 mt-0.5">—</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium">Jenis Kelamin</p>
                        <p id="detail_jk" class="font-semibold text-gray-800 mt-0.5">—</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium">Nama Orang Tua</p>
                        <p id="detail_orangtua" class="font-semibold text-gray-800 mt-0.5">—</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium">No. HP Orang Tua</p>
                        <p id="detail_noorangtua" class="font-semibold text-gray-800 mt-0.5">—</p>
                    </div>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-medium mb-1.5">Kelas Kursus</p>
                    <div id="detail_kelas" class="flex flex-wrap gap-1.5"></div>
                </div>
                <div class="flex justify-end gap-3 pt-1">
                    <button onclick="closeModal('modalDetail')"
                        class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                        Tutup
                    </button>
                    <a id="detail_edit_link" href="#"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-yellow-500 hover:bg-yellow-600 rounded-xl shadow transition">
                        <i class="fa-solid fa-pen mr-1.5"></i> Edit
                    </a>
                </div>
            </div>
        </div>
    </div>


    {{-- ===== Data peserta dari Blade ke JS ===== --}}
    @php
        $pesertaJs = collect($peserta ?? [])->map(function($p) {
            return [
                'id'             => $p->id,
                'nama'           => $p->nama,
                'email'          => $p->email,
                'no_hp'          => $p->no_hp,
                'jenis_kelamin'  => $p->jenis_kelamin,
                'level'          => $p->level ?? '-',
                'status'         => strtolower($p->status),
                'nama_orangtua'  => $p->nama_ortu ?? '-',
                'no_orangtua'    => $p->no_ortu  ?? '-',
                'kelas'          => $p->kelas->pluck('nama_kelas')->toArray(),
                'edit_url'       => url('/kasir/peserta/' . $p->id . '/edit'),
            ];
        })->values()->toArray();
    @endphp

    <script>
        const allData = @json($pesertaJs);

        // Isi dropdown level secara dinamis
        const levelSet = [...new Set(allData.map(p => p.level).filter(k => k && k !== '-'))].sort();
        const selectLevel = document.getElementById('filterLevel');
        levelSet.forEach(k => {
            const opt = document.createElement('option');
            opt.value = k.toLowerCase();
            opt.textContent = k;
            selectLevel.appendChild(opt);
        });

        // ===== State =====
        let filteredData = [...allData];
        let currentPage  = 1;
        let perPage      = 5;

        // ===== Filter =====
        function applyFilter() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const jk     = document.getElementById('filterJK').value;
            const level  = document.getElementById('filterLevel').value.toLowerCase();
            const kelas  = document.getElementById('filterKelas').value.toLowerCase();

            filteredData = allData.filter(p => {
                const matchSearch = p.nama.toLowerCase().includes(search) || p.no_hp.includes(search);
                const matchJK     = !jk || p.jenis_kelamin === jk;
                const matchLevel  = !level || (p.level ?? '').toLowerCase().includes(level);
                const matchKelas  = !kelas || p.kelas.some(k => k.toLowerCase().includes(kelas));
                return matchSearch && matchJK && matchLevel && matchKelas;
            });

            currentPage = 1;
            render();
        }

        function changePerPage() {
            perPage     = parseInt(document.getElementById('perPage').value);
            currentPage = 1;
            render();
        }

        // ===== Helper: render badge kelas (1 tampil + sisanya jadi +N) =====
        function renderKelasBadges(kelasList) {
            if (!kelasList.length) return '<span class="text-gray-400 text-xs">—</span>';

            const first = kelasList[0];
            const more  = kelasList.length - 1;
            const tooltipText = more > 0 ? kelasList.slice(1).join(', ') : '';

            return `
                <span class="bg-primary-50 text-primary-700 text-xs font-medium px-2.5 py-1 rounded-full border border-primary-100">${first}</span>
                ${more > 0
                    ? `<span title="${tooltipText}" class="bg-gray-100 text-gray-500 text-xs font-semibold px-2 py-1 rounded-full cursor-default">+${more}</span>`
                    : ''
                }
            `;
        }

        // ===== Render Tabel =====
        function render() {
            const isAll      = perPage === 9999;
            const start      = isAll ? 0 : (currentPage - 1) * perPage;
            const end        = isAll ? filteredData.length : start + perPage;
            const pageData   = filteredData.slice(start, end);
            const total      = filteredData.length;
            const totalPages = isAll ? 1 : Math.ceil(total / perPage);

            const tbody = document.getElementById('tableBody');
            if (!pageData.length) {
                tbody.innerHTML = `<tr><td colspan="7" class="px-5 py-16 text-center text-gray-400">
                    <i class="fa-solid fa-users text-4xl mb-3 block text-gray-200"></i>
                    <p class="font-medium">Tidak ada data peserta</p>
                </td></tr>`;
            } else {
                tbody.innerHTML = pageData.map((p, i) => {
                    const no = start + i + 1;

                    const kelasBadges = renderKelasBadges(p.kelas);

                    const jkLabel = p.jenis_kelamin === 'laki-laki' ? 'Laki-laki' : 'Perempuan';

                    const levelColor = {
                        cukup : 'bg-yellow-50 text-yellow-700 border-yellow-100',
                        baik  : 'bg-blue-50 text-blue-700 border-blue-100',
                        mahir : 'bg-green-50 text-green-700 border-green-100'
                    };
                    const levelBadge = p.level && p.level !== '-'
                        ? `<span class="text-xs font-semibold px-2.5 py-1 rounded-full border ${levelColor[p.level] || 'bg-gray-50 text-gray-500 border-gray-200'}">${p.level.charAt(0).toUpperCase() + p.level.slice(1)}</span>`
                        : '<span class="text-gray-400 text-xs">—</span>';

                    return `
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3.5 text-gray-400 font-medium text-xs">${no}</td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                                        <span class="text-primary-700 font-bold text-xs">${p.nama.charAt(0).toUpperCase()}</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">${p.nama}</p>
                                        <p class="text-xs text-gray-400">${p.email}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-gray-600">${p.no_hp}</td>
                            <td class="px-5 py-3.5 text-gray-600">${jkLabel}</td>
                            <td class="px-5 py-3.5">${levelBadge}</td>
                            <td class="px-5 py-3.5">
                                <div class="flex flex-wrap gap-1 items-center">${kelasBadges}</div>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button onclick="openDetail(${p.id})" title="Detail"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 transition">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </button>
                                    <a href="${p.edit_url}" title="Edit"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-yellow-50 hover:bg-yellow-100 text-yellow-600 transition">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>`;
                }).join('');
            }

            const from = total === 0 ? 0 : start + 1;
            const to   = Math.min(end, total);
            document.getElementById('paginationInfo').textContent =
                `Menampilkan ${from}–${to} dari ${total} peserta`;

            renderPagination(totalPages);
        }

        // ===== Render Pagination =====
        function renderPagination(totalPages) {
            const nav = document.getElementById('paginationNav');
            if (totalPages <= 1) { nav.innerHTML = ''; return; }

            const base     = 'w-8 h-8 flex items-center justify-center rounded-lg text-xs font-semibold transition cursor-pointer select-none';
            const active   = 'bg-primary-700 text-white shadow-sm';
            const normal   = 'bg-white border border-gray-200 text-gray-600 hover:bg-primary-50 hover:border-primary-300 hover:text-primary-700';
            const disabled = 'bg-gray-50 border border-gray-100 text-gray-300 cursor-not-allowed';

            let html = '';
            html += `<button onclick="goPage(1)" ${currentPage === 1 ? 'disabled' : ''}
                class="${base} ${currentPage === 1 ? disabled : normal}" title="Halaman pertama">
                <i class="fa-solid fa-angles-left" style="font-size:9px;"></i>
            </button>`;
            html += `<button onclick="goPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}
                class="${base} ${currentPage === 1 ? disabled : normal}" title="Sebelumnya">
                <i class="fa-solid fa-chevron-left" style="font-size:10px;"></i>
            </button>`;

            getPageRange(currentPage, totalPages).forEach(p => {
                if (p === '...') {
                    html += `<span class="${base} border border-transparent text-gray-400 cursor-default">…</span>`;
                } else {
                    html += `<button onclick="goPage(${p})" class="${base} ${p === currentPage ? active : normal}">${p}</button>`;
                }
            });

            html += `<button onclick="goPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}
                class="${base} ${currentPage === totalPages ? disabled : normal}" title="Selanjutnya">
                <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i>
            </button>`;
            html += `<button onclick="goPage(${totalPages})" ${currentPage === totalPages ? 'disabled' : ''}
                class="${base} ${currentPage === totalPages ? disabled : normal}" title="Halaman terakhir">
                <i class="fa-solid fa-angles-right" style="font-size:9px;"></i>
            </button>`;

            nav.innerHTML = html;
        }

        function getPageRange(cur, total) {
            if (total <= 7) return Array.from({ length: total }, (_, i) => i + 1);
            if (cur <= 4)        return [1, 2, 3, 4, 5, '...', total];
            if (cur >= total - 3) return [1, '...', total-4, total-3, total-2, total-1, total];
            return [1, '...', cur - 1, cur, cur + 1, '...', total];
        }

        function goPage(page) {
            const totalPages = perPage === 9999 ? 1 : Math.ceil(filteredData.length / perPage);
            if (page < 1 || page > totalPages) return;
            currentPage = page;
            render();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // ===== Modal =====
        function openDetail(id) {
            const p = allData.find(x => x.id === id);
            if (!p) return;
            document.getElementById('detail_avatar').textContent     = p.nama.charAt(0).toUpperCase();
            document.getElementById('detail_nama').textContent       = p.nama;
            document.getElementById('detail_email').textContent      = p.email;
            document.getElementById('detail_nohp').textContent       = p.no_hp;
            document.getElementById('detail_jk').textContent         = p.jenis_kelamin === 'laki-laki' ? 'Laki-laki' : 'Perempuan';
            document.getElementById('detail_orangtua').textContent   = p.nama_orangtua;
            document.getElementById('detail_noorangtua').textContent = p.no_orangtua;
            document.getElementById('detail_edit_link').href         = p.edit_url;

            // Modal detail tetap tampil semua kelas
            document.getElementById('detail_kelas').innerHTML = p.kelas.length
                ? p.kelas.map(k => `<span class="bg-primary-50 text-primary-700 text-xs font-medium px-2.5 py-1 rounded-full border border-primary-100">${k}</span>`).join('')
                : '<span class="text-gray-400 text-sm">Belum ada kelas</span>';

            openModal('modalDetail');
        }

        function openModal(id)  { const el = document.getElementById(id); el.classList.remove('hidden'); el.classList.add('flex'); }
        function closeModal(id) { const el = document.getElementById(id); el.classList.add('hidden'); el.classList.remove('flex'); }
        document.getElementById('modalDetail').addEventListener('click', function(e) { if (e.target === this) closeModal('modalDetail'); });

        // ===== Flash =====
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session("success") }}',
                confirmButtonColor: '#1e5399',
                timer: 3000,
                timerProgressBar: true,
            });
        @endif
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session("error") }}',
                confirmButtonColor: '#1e5399',
            });
        @endif

        // ===== Init =====
        render();
    </script>

@endsection