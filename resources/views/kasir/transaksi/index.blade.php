@extends('Layout.kasir')

@section('content')

    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-800">Transaksi Tagihan</h1>
        <p class="text-sm text-gray-500 mt-0.5">Kelola tagihan dan pembayaran peserta</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 mb-5 flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" id="searchInput" oninput="applyFilter()" placeholder="Cari nama atau no HP..."
                class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent" />
        </div>
        <select id="filterBulan" onchange="applyFilter()"
            class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white text-gray-600">
            <option value="">Semua Bulan</option>
            <option value="januari">Januari</option>
            <option value="februari">Februari</option>
            <option value="maret">Maret</option>
            <option value="april">April</option>
            <option value="mei">Mei</option>
            <option value="juni">Juni</option>
            <option value="juli">Juli</option>
            <option value="agustus">Agustus</option>
            <option value="september">September</option>
            <option value="oktober">Oktober</option>
            <option value="november">November</option>
            <option value="desember">Desember</option>
        </select>
        <select id="filterKelas" onchange="applyFilter()"
            class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white text-gray-600">
            <option value="">Semua kelas</option>
            @foreach($kelasList ?? [] as $kelas)
                <option value="{{ strtolower($kelas->nama_kelas) }}">{{ $kelas->nama_kelas }}</option>
            @endforeach
        </select>
        <select id="filterStatus" onchange="applyFilter()"
            class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white text-gray-600">
            <option value="">Semua Status</option>
            <option value="belum_bayar">Belum Lunas</option>
            <option value="lunas">Lunas</option>
        </select>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-primary-700 text-white text-left">
                        <th class="px-5 py-3.5 font-semibold">No</th>
                        <th class="px-5 py-3.5 font-semibold">Nama</th>
                        <th class="px-5 py-3.5 font-semibold">Kelas Kursus</th>
                        <th class="px-5 py-3.5 font-semibold">Tgl Tagihan</th>
                        <th class="px-5 py-3.5 font-semibold">Jatuh Tempo</th>
                        <th class="px-5 py-3.5 font-semibold">Status</th>
                        <th class="px-5 py-3.5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="divide-y divide-gray-100">
                </tbody>
            </table>
        </div>

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
            <div id="paginationNav" class="flex items-center gap-1"></div>
        </div>
    </div>

    <div id="modalHapus" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 overflow-hidden">
            <div class="px-6 py-4 bg-red-600 flex items-center gap-3">
                <i class="fa-solid fa-trash text-white"></i>
                <h2 class="text-white font-bold text-base">Hapus Tagihan</h2>
            </div>
            <div class="px-6 py-5">
                <p class="text-sm text-gray-600">Tagihan <span id="hapus_nama" class="font-bold text-gray-800"></span> akan dihapus permanen. Lanjutkan?</p>
                <div class="flex gap-3 mt-5">
                    <button onclick="closeModal('modalHapus')"
                        class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                        Batal
                    </button>
                    <form id="hapus_form" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full px-4 py-2.5 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-xl transition">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @php
        $tagihanJs = collect($tagihan ?? [])->map(function($t) {
            $parts = explode('-', $t->bulan_tahun);
            $bulanMap = [
                '01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April',
                '05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus',
                '09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember',
            ];
            $bulanLabel = $parts[1] ?? '';
            $bulanRaw   = strtolower($bulanMap[$parts[0]] ?? $parts[0]);

            $kelasArr = [];
            if (!empty($t->kelas_snapshot)) {
                $kelasArr = is_array($t->kelas_snapshot)
                    ? $t->kelas_snapshot
                    : json_decode($t->kelas_snapshot, true) ?? [];
            } else {
                $kelasArr = $t->peserta->kelas->pluck('nama_kelas')->toArray();
            }

            return [
                'id'                  => $t->id,
                'nama'                => $t->peserta->nama ?? '-',
                'no_hp'               => $t->peserta->no_hp ?? '',
                'kelas'               => $kelasArr,
                'bulan_raw'           => $bulanRaw,
                'bulan_label'         => $bulanLabel,
                'tanggal_tagihan'     => $t->tanggal_tagihan
                                            ? \Carbon\Carbon::parse($t->tanggal_tagihan)->translatedFormat('d M Y')
                                            : '-',
                'tanggal_jatuh_tempo' => $t->tanggal_jatuh_tempo
                                            ? \Carbon\Carbon::parse($t->tanggal_jatuh_tempo)->translatedFormat('d M Y')
                                            : '-',
                'jatuh_tempo_lewat'   => $t->tanggal_jatuh_tempo && $t->status !== 'lunas'
                                            && \Carbon\Carbon::parse($t->tanggal_jatuh_tempo)->isPast(),
                'status'              => strtolower($t->status),
                'bayar_url'           => url('/kasir/transaksi/' . $t->id . '/bayar'),
                'hapus_url'           => url('/kasir/transaksi/' . $t->id),
            ];
        })->values()->toArray();
    @endphp

    <script>
        const allData = @json($tagihanJs);

        let filteredData = [...allData];
        let currentPage  = 1;
        let perPage      = 5;

        function applyFilter() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const bulan  = document.getElementById('filterBulan').value.toLowerCase();
            const kelas  = document.getElementById('filterKelas').value.toLowerCase();
            const status = document.getElementById('filterStatus').value.toLowerCase();

            filteredData = allData.filter(t => {
                const matchSearch = t.nama.toLowerCase().includes(search) || t.no_hp.includes(search);
                const matchBulan  = !bulan  || t.bulan_raw === bulan;
                const matchKelas  = !kelas  || t.kelas.some(k => k.toLowerCase().includes(kelas));
                const matchStatus = !status || t.status === status;
                return matchSearch && matchBulan && matchKelas && matchStatus;
            });

            currentPage = 1;
            render();
        }

        function changePerPage() {
            perPage     = parseInt(document.getElementById('perPage').value);
            currentPage = 1;
            render();
        }

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
                    <i class="fa-solid fa-file-invoice text-4xl mb-3 block text-gray-200"></i>
                    <p class="font-medium">Tidak ada data tagihan</p>
                </td></tr>`;
            } else {
                tbody.innerHTML = pageData.map((t, i) => {
                    const no = start + i + 1;
                    const kelasBadges = t.kelas.length
                        ? t.kelas.map(k => `<span class="bg-primary-50 text-primary-700 text-xs font-medium px-2.5 py-1 rounded-full border border-primary-100">${k}</span>`).join('')
                        : '<span class="text-gray-400 text-xs">—</span>';

                    const statusBadge = t.status === 'lunas'
                        ? `<span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 text-xs font-semibold px-3 py-1 rounded-full border border-green-100"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Lunas</span>`
                        : `<span class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 text-xs font-semibold px-3 py-1 rounded-full border border-red-100"><span class="w-1.5 h-1.5 rounded-full bg-red-400"></span> Belum Lunas</span>`;

                    const aksi = t.status !== 'lunas'
                        ? `<div class="flex items-center justify-center gap-1.5">
                                <a href="${t.bayar_url}" title="Bayar"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-green-50 hover:bg-green-100 text-green-600 transition">
                                    <i class="fa-solid fa-money-bill-wave text-xs"></i>
                                </a>
                                <button onclick="openHapus(${t.id}, '${t.nama}', '${t.hapus_url}')" title="Hapus"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 hover:bg-red-100 text-red-500 transition">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                           </div>`
                        : `<div class="text-center text-xs text-gray-300">—</div>`;

                    const jatuhTempoBadge = t.jatuh_tempo_lewat
                        ? `<span class="text-red-500 font-semibold text-xs">${t.tanggal_jatuh_tempo}</span>`
                        : `<span class="text-gray-600 text-xs">${t.tanggal_jatuh_tempo}</span>`;

                    return `
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3.5 text-gray-400 font-medium text-xs">${no}</td>
                            <td class="px-5 py-3.5 font-semibold text-gray-800">${t.nama}</td>
                            <td class="px-5 py-3.5"><div class="flex flex-wrap gap-1">${kelasBadges}</div></td>
                            <td class="px-5 py-3.5 text-gray-600 text-xs">${t.tanggal_tagihan}</td>
                            <td class="px-5 py-3.5">${jatuhTempoBadge}</td>
                            <td class="px-5 py-3.5">${statusBadge}</td>
                            <td class="px-5 py-3.5">${aksi}</td>
                        </tr>`;
                }).join('');
            }

            const from = total === 0 ? 0 : start + 1;
            const to   = Math.min(end, total);
            document.getElementById('paginationInfo').textContent =
                `Menampilkan ${from}–${to} dari ${total} transaksi`;

            renderPagination(totalPages);
        }

        function renderPagination(totalPages) {
            const nav = document.getElementById('paginationNav');
            if (totalPages <= 1) { nav.innerHTML = ''; return; }

            const base     = 'w-8 h-8 flex items-center justify-center rounded-lg text-xs font-semibold transition cursor-pointer select-none';
            const active   = 'bg-primary-700 text-white shadow-sm';
            const normal   = 'bg-white border border-gray-200 text-gray-600 hover:bg-primary-50 hover:border-primary-300 hover:text-primary-700';
            const disabled = 'bg-gray-50 border border-gray-100 text-gray-300 cursor-not-allowed';

            let html = '';
            html += `<button onclick="goPage(1)" ${currentPage===1?'disabled':''} class="${base} ${currentPage===1?disabled:normal}"><i class="fa-solid fa-angles-left" style="font-size:9px;"></i></button>`;
            html += `<button onclick="goPage(${currentPage-1})" ${currentPage===1?'disabled':''} class="${base} ${currentPage===1?disabled:normal}"><i class="fa-solid fa-chevron-left" style="font-size:10px;"></i></button>`;

            getPageRange(currentPage, totalPages).forEach(p => {
                if (p === '...') {
                    html += `<span class="${base} border border-transparent text-gray-400 cursor-default">…</span>`;
                } else {
                    html += `<button onclick="goPage(${p})" class="${base} ${p===currentPage?active:normal}">${p}</button>`;
                }
            });

            html += `<button onclick="goPage(${currentPage+1})" ${currentPage===totalPages?'disabled':''} class="${base} ${currentPage===totalPages?disabled:normal}"><i class="fa-solid fa-chevron-right" style="font-size:10px;"></i></button>`;
            html += `<button onclick="goPage(${totalPages})" ${currentPage===totalPages?'disabled':''} class="${base} ${currentPage===totalPages?disabled:normal}"><i class="fa-solid fa-angles-right" style="font-size:9px;"></i></button>`;

            nav.innerHTML = html;
        }

        function getPageRange(cur, total) {
            if (total <= 7) return Array.from({ length: total }, (_, i) => i + 1);
            if (cur <= 4)  return [1, 2, 3, 4, 5, '...', total];
            if (cur >= total - 3) return [1, '...', total-4, total-3, total-2, total-1, total];
            return [1, '...', cur-1, cur, cur+1, '...', total];
        }

        function goPage(page) {
            const totalPages = perPage === 9999 ? 1 : Math.ceil(filteredData.length / perPage);
            if (page < 1 || page > totalPages) return;
            currentPage = page;
            render();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function openHapus(id, nama, url) {
            document.getElementById('hapus_nama').textContent = nama;
            document.getElementById('hapus_form').action = url;
            openModal('modalHapus');
        }

        function openModal(id) { const el = document.getElementById(id); el.classList.remove('hidden'); el.classList.add('flex'); }
        function closeModal(id) { const el = document.getElementById(id); el.classList.add('hidden'); el.classList.remove('flex'); }
        document.getElementById('modalHapus').addEventListener('click', function(e) { if (e.target === this) closeModal('modalHapus'); });

        @if(session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session("success") }}', confirmButtonColor: '#1e5399' });
        @endif
        @if(session('error'))
            Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session("error") }}', confirmButtonColor: '#1e5399' });
        @endif

        render();
    </script>

@endsection