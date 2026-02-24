@extends('Layout.admin')

@section('content')

    {{-- Page Title --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Data Kelas</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola semua kelas kursus yang tersedia</p>
        </div>
        <a href="{{ url('/admin/kelas/add') }}"
            class="flex items-center gap-2 bg-primary-700 hover:bg-primary-800 text-white text-sm font-medium px-4 py-2.5 rounded-xl shadow transition">
            <i class="fa-solid fa-plus"></i> Tambah Kelas
        </a>
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
                        <th class="px-5 py-3.5 font-semibold">Hari</th>
                        <th class="px-5 py-3.5 font-semibold">Harga</th>
                        <th class="px-5 py-3.5 font-semibold">Jumlah Peserta</th>
                        <th class="px-5 py-3.5 font-semibold">Dibuat</th>
                        <th class="px-5 py-3.5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($kelas ?? [] as $index => $k)
                        <tr class="hover:bg-gray-50 transition kelas-row"
                            data-nama="{{ strtolower($k->nama_kelas) }}"
                            data-hari="{{ strtolower($k->hari_kelas) }}">

                            <td class="px-5 py-4 text-gray-400 font-medium text-xs">{{ $index + 1 }}</td>

                            {{-- Nama Kelas --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-primary-50 flex items-center justify-center flex-shrink-0 border border-primary-100">
                                        <i class="fa-solid fa-chalkboard-user text-primary-600 text-sm"></i>
                                    </div>
                                    <p class="font-semibold text-gray-800">{{ $k->nama_kelas }}</p>
                                </div>
                            </td>

                            {{-- Hari --}}
                            <td class="px-5 py-4">
                                @php
                                    $hariList = collect(explode(',', $k->hari_kelas))->map(fn($h) => trim($h));
                                    $hariColor = [
                                        'senin'  => 'bg-blue-50 text-blue-700 border-blue-100',
                                        'selasa' => 'bg-purple-50 text-purple-700 border-purple-100',
                                        'rabu'   => 'bg-green-50 text-green-700 border-green-100',
                                        'kamis'  => 'bg-yellow-50 text-yellow-700 border-yellow-100',
                                        'jumat'  => 'bg-orange-50 text-orange-700 border-orange-100',
                                        'sabtu'  => 'bg-pink-50 text-pink-700 border-pink-100',
                                        'minggu' => 'bg-red-50 text-red-700 border-red-100',
                                    ];
                                @endphp
                                <div class="flex flex-wrap gap-1">
                                    @foreach($hariList as $hari)
                                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full border {{ $hariColor[strtolower($hari)] ?? 'bg-gray-50 text-gray-600 border-gray-200' }}">
                                            {{ ucfirst($hari) }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>

                            {{-- Harga --}}
                            <td class="px-5 py-4">
                                <span class="font-semibold text-gray-800">
                                    Rp {{ number_format($k->harga_kelas, 0, ',', '.') }}
                                </span>
                                <span class="text-xs text-gray-400">/bulan</span>
                            </td>

                            {{-- Jumlah Peserta --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-gray-800">{{ $k->jumlah_peserta ?? 0 }}</span>
                                    <span class="text-xs text-gray-400">peserta</span>
                                </div>
                            </td>

                            {{-- Dibuat --}}
                            <td class="px-5 py-4 text-gray-400 text-xs">
                                {{ \Carbon\Carbon::parse($k->created_at)->format('d M Y') }}
                            </td>

                            {{-- Aksi --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ url('/admin/kelas/' . $k->id . '/edit') }}" title="Edit"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-yellow-50 hover:bg-yellow-100 text-yellow-600 transition">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    <button onclick="confirmDelete({{ $k->id }}, '{{ $k->nama_kelas }}')" title="Hapus"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 hover:bg-red-100 text-red-500 transition">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center text-gray-400">
                                <i class="fa-solid fa-chalkboard-user text-4xl mb-3 block text-gray-200"></i>
                                <p class="font-medium">Belum ada data kelas</p>
                                <p class="text-xs mt-1">Klik "Tambah Kelas" untuk menambahkan kelas baru</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($kelas) && method_exists($kelas, 'hasPages') && $kelas->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between text-sm text-gray-500">
                <span>Menampilkan {{ $kelas->firstItem() }}–{{ $kelas->lastItem() }} dari {{ $kelas->total() }} kelas</span>
                <div>{{ $kelas->links() }}</div>
            </div>
        @endif
    </div>

    {{-- Hidden form delete --}}
    <form id="formDelete" method="POST" class="hidden">@csrf @method('DELETE')</form>

    <script>
        function filterTable() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const hari   = document.getElementById('filterHari').value.toLowerCase();
            document.querySelectorAll('.kelas-row').forEach(row => {
                const matchSearch = row.dataset.nama.includes(search) || row.dataset.hari.includes(search);
                const matchHari   = !hari || row.dataset.hari.includes(hari);
                row.style.display = (matchSearch && matchHari) ? '' : 'none';
            });
        }

        function confirmDelete(id, nama) {
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
                    form.action = `/admin/kelas/${id}`;
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