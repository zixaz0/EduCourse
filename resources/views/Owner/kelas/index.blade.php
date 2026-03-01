@extends('Layout.owner')

@section('content')

    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-800">Data Kelas</h1>
        <p class="text-sm text-gray-500 mt-0.5">Daftar semua kelas beserta peserta yang terdaftar</p>
    </div>

    {{-- Search --}}
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

    {{-- Cards Grid --}}
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

                {{-- Header card --}}
                <div class="bg-primary-700 px-5 py-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-chalkboard-user text-white"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-white font-bold text-sm truncate">{{ $k->nama_kelas }}</p>
                        <p class="text-blue-200 text-xs">Rp {{ number_format($k->harga_kelas, 0, ',', '.') }} / bulan</p>
                    </div>
                </div>

                {{-- Body --}}
                <div class="p-5 space-y-3">
                    {{-- Hari --}}
                    <div class="flex flex-wrap gap-1">
                        @foreach($hariList as $hari)
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full border {{ $hariColor[trim($hari)] ?? 'bg-gray-50 text-gray-600 border-gray-200' }}">
                                {{ $hari }}
                            </span>
                        @endforeach
                    </div>

                    {{-- Jumlah peserta --}}
                    <div class="flex items-center justify-between pt-1">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-users text-gray-400 text-xs"></i>
                            <span class="text-sm text-gray-600"><span class="font-bold text-gray-800">{{ $k->jumlah_peserta ?? 0 }}</span> peserta</span>
                        </div>
                        <button onclick="lihatPeserta({{ $k->id }})"
                            class="flex items-center gap-1.5 text-xs font-semibold text-primary-700 hover:text-primary-900 bg-primary-50 hover:bg-primary-100 px-3 py-1.5 rounded-lg transition">
                            <i class="fa-solid fa-eye text-xs"></i> Lihat Peserta
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


    {{-- Modal Peserta Per Kelas --}}
    <div id="modalPeserta" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 bg-primary-700">
                <div>
                    <h2 id="modal_kelas_nama" class="text-white font-bold text-base">—</h2>
                    <p id="modal_kelas_hari" class="text-blue-200 text-xs mt-0.5">—</p>
                </div>
                <button onclick="closeModal()" class="text-white/70 hover:text-white transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            {{-- Search dalam modal --}}
            <div class="px-5 pt-4">
                <div class="relative">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" id="modalSearch" oninput="filterModalPeserta()" placeholder="Cari nama peserta..."
                        class="w-full pl-8 pr-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-300">
                </div>
            </div>

            <div class="px-5 py-4 max-h-80 overflow-y-auto" id="modalPesertaList">
                <p class="text-center text-gray-400 text-sm py-8">Memuat data...</p>
            </div>

            <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between">
                <p class="text-xs text-gray-400"><span id="modal_jumlah">0</span> peserta terdaftar</p>
                <button onclick="closeModal()"
                    class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- Data peserta per kelas untuk JS --}}
    <script>
        const dataKelas = {
            @foreach($kelas ?? [] as $k)
            {{ $k->id }}: {
                nama: @json($k->nama_kelas),
                hari: @json($k->hari_kelas),
                peserta: [
                    @foreach($k->peserta ?? [] as $p)
                    { nama: @json($p->nama), email: @json($p->email), status: @json(strtolower($p->status ?? 'aktif')) },
                    @endforeach
                ]
            },
            @endforeach
        };

        function lihatPeserta(id) {
            const k = dataKelas[id];
            if (!k) return;

            document.getElementById('modal_kelas_nama').textContent = k.nama;
            document.getElementById('modal_kelas_hari').textContent = k.hari;
            document.getElementById('modal_jumlah').textContent     = k.peserta.length;
            document.getElementById('modalSearch').value = '';

            renderPeserta(k.peserta);
            openModal();
        }

        function renderPeserta(list) {
            const el = document.getElementById('modalPesertaList');
            if (!list.length) {
                el.innerHTML = `<div class="text-center py-10 text-gray-400"><i class="fa-solid fa-users text-3xl mb-2 block text-gray-200"></i><p class="text-sm">Belum ada peserta di kelas ini</p></div>`;
                return;
            }
            el.innerHTML = list.map((p, i) => `
                <div class="flex items-center gap-3 py-2.5 border-b border-gray-50 last:border-0 peserta-item"
                    data-nama="${p.nama.toLowerCase()}">
                    <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                        <span class="text-primary-700 font-bold text-xs">${p.nama.charAt(0).toUpperCase()}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">${p.nama}</p>
                        <p class="text-xs text-gray-400 truncate">${p.email}</p>
                    </div>
                    ${p.status === 'aktif'
                        ? `<span class="inline-flex items-center gap-1 bg-green-50 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full border border-green-100"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif</span>`
                        : `<span class="inline-flex items-center gap-1 bg-red-50 text-red-600 text-xs font-semibold px-2.5 py-1 rounded-full border border-red-100"><span class="w-1.5 h-1.5 rounded-full bg-red-400"></span> Non-Aktif</span>`
                    }
                </div>
            `).join('');
        }

        function filterModalPeserta() {
            const q = document.getElementById('modalSearch').value.toLowerCase();
            document.querySelectorAll('.peserta-item').forEach(item => {
                item.style.display = item.dataset.nama.includes(q) ? '' : 'none';
            });
        }

        function filterTable() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const hari   = document.getElementById('filterHari').value.toLowerCase();
            document.querySelectorAll('.kelas-card').forEach(card => {
                const ok = (card.dataset.nama.includes(search) || card.dataset.hari.includes(search))
                        && (!hari || card.dataset.hari.includes(hari));
                card.style.display = ok ? '' : 'none';
            });
        }

        function openModal()  { const el = document.getElementById('modalPeserta'); el.classList.remove('hidden'); el.classList.add('flex'); }
        function closeModal() { const el = document.getElementById('modalPeserta'); el.classList.add('hidden'); el.classList.remove('flex'); }
        document.getElementById('modalPeserta').addEventListener('click', e => { if (e.target === e.currentTarget) closeModal(); });
    </script>

@endsection