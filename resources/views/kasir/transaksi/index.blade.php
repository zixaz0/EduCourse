@extends('Layout.kasir')

@section('content')

    {{-- Page Title --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Transaksi</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola tagihan dan pembayaran peserta</p>
        </div>
    </div>

    {{-- Search & Filter --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 mb-5 flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari nama peserta..."
                class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent" />
        </div>
        <select id="filterBulan" onchange="filterTable()"
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
        <select id="filterKelas" onchange="filterTable()"
            class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white text-gray-600">
            <option value="">Semua Kelas</option>
            @foreach($kelasList ?? [] as $kelas)
                <option value="{{ strtolower($kelas->nama_kelas) }}">{{ $kelas->nama_kelas }}</option>
            @endforeach
        </select>
        <select id="filterStatus" onchange="filterTable()"
            class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white text-gray-600">
            <option value="">Semua Status</option>
            <option value="belum lunas">Belum Lunas</option>
            <option value="lunas">Lunas</option>
        </select>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-primary-700 text-white text-left">
                        <th class="px-5 py-3.5 font-semibold">No</th>
                        <th class="px-5 py-3.5 font-semibold">Nama Peserta</th>
                        <th class="px-5 py-3.5 font-semibold">Kursus</th>
                        <th class="px-5 py-3.5 font-semibold">Bulan/Tahun</th>
                        <th class="px-5 py-3.5 font-semibold">Total Tagihan</th>
                        <th class="px-5 py-3.5 font-semibold">Status</th>
                        <th class="px-5 py-3.5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tagihan ?? [] as $index => $t)
                        <tr class="hover:bg-gray-50 transition tagihan-row"
                            data-nama="{{ strtolower($t->peserta->nama ?? '') }}"
                            data-bulan="{{ strtolower(explode('/', $t->bulan_tahun)[0] ?? '') }}"
                            data-kelas="{{ strtolower($t->peserta->kelas->pluck('nama_kelas')->implode(', ') ?? '') }}"
                            data-status="{{ strtolower($t->status) }}">

                            <td class="px-5 py-3.5 text-gray-500 font-medium">{{ $index + 1 }}</td>

                            {{-- Nama Peserta --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                                        <span class="text-primary-700 font-bold text-xs">
                                            {{ strtoupper(substr($t->peserta->nama ?? 'P', 0, 1)) }}
                                        </span>
                                    </div>
                                    <p class="font-semibold text-gray-800">{{ $t->peserta->nama ?? '-' }}</p>
                                </div>
                            </td>

                            {{-- Kursus --}}
                            <td class="px-5 py-3.5">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($t->peserta->kelas ?? [] as $k)
                                        <span class="bg-primary-50 text-primary-700 text-xs font-medium px-2.5 py-1 rounded-full border border-primary-100">
                                            {{ $k->nama_kelas }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>

                            {{-- Bulan/Tahun --}}
                            <td class="px-5 py-3.5 text-gray-600 font-medium">{{ $t->bulan_tahun }}</td>

                            {{-- Total Tagihan --}}
                            <td class="px-5 py-3.5 font-semibold text-gray-800">
                                Rp {{ number_format($t->total_tagihan, 0, ',', '.') }}
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-3.5">
                                @if(strtolower($t->status) === 'lunas')
                                    <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 text-xs font-semibold px-3 py-1 rounded-full border border-green-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Lunas
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 text-xs font-semibold px-3 py-1 rounded-full border border-red-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span> Belum Lunas
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-1.5">
                                    @if(strtolower($t->status) !== 'lunas')
                                        {{-- Bayar --}}
                                        <a href="{{ url('/kasir/transaksi/' . $t->id . '/bayar') }}"
                                            title="Bayar"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-green-50 hover:bg-green-100 text-green-600 transition">
                                            <i class="fa-solid fa-money-bill-wave text-xs"></i>
                                        </a>
                                        {{-- Hapus --}}
                                        <button onclick="confirmDelete('{{ $t->peserta->nama ?? '-' }}')"
                                            title="Hapus"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 hover:bg-red-100 text-red-500 transition">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    @else
                                        <span class="text-xs text-gray-300 px-2">—</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center text-gray-400">
                                <i class="fa-solid fa-receipt text-4xl mb-3 block text-gray-200"></i>
                                <p class="font-medium">Belum ada data tagihan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if(isset($tagihan) && method_exists($tagihan, 'hasPages') && $tagihan->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between text-sm text-gray-500">
                <span>Menampilkan {{ $tagihan->firstItem() }}–{{ $tagihan->lastItem() }} dari {{ $tagihan->total() }} tagihan</span>
                <div>{{ $tagihan->links() }}</div>
            </div>
        @endif
    </div>


    {{-- ==================== MODAL FORM BAYAR ==================== --}}
    <div id="modalBayar" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 bg-primary-700">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                        <i class="fa-solid fa-money-bill-wave text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="text-white font-bold text-sm">Form Pembayaran</p>
                        <p class="text-blue-200 text-xs" id="modal_bulan_label">—</p>
                    </div>
                </div>
                <button onclick="closeModal('modalBayar')" class="text-white/70 hover:text-white transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            {{-- Info Tagihan --}}
            <div class="mx-6 mt-5 bg-primary-50 border border-primary-100 rounded-xl p-4 space-y-2.5">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500 font-medium">Nama Peserta</span>
                    <span id="modal_nama" class="font-semibold text-gray-800 text-right max-w-[55%]">—</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500 font-medium">Kursus</span>
                    <span id="modal_kursus" class="font-semibold text-gray-800 text-right max-w-[55%]">—</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500 font-medium">Periode</span>
                    <span id="modal_periode" class="font-semibold text-gray-800">—</span>
                </div>
                <hr class="border-primary-100">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500 font-medium">Total Tagihan</span>
                    <span id="modal_total" class="text-base font-bold text-primary-700">—</span>
                </div>
            </div>

            {{-- Form --}}
            <div class="px-6 py-5 space-y-4">

                {{-- Nomor Unik --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Nomor Unik <span class="text-gray-400 font-normal">(otomatis)</span>
                    </label>
                    <div class="relative">
                        <input type="text" id="input_nomor_unik" readonly
                            class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 bg-gray-50 text-gray-500 font-mono focus:outline-none cursor-not-allowed">
                        <button onclick="regenNomorUnik()"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-primary-600 hover:text-primary-800 transition text-xs font-medium">
                            <i class="fa-solid fa-rotate-right"></i>
                        </button>
                    </div>
                </div>

                {{-- Uang Bayar --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Uang Bayar <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">Rp</span>
                        <input type="number" id="input_uang_bayar" oninput="hitungKembali()"
                            placeholder="0" min="0"
                            class="w-full text-sm border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent transition">
                    </div>
                </div>

                {{-- Uang Kembali --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Uang Kembali</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">Rp</span>
                        <input type="text" id="input_uang_kembali" readonly
                            value="0"
                            class="w-full text-sm border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 bg-gray-50 text-gray-600 font-semibold focus:outline-none cursor-not-allowed">
                    </div>
                    <p id="kembali_warning" class="text-xs text-red-500 mt-1 hidden">
                        <i class="fa-solid fa-triangle-exclamation mr-1"></i>Uang bayar kurang dari total tagihan!
                    </p>
                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-end gap-3 pt-1">
                    <button type="button" onclick="closeModal('modalBayar')"
                        class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                        Batal
                    </button>
                    <button type="button" id="btn_bayar" onclick="submitBayar()"
                        class="px-6 py-2.5 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-xl shadow transition flex items-center gap-2">
                        <i class="fa-solid fa-check"></i> Konfirmasi Bayar
                    </button>
                </div>
            </div>
        </div>
    </div>


    <script>
        // ===== State modal =====
        let currentTagihanId = null;
        let currentTotal     = 0;

        // ===== Modal Helpers =====
        function openModal(id) {
            const el = document.getElementById(id);
            el.classList.remove('hidden');
            el.classList.add('flex');
        }
        function closeModal(id) {
            const el = document.getElementById(id);
            el.classList.add('hidden');
            el.classList.remove('flex');
        }
        document.getElementById('modalBayar').addEventListener('click', function(e) {
            if (e.target === this) closeModal('modalBayar');
        });

        // ===== Generate Nomor Unik =====
        function generateNomorUnik() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let result = 'TRX-';
            for (let i = 0; i < 8; i++) {
                result += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            return result;
        }
        function regenNomorUnik() {
            document.getElementById('input_nomor_unik').value = generateNomorUnik();
        }

        // ===== Buka Modal Bayar =====
        function openBayar(id, nama, bulanTahun, total, kursus) {
            currentTagihanId = id;
            currentTotal     = total;

            document.getElementById('modal_nama').textContent    = nama;
            document.getElementById('modal_kursus').textContent  = kursus;
            document.getElementById('modal_periode').textContent = bulanTahun;
            document.getElementById('modal_bulan_label').textContent = 'Tagihan ' + bulanTahun;
            document.getElementById('modal_total').textContent   = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('input_nomor_unik').value    = generateNomorUnik();
            document.getElementById('input_uang_bayar').value    = '';
            document.getElementById('input_uang_kembali').value  = '0';
            document.getElementById('kembali_warning').classList.add('hidden');

            openModal('modalBayar');
            setTimeout(() => document.getElementById('input_uang_bayar').focus(), 100);
        }

        // ===== Hitung Uang Kembali =====
        function hitungKembali() {
            const bayar   = parseInt(document.getElementById('input_uang_bayar').value) || 0;
            const kembali = bayar - currentTotal;
            const warning = document.getElementById('kembali_warning');
            const btnBayar = document.getElementById('btn_bayar');

            if (bayar > 0 && kembali < 0) {
                document.getElementById('input_uang_kembali').value = '0';
                warning.classList.remove('hidden');
                btnBayar.disabled = true;
                btnBayar.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                document.getElementById('input_uang_kembali').value = kembali.toLocaleString('id-ID');
                warning.classList.add('hidden');
                btnBayar.disabled = false;
                btnBayar.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }

        // ===== Submit Bayar =====
        function submitBayar() {
            const bayar = parseInt(document.getElementById('input_uang_bayar').value) || 0;
            if (bayar <= 0) {
                Swal.fire({ icon: 'warning', title: 'Isi Uang Bayar', text: 'Masukkan jumlah uang bayar terlebih dahulu.', confirmButtonColor: '#1e5399' });
                return;
            }
            if (bayar < currentTotal) return;

            const nomorUnik = document.getElementById('input_nomor_unik').value;
            const kembali   = bayar - currentTotal;

            Swal.fire({
                title: 'Konfirmasi Pembayaran?',
                html: `
                    <div class="text-left space-y-2 text-sm">
                        <div class="flex justify-between"><span class="text-gray-500">Nomor Unik</span><span class="font-mono font-semibold">${nomorUnik}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Uang Bayar</span><span class="font-semibold">Rp ${bayar.toLocaleString('id-ID')}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Uang Kembali</span><span class="font-semibold text-green-600">Rp ${kembali.toLocaleString('id-ID')}</span></div>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fa-solid fa-check mr-1"></i> Ya, Bayar',
                cancelButtonText: 'Batal',
            }).then(result => {
                if (result.isConfirmed) {
                    // Sambungkan ke controller di sini
                    Swal.fire({ icon: 'success', title: 'Pembayaran Berhasil!', text: `Kembalian: Rp ${kembali.toLocaleString('id-ID')}`, confirmButtonColor: '#1e5399' })
                        .then(() => closeModal('modalBayar'));
                }
            });
        }

        // ===== Search & Filter =====
        function filterTable() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const bulan  = document.getElementById('filterBulan').value.toLowerCase();
            const kelas  = document.getElementById('filterKelas').value.toLowerCase();
            const status = document.getElementById('filterStatus').value.toLowerCase();

            document.querySelectorAll('.tagihan-row').forEach(row => {
                const matchSearch = row.dataset.nama.includes(search);
                const matchBulan  = !bulan  || row.dataset.bulan === bulan;
                const matchKelas  = !kelas  || row.dataset.kelas.includes(kelas);
                const matchStatus = !status || row.dataset.status === status;
                row.style.display = (matchSearch && matchBulan && matchKelas && matchStatus) ? '' : 'none';
            });
        }

        // ===== Hapus =====
        function confirmDelete(nama) {
            Swal.fire({
                title: 'Hapus Tagihan?',
                html: `Tagihan <b>${nama}</b> akan dihapus permanen.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
            });
        }

        // ===== Flash messages =====
        @if(session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session("success") }}', confirmButtonColor: '#1e5399' });
        @endif
        @if(session('error'))
            Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session("error") }}', confirmButtonColor: '#1e5399' });
        @endif
    </script>

@endsection