@extends('Layout.owner')

@section('content')

    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-800">Data Kelas</h1>
        <p class="text-sm text-gray-500 mt-0.5">Daftar semua kelas beserta peserta yang terdaftar</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 mb-5 flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari nama kelas atau hari..."
                class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent" />
        </div>
        <select id="filterHari" onchange="filterTable()"
            class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white text-gray-600">
            <option value="">Semua Hari</option>
            <option value="senin">Senin</option><option value="selasa">Selasa</option>
            <option value="rabu">Rabu</option><option value="kamis">Kamis</option>
            <option value="jumat">Jumat</option><option value="sabtu">Sabtu</option>
            <option value="minggu">Minggu</option>
        </select>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" id="kelasGrid">
        @forelse($kelas ?? [] as $k)
            @php
                $hariList = collect(explode(',', $k->hari_kelas))->map(fn($h) => trim($h));
                $hariColor = [
                    'Senin'  => 'bg-blue-50 text-blue-700 border-blue-100',
                    'Selasa' => 'bg-purple-50 text-purple-700 border-purple-100',
                    'Rabu'   => 'bg-green-50 text-green-700 border-green-100',
                    'Kamis'  => 'bg-yellow-50 text-yellow-700 border-yellow-100',
                    'Jumat'  => 'bg-orange-50 text-orange-700 border-orange-100',
                    'Sabtu'  => 'bg-pink-50 text-pink-700 border-pink-100',
                    'Minggu' => 'bg-red-50 text-red-700 border-red-100',
                ];
            @endphp
            <div class="kelas-card bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition"
                data-nama="{{ strtolower($k->nama_kelas) }}"
                data-hari="{{ strtolower($k->hari_kelas) }}">

                <div class="bg-primary-700 px-5 py-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-chalkboard-user text-white"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-white font-bold text-sm truncate">{{ $k->nama_kelas }}</p>
                        <p class="text-blue-200 text-xs">Rp {{ number_format($k->harga_kelas, 0, ',', '.') }} / bulan</p>
                    </div>
                </div>

                <div class="p-5 space-y-3">

                    <div class="flex flex-wrap gap-1">
                        @foreach($hariList as $hari)
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full border {{ $hariColor[trim($hari)] ?? 'bg-gray-50 text-gray-600 border-gray-200' }}">
                                {{ $hari }}
                            </span>
                        @endforeach
                    </div>

                    <div class="space-y-2 pt-1">

                        <div class="flex items-center gap-2.5">
                            <div class="w-6 h-6 rounded-lg bg-primary-50 flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-user text-primary-500 text-[10px]"></i>
                            </div>
                            <span class="text-xs text-gray-500">Guru:</span>
                            <span class="text-xs font-semibold text-gray-700">{{ $k->guru->nama ?? '-' }}</span>
                        </div>

                        <div class="flex items-center gap-2.5">
                            <div class="w-6 h-6 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-clock text-blue-400 text-[10px]"></i>
                            </div>
                            <span class="text-xs text-gray-500">Jam:</span>
                            <span class="text-xs font-semibold text-gray-700">{{ $k->jam_mulai }} – {{ $k->jam_selesai }}</span>
                        </div>

                        @if($k->deskripsi)
                        <div class="flex items-start gap-2.5">
                            <div class="w-6 h-6 rounded-lg bg-gray-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fa-solid fa-align-left text-gray-400 text-[10px]"></i>
                            </div>
                            <span class="text-xs text-gray-500 leading-relaxed">{{ Str::limit($k->deskripsi, 60) }}</span>
                        </div>
                        @endif

                    </div>

                    <div class="border-t border-gray-100 pt-3 flex items-center justify-between">
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full border
                            {{ ($k->jumlah_peserta ?? 0) > 0 ? 'bg-green-50 text-green-700 border-green-100' : 'bg-gray-50 text-gray-400 border-gray-200' }}">
                            <i class="fa-solid fa-users text-[10px]"></i>
                            {{ $k->jumlah_peserta ?? 0 }} peserta
                        </span>
                        <button onclick="openDetailModal({{ $k->id }})"
                            class="flex items-center gap-1.5 text-xs font-semibold text-primary-700 hover:text-primary-900 bg-primary-50 hover:bg-primary-100 px-3 py-1.5 rounded-lg border border-primary-100 transition">
                            <i class="fa-solid fa-eye text-xs"></i> Lihat Detail
                        </button>
                    </div>

                </div>
            </div>
        @empty
            <div class="col-span-3 py-16 text-center text-gray-400">
                <i class="fa-solid fa-chalkboard-user text-4xl mb-3 block text-gray-200"></i>
                <p class="font-medium">Belum ada data kelas</p>
            </div>
        @endforelse
    </div>


    <div id="modalDetailKelas"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden"
        onclick="closeModalOnBackdrop(event)">

        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] flex flex-col overflow-hidden">

            <div class="flex items-center justify-between px-6 py-4 bg-primary-700 rounded-t-2xl flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                        <i class="fa-solid fa-chalkboard-user text-white text-sm"></i>
                    </div>
                    <div>
                        <h2 id="modalNamaKelas" class="text-white font-bold text-base leading-tight">-</h2>
                        <p class="text-white/70 text-xs mt-0.5">Detail Kelas</p>
                    </div>
                </div>
                <button onclick="closeDetailModal()" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/20 hover:bg-white/30 text-white transition">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <div class="overflow-y-auto flex-1 p-6 space-y-5">

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <p class="text-xs text-gray-400 mb-1 flex items-center gap-1.5">
                            <i class="fa-solid fa-user-tie"></i> Guru Pengajar
                        </p>
                        <p id="modalGuru" class="text-sm font-semibold text-gray-800">-</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <p class="text-xs text-gray-400 mb-1 flex items-center gap-1.5">
                            <i class="fa-solid fa-tag"></i> Harga / Bulan
                        </p>
                        <p id="modalHarga" class="text-sm font-semibold text-gray-800">-</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <p class="text-xs text-gray-400 mb-1 flex items-center gap-1.5">
                            <i class="fa-solid fa-clock"></i> Jam Kelas
                        </p>
                        <p id="modalJam" class="text-sm font-semibold text-gray-800">-</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <p class="text-xs text-gray-400 mb-1 flex items-center gap-1.5">
                            <i class="fa-solid fa-users"></i> Peserta Aktif
                        </p>
                        <p id="modalPesertaCount" class="text-sm font-semibold text-gray-800">-</p>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                    <p class="text-xs text-gray-400 mb-2 flex items-center gap-1.5">
                        <i class="fa-solid fa-calendar-days"></i> Hari Kelas
                    </p>
                    <div id="modalHari" class="flex flex-wrap gap-1.5"></div>
                </div>

                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                    <p class="text-xs text-gray-400 mb-2 flex items-center gap-1.5">
                        <i class="fa-solid fa-align-left"></i> Deskripsi Kelas
                    </p>
                    <p id="modalDeskripsi" class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">-</p>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs text-gray-400 flex items-center gap-1.5">
                            <i class="fa-solid fa-list-ul"></i> Daftar Peserta
                        </p>
                        <div class="relative">
                            <i class="fa-solid fa-search absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-[10px]"></i>
                            <input type="text" id="modalSearchPeserta" oninput="filterModalPeserta()" placeholder="Cari peserta..."
                                class="pl-7 pr-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-300 w-40">
                        </div>
                    </div>
                    <div id="modalPesertaList" class="space-y-2 max-h-52 overflow-y-auto pr-1">
                        <p class="text-sm text-gray-400 italic">Memuat data...</p>
                    </div>
                </div>

            </div>

            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between flex-shrink-0 bg-gray-50 rounded-b-2xl">
                <p id="modalFooterInfo" class="text-xs text-gray-400"></p>
                <button onclick="closeDetailModal()"
                    class="flex items-center gap-1.5 text-xs font-medium px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 border border-gray-200 transition">
                    <i class="fa-solid fa-xmark"></i> Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        const kelasData = @json($kelasJson);

        const hariColorClass = {
            'senin'  : 'bg-blue-50 text-blue-700 border-blue-100',
            'selasa' : 'bg-purple-50 text-purple-700 border-purple-100',
            'rabu'   : 'bg-green-50 text-green-700 border-green-100',
            'kamis'  : 'bg-yellow-50 text-yellow-700 border-yellow-100',
            'jumat'  : 'bg-orange-50 text-orange-700 border-orange-100',
            'sabtu'  : 'bg-pink-50 text-pink-700 border-pink-100',
            'minggu' : 'bg-red-50 text-red-700 border-red-100',
        };

        function openDetailModal(id) {
            const k = kelasData.find(function(x) { return x.id === id; });
            if (!k) return;

            document.getElementById('modalNamaKelas').textContent    = k.nama_kelas;
            document.getElementById('modalGuru').textContent         = k.guru;
            document.getElementById('modalHarga').textContent        = 'Rp ' + Number(k.harga_kelas).toLocaleString('id-ID') + ' /bln';
            document.getElementById('modalJam').textContent          = k.jam_mulai + ' – ' + k.jam_selesai;
            document.getElementById('modalPesertaCount').textContent = k.jumlah_peserta + ' peserta aktif';
            document.getElementById('modalDeskripsi').textContent    = k.deskripsi || 'Tidak ada deskripsi.';
            document.getElementById('modalFooterInfo').textContent   = 'Dibuat: ' + k.created_at;
            document.getElementById('modalSearchPeserta').value      = '';

            const hariContainer = document.getElementById('modalHari');
            hariContainer.innerHTML = '';
            k.hari_kelas.split(',').map(function(h) { return h.trim(); }).forEach(function(hari) {
                const cls = hariColorClass[hari.toLowerCase()] || 'bg-gray-50 text-gray-600 border-gray-200';
                hariContainer.innerHTML += '<span class="text-xs font-semibold px-2.5 py-1 rounded-full border ' + cls + '">' + hari + '</span>';
            });

            renderPesertaModal(k.peserta);

            document.getElementById('modalDetailKelas').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function renderPesertaModal(list) {
            const el = document.getElementById('modalPesertaList');
            if (!list || list.length === 0) {
                el.innerHTML = '<p class="text-sm text-gray-400 italic py-2">Belum ada peserta aktif di kelas ini.</p>';
                return;
            }
            el.innerHTML = list.map(function(p, i) {
                var statusBadge = p.status === 'aktif'
                    ? '<span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-green-50 text-green-700 border border-green-100">Aktif</span>'
                    : '<span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-red-50 text-red-600 border border-red-100">Non-Aktif</span>';

                return '<div class="peserta-item flex items-center gap-3 bg-white rounded-xl border border-gray-100 px-4 py-2.5 hover:bg-gray-50 transition" data-nama="' + p.nama.toLowerCase() + '">'
                    + '<div class="w-7 h-7 rounded-full bg-primary-50 border border-primary-100 flex items-center justify-center flex-shrink-0 text-xs font-bold text-primary-600">' + (i + 1) + '</div>'
                    + '<div class="flex-1 min-w-0">'
                    + '<p class="text-sm font-semibold text-gray-800 truncate">' + p.nama + '</p>'
                    + '<p class="text-xs text-gray-400">' + (p.no_hp || '-') + (p.level ? ' · ' + p.level : '') + '</p>'
                    + '</div>'
                    + statusBadge
                    + '</div>';
            }).join('');
        }

        function filterModalPeserta() {
            const q = document.getElementById('modalSearchPeserta').value.toLowerCase();
            document.querySelectorAll('#modalPesertaList .peserta-item').forEach(function(item) {
                item.style.display = item.dataset.nama.includes(q) ? '' : 'none';
            });
        }

        function closeDetailModal() {
            document.getElementById('modalDetailKelas').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function closeModalOnBackdrop(event) {
            if (event.target === document.getElementById('modalDetailKelas')) {
                closeDetailModal();
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeDetailModal();
        });

        function filterTable() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const hari   = document.getElementById('filterHari').value.toLowerCase();
            document.querySelectorAll('.kelas-card').forEach(function(card) {
                const ok = (card.dataset.nama.includes(search) || card.dataset.hari.includes(search))
                        && (!hari || card.dataset.hari.includes(hari));
                card.style.display = ok ? '' : 'none';
            });
        }
    </script>

@endsection