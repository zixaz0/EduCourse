@extends('Layout.admin')

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Data Kelas</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola semua kelas kursus yang tersedia</p>
        </div>
        <a href="{{ route('admin.kelas.add') }}"
            class="flex items-center gap-2 bg-primary-700 hover:bg-primary-800 text-white text-sm font-medium px-4 py-2.5 rounded-xl shadow transition">
            <i class="fa-solid fa-plus"></i> Tambah Kelas
        </a>
    </div>

    {{-- Search & Filter --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 mb-5 flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari nama kelas, guru, atau hari..."
                class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent" />
        </div>
        <select id="filterHari" onchange="filterTable()"
            class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white text-gray-600">
            <option value="">Semua Hari</option>
            <option value="senin">Senin</option>
            <option value="selasa">Selasa</option>
            <option value="rabu">Rabu</option>
            <option value="kamis">Kamis</option>
            <option value="jumat">Jumat</option>
            <option value="sabtu">Sabtu</option>
            <option value="minggu">Minggu</option>
        </select>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-primary-700 text-white text-left">
                        <th class="px-5 py-3.5 font-semibold">No</th>
                        <th class="px-5 py-3.5 font-semibold">Nama Kelas</th>
                        <th class="px-5 py-3.5 font-semibold">Guru</th>
                        <th class="px-5 py-3.5 font-semibold">Hari</th>
                        <th class="px-5 py-3.5 font-semibold">Jam</th>
                        <th class="px-5 py-3.5 font-semibold">Harga</th>
                        <th class="px-5 py-3.5 font-semibold">Peserta Aktif</th>
                        <th class="px-5 py-3.5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($kelas as $index => $k)
                        @php
                            $hariColor = [
                                'senin'  => 'bg-blue-50 text-blue-700 border-blue-100',
                                'selasa' => 'bg-purple-50 text-purple-700 border-purple-100',
                                'rabu'   => 'bg-green-50 text-green-700 border-green-100',
                                'kamis'  => 'bg-yellow-50 text-yellow-700 border-yellow-100',
                                'jumat'  => 'bg-orange-50 text-orange-700 border-orange-100',
                                'sabtu'  => 'bg-pink-50 text-pink-700 border-pink-100',
                                'minggu' => 'bg-red-50 text-red-700 border-red-100',
                            ];
                            $hariList = collect(explode(',', $k->hari_kelas))->map(fn($h) => trim($h));
                        @endphp
                        <tr class="hover:bg-gray-50 transition kelas-row"
                            data-nama="{{ strtolower($k->nama_kelas) }}"
                            data-guru="{{ strtolower($k->guru->nama ?? '') }}"
                            data-hari="{{ strtolower($k->hari_kelas) }}">

                            {{-- No --}}
                            <td class="px-5 py-4 text-gray-400 font-medium text-xs">
                                {{ $kelas->firstItem() + $index }}
                            </td>

                            {{-- Nama Kelas --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-primary-50 flex items-center justify-center flex-shrink-0 border border-primary-100">
                                        <i class="fa-solid fa-chalkboard-user text-primary-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $k->nama_kelas }}</p>
                                        @if($k->deskripsi)
                                            <p class="text-xs text-gray-400 mt-0.5">{{ Str::limit($k->deskripsi, 45) }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Guru --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
                                        <i class="fa-solid fa-chalkboard-user text-gray-400 text-xs"></i>
                                    </div>
                                    <span class="text-gray-700 font-medium">{{ $k->guru->nama ?? '-' }}</span>
                                </div>
                            </td>

                            {{-- Hari --}}
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($hariList as $hari)
                                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full border {{ $hariColor[strtolower($hari)] ?? 'bg-gray-50 text-gray-600 border-gray-200' }}">
                                            {{ ucfirst($hari) }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>

                            {{-- Jam --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-1.5 text-gray-700">
                                    <i class="fa-solid fa-clock text-gray-300 text-xs"></i>
                                    <span class="font-medium">{{ $k->jam_mulai }}</span>
                                    <span class="text-gray-300">–</span>
                                    <span class="font-medium">{{ $k->jam_selesai }}</span>
                                </div>
                            </td>

                            {{-- Harga --}}
                            <td class="px-5 py-4">
                                <span class="font-semibold text-gray-800">Rp {{ number_format($k->harga_kelas, 0, ',', '.') }}</span>
                                <span class="text-xs text-gray-400">/bln</span>
                            </td>

                            {{-- Peserta Aktif --}}
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full border
                                    {{ ($k->jumlah_peserta ?? 0) > 0 ? 'bg-green-50 text-green-700 border-green-100' : 'bg-gray-50 text-gray-400 border-gray-200' }}">
                                    <i class="fa-solid fa-users text-[10px]"></i>
                                    {{ $k->jumlah_peserta ?? 0 }} peserta
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-1.5">
                                    {{-- Tombol View --}}
                                    <button onclick="openDetailModal({{ $k->id }})" title="Lihat Detail"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-500 border border-blue-100 transition">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </button>
                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('admin.kelas.edit', $k->id) }}" title="Edit"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-yellow-50 hover:bg-yellow-100 text-yellow-500 border border-yellow-100 transition">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    {{-- Tombol Hapus --}}
                                    <button onclick="confirmDelete({{ $k->id }}, '{{ addslashes($k->nama_kelas) }}', '{{ route('admin.kelas.destroy', $k->id) }}')" title="Hapus"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 hover:bg-red-100 text-red-500 border border-red-100 transition">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-16 text-center text-gray-400">
                                <i class="fa-solid fa-chalkboard-user text-4xl mb-3 block text-gray-200"></i>
                                <p class="font-medium">Belum ada data kelas</p>
                                <p class="text-xs mt-1">Klik "Tambah Kelas" untuk menambahkan kelas baru</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($kelas->total() > 0)
        <div class="px-5 py-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-sm text-gray-500">

            {{-- Kiri: info + per page --}}
            <div class="flex items-center gap-2">
                <span>Menampilkan {{ $kelas->firstItem() }}–{{ $kelas->lastItem() }} dari {{ $kelas->total() }} kelas. Tampilkan</span>
                <select onchange="changePerPage(this.value)"
                    class="border border-gray-200 rounded-lg px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white">
                    @foreach([5, 10, 25, 50] as $opt)
                        <option value="{{ $opt }}" {{ $perPage == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
                <span>data</span>
            </div>

            {{-- Kanan: navigasi halaman --}}
            <div class="flex items-center gap-1">
                @if($kelas->onFirstPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 cursor-not-allowed text-xs">«</span>
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 cursor-not-allowed text-xs">‹</span>
                @else
                    <a href="{{ $kelas->url(1) }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500 transition text-xs">«</a>
                    <a href="{{ $kelas->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500 transition text-xs">‹</a>
                @endif

                @php
                    $current = $kelas->currentPage();
                    $last    = $kelas->lastPage();
                    $start   = max(1, $current - 1);
                    $end     = min($last, $current + 1);
                @endphp

                @if($start > 1)
                    <a href="{{ $kelas->url(1) }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-600 transition text-xs">1</a>
                    @if($start > 2)<span class="w-8 h-8 flex items-center justify-center text-gray-400 text-xs">…</span>@endif
                @endif

                @for($page = $start; $page <= $end; $page++)
                    @if($page == $current)
                        <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-primary-700 text-white font-semibold text-xs">{{ $page }}</span>
                    @else
                        <a href="{{ $kelas->url($page) }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-600 transition text-xs">{{ $page }}</a>
                    @endif
                @endfor

                @if($end < $last)
                    @if($end < $last - 1)<span class="w-8 h-8 flex items-center justify-center text-gray-400 text-xs">…</span>@endif
                    <a href="{{ $kelas->url($last) }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-600 transition text-xs">{{ $last }}</a>
                @endif

                @if($kelas->hasMorePages())
                    <a href="{{ $kelas->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500 transition text-xs">›</a>
                    <a href="{{ $kelas->url($kelas->lastPage()) }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500 transition text-xs">»</a>
                @else
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 cursor-not-allowed text-xs">›</span>
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 cursor-not-allowed text-xs">»</span>
                @endif
            </div>

        </div>
        @endif
    </div>

    {{-- ===================== MODAL DETAIL KELAS ===================== --}}
    <div id="modalDetailKelas"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden"
        onclick="closeModalOnBackdrop(event)">

        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] flex flex-col overflow-hidden">

            {{-- Modal Header --}}
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

            {{-- Modal Body --}}
            <div class="overflow-y-auto flex-1 p-6 space-y-5">

                {{-- Info Grid --}}
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

                {{-- Hari --}}
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                    <p class="text-xs text-gray-400 mb-2 flex items-center gap-1.5">
                        <i class="fa-solid fa-calendar-days"></i> Hari Kelas
                    </p>
                    <div id="modalHari" class="flex flex-wrap gap-1.5"></div>
                </div>

                {{-- Deskripsi --}}
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                    <p class="text-xs text-gray-400 mb-2 flex items-center gap-1.5">
                        <i class="fa-solid fa-align-left"></i> Deskripsi Kelas
                    </p>
                    <p id="modalDeskripsi" class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">-</p>
                </div>

                {{-- Daftar Peserta --}}
                <div>
                    <p class="text-xs text-gray-400 mb-2 flex items-center gap-1.5">
                        <i class="fa-solid fa-list-ul"></i> Daftar Peserta Aktif
                    </p>
                    <div id="modalPesertaList" class="space-y-2 max-h-52 overflow-y-auto pr-1">
                        <p class="text-sm text-gray-400 italic">Memuat data...</p>
                    </div>
                </div>

            </div>

            {{-- Modal Footer --}}
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between flex-shrink-0 bg-gray-50 rounded-b-2xl">
                <p id="modalFooterInfo" class="text-xs text-gray-400"></p>
                <div class="flex gap-2">
                    <a id="modalEditBtn" href="#"
                        class="flex items-center gap-1.5 text-xs font-medium px-4 py-2 rounded-xl bg-yellow-50 hover:bg-yellow-100 text-yellow-600 border border-yellow-100 transition">
                        <i class="fa-solid fa-pen"></i> Edit Kelas
                    </a>
                    <button onclick="closeDetailModal()"
                        class="flex items-center gap-1.5 text-xs font-medium px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 border border-gray-200 transition">
                        <i class="fa-solid fa-xmark"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- ===================== END MODAL ===================== --}}

    {{-- Data Kelas untuk JS (embed JSON) --}}
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
            const k = kelasData.find(x => x.id === id);
            if (!k) return;

            // Isi field
            document.getElementById('modalNamaKelas').textContent    = k.nama_kelas;
            document.getElementById('modalGuru').textContent         = k.guru;
            document.getElementById('modalHarga').textContent        = 'Rp ' + Number(k.harga_kelas).toLocaleString('id-ID') + ' /bln';
            document.getElementById('modalJam').textContent          = k.jam_mulai + ' – ' + k.jam_selesai;
            document.getElementById('modalPesertaCount').textContent = k.jumlah_peserta + ' peserta aktif';
            document.getElementById('modalDeskripsi').textContent    = k.deskripsi || 'Tidak ada deskripsi.';
            document.getElementById('modalFooterInfo').textContent   = 'Dibuat: ' + k.created_at;
            document.getElementById('modalEditBtn').href             = k.edit_url;

            // Hari badges
            const hariContainer = document.getElementById('modalHari');
            hariContainer.innerHTML = '';
            k.hari_kelas.split(',').map(h => h.trim()).forEach(hari => {
                const cls = hariColorClass[hari.toLowerCase()] || 'bg-gray-50 text-gray-600 border-gray-200';
                hariContainer.innerHTML += `<span class="text-xs font-semibold px-2.5 py-1 rounded-full border ${cls}">${hari}</span>`;
            });

            // Daftar peserta
            const listEl = document.getElementById('modalPesertaList');
            if (k.peserta.length === 0) {
                listEl.innerHTML = `<p class="text-sm text-gray-400 italic py-2">Belum ada peserta aktif di kelas ini.</p>`;
            } else {
                listEl.innerHTML = k.peserta.map((p, i) => `
                    <div class="flex items-center gap-3 bg-white rounded-xl border border-gray-100 px-4 py-2.5 hover:bg-gray-50 transition">
                        <div class="w-7 h-7 rounded-full bg-primary-50 border border-primary-100 flex items-center justify-center flex-shrink-0 text-xs font-bold text-primary-600">
                            ${i + 1}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate">${p.nama}</p>
                            <p class="text-xs text-gray-400">${p.no_hp || '-'} ${p.level ? '· ' + p.level : ''}</p>
                        </div>
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-green-50 text-green-700 border border-green-100">Aktif</span>
                    </div>
                `).join('');
            }

            // Tampilkan modal
            document.getElementById('modalDetailKelas').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
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

        // Tutup modal dengan Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeDetailModal();
        });
    </script>

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
            const hari   = document.getElementById('filterHari').value.toLowerCase();
            document.querySelectorAll('.kelas-row').forEach(row => {
                const matchSearch = row.dataset.nama.includes(search)
                                 || row.dataset.guru.includes(search)
                                 || row.dataset.hari.includes(search);
                const matchHari   = !hari || row.dataset.hari.includes(hari);
                row.style.display = (matchSearch && matchHari) ? '' : 'none';
            });
        }

        function confirmDelete(id, nama, url) {
            Swal.fire({
                title: 'Hapus Kelas?',
                html: `Kelas <b>${nama}</b> akan dihapus permanen.<br><span class="text-xs text-gray-500">Pastikan tidak ada peserta aktif di kelas ini.</span>`,
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