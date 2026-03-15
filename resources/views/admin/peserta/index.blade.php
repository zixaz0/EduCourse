@extends('Layout.admin')

@section('content')

    {{-- Page Title --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Data Peserta</h1>
            <p class="text-sm text-gray-500 mt-0.5">semua data peserta kursus</p>
        </div>
    </div>

    {{-- Search & Filter --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 mb-5 flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari nama atau no. HP..."
                class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent" />
        </div>
        <select id="filterStatus" onchange="filterTable()"
            class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white text-gray-600">
            <option value="">Semua Status</option>
            <option value="aktif">Aktif</option>
            <option value="nonaktif">Non-Aktif</option>
        </select>
        <select id="filterKelas" onchange="filterTable()"
            class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white text-gray-600">
            <option value="">Semua Kelas</option>
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
                        <th class="px-5 py-3.5 font-semibold">Kelas Kursus</th>
                        <th class="px-5 py-3.5 font-semibold">Status</th>
                        <th class="px-5 py-3.5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($peserta ?? [] as $index => $p)
                        <tr class="hover:bg-gray-50 transition peserta-row"
                            data-nama="{{ strtolower($p->nama) }}"
                            data-nohp="{{ $p->no_hp }}"
                            data-status="{{ strtolower($p->status) }}"
                            data-kelas="{{ strtolower($p->kelas->pluck('nama_kelas')->implode(', ')) }}">

                            <td class="px-5 py-3.5 text-gray-400 font-medium text-xs">{{ $index + 1 }}</td>

                            {{-- Nama --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                                        <span class="text-primary-700 font-bold text-xs">{{ strtoupper(substr($p->nama, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $p->nama }}</p>
                                        <p class="text-xs text-gray-400">{{ $p->email }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-5 py-3.5 text-gray-600">{{ $p->no_hp }}</td>

                            <td class="px-5 py-3.5">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($p->kelas as $k)
                                        <span class="bg-primary-50 text-primary-700 text-xs font-medium px-2.5 py-1 rounded-full border border-primary-100">
                                            {{ $k->nama_kelas }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>

                            <td class="px-5 py-3.5">
                                @if(strtolower($p->status) === 'aktif')
                                    <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 text-xs font-semibold px-3 py-1 rounded-full border border-green-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 text-xs font-semibold px-3 py-1 rounded-full border border-red-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span> Non-Aktif
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button onclick="openDetail({{ $p->id }})" title="Detail"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 transition">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </button>
                                    <button onclick="confirmDelete({{ $p->id }}, '{{ $p->nama }}')" title="Hapus"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 hover:bg-red-100 text-red-500 transition">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center text-gray-400">
                                <i class="fa-solid fa-users text-4xl mb-3 block text-gray-200"></i>
                                <p class="font-medium">Belum ada data peserta</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($peserta) && method_exists($peserta, 'hasPages') && $peserta->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between text-sm text-gray-500">
                <span>Menampilkan {{ $peserta->firstItem() }}–{{ $peserta->lastItem() }} dari {{ $peserta->total() }} peserta</span>
                <div>{{ $peserta->links() }}</div>
            </div>
        @endif
    </div>

    {{-- Modal Detail --}}
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
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><p class="text-xs text-gray-400 font-medium">Nama</p><p id="detail_nama" class="font-semibold text-gray-800 mt-0.5">—</p></div>
                    <div><p class="text-xs text-gray-400 font-medium">Email</p><p id="detail_email" class="font-semibold text-gray-800 mt-0.5 break-all">—</p></div>
                    <div><p class="text-xs text-gray-400 font-medium">No. HP</p><p id="detail_nohp" class="font-semibold text-gray-800 mt-0.5">—</p></div>
                    <div><p class="text-xs text-gray-400 font-medium">Status</p><div id="detail_status" class="mt-0.5">—</div></div>
                    <div><p class="text-xs text-gray-400 font-medium">Nama Orang Tua</p><p id="detail_orangtua" class="font-semibold text-gray-800 mt-0.5">—</p></div>
                    <div><p class="text-xs text-gray-400 font-medium">No. HP Orang Tua</p><p id="detail_noorangtua" class="font-semibold text-gray-800 mt-0.5">—</p></div>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-medium mb-1.5">Kelas Kursus</p>
                    <div id="detail_kelas" class="flex flex-wrap gap-1.5"></div>
                </div>
                <div class="flex justify-end pt-1">
                    <button onclick="closeModal('modalDetail')"
                        class="px-4 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Hidden form untuk delete --}}
    <form id="formDelete" method="POST" class="hidden">@csrf @method('DELETE')</form>

    <script>
        const dataPeserta = {
            @foreach($peserta ?? [] as $p)
            {{ $p->id }}: {
                nama: @json($p->nama),
                email: @json($p->email),
                no_hp: @json($p->no_hp),
                status: @json(strtolower($p->status)),
                nama_orangtua: @json($p->nama_orangtua ?? '-'),
                no_orangtua: @json($p->no_orangtua ?? '-'),
                kelas: [@foreach($p->kelas as $k) @json($k->nama_kelas), @endforeach],
                edit_url: null,
            },
            @endforeach
        };

        function openModal(id) { const el = document.getElementById(id); el.classList.remove('hidden'); el.classList.add('flex'); }
        function closeModal(id) { const el = document.getElementById(id); el.classList.add('hidden'); el.classList.remove('flex'); }
        document.getElementById('modalDetail').addEventListener('click', e => { if (e.target === e.currentTarget) closeModal('modalDetail'); });

        function openDetail(id) {
            const p = dataPeserta[id]; if (!p) return;
            document.getElementById('detail_avatar').textContent     = p.nama.charAt(0).toUpperCase();
            document.getElementById('detail_nama').textContent       = p.nama;
            document.getElementById('detail_email').textContent      = p.email;
            document.getElementById('detail_nohp').textContent       = p.no_hp;
            document.getElementById('detail_orangtua').textContent   = p.nama_orangtua;
            document.getElementById('detail_noorangtua').textContent = p.no_orangtua;
            document.getElementById('detail_status').innerHTML = p.status === 'aktif'
                ? `<span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 text-xs font-semibold px-3 py-1 rounded-full border border-green-100"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif</span>`
                : `<span class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 text-xs font-semibold px-3 py-1 rounded-full border border-red-100"><span class="w-1.5 h-1.5 rounded-full bg-red-400"></span> Non-Aktif</span>`;
            document.getElementById('detail_kelas').innerHTML = p.kelas.length
                ? p.kelas.map(k => `<span class="bg-primary-50 text-primary-700 text-xs font-medium px-2.5 py-1 rounded-full border border-primary-100">${k}</span>`).join('')
                : '<span class="text-gray-400 text-sm">Belum ada kelas</span>';
            openModal('modalDetail');
        }

        function filterTable() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const status = document.getElementById('filterStatus').value.toLowerCase();
            const kelas  = document.getElementById('filterKelas').value.toLowerCase();
            document.querySelectorAll('.peserta-row').forEach(row => {
                const ok = (row.dataset.nama.includes(search) || row.dataset.nohp.includes(search))
                        && (!status || row.dataset.status === status)
                        && (!kelas  || row.dataset.kelas.includes(kelas));
                row.style.display = ok ? '' : 'none';
            });
        }

        function confirmDelete(id, nama) {
            Swal.fire({
                title: 'Hapus Peserta?',
                html: `Data <b>${nama}</b> akan dihapus permanen.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
            }).then(result => {
                if (result.isConfirmed) {
                    const form = document.getElementById('formDelete');
                    form.action = `/admin/peserta/${id}`;
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