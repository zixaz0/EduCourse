@extends('Layout.admin')

@section('content')

    {{-- Page Title --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Riwayat Transaksi</h1>
            <p class="text-sm text-gray-500 mt-0.5">Semua riwayat pembayaran dari seluruh kasir</p>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-primary-50 border border-primary-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-clock-rotate-left text-primary-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium">Total Transaksi</p>
                <p class="text-xl font-bold text-gray-800">{{ count($riwayat ?? []) }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-green-50 border border-green-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-money-bill-wave text-green-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium">Total Pemasukan</p>
                <p class="text-xl font-bold text-gray-800">
                    Rp {{ number_format(collect($riwayat ?? [])->sum('tagihan.total_tagihan'), 0, ',', '.') }}
                </p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-calendar-day text-blue-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium">Transaksi Hari Ini</p>
                <p class="text-xl font-bold text-gray-800">
                    {{ collect($riwayat ?? [])->filter(fn($r) => \Carbon\Carbon::parse($r->created_at)->isToday())->count() }}
                </p>
            </div>
        </div>
    </div>

    {{-- Search & Filter --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 mb-5">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari nama peserta atau no. unik..."
                    class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent" />
            </div>
            {{-- Filter Kasir (khusus admin) --}}
            <select id="filterKasir" onchange="filterTable()"
                class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white text-gray-600">
                <option value="">Semua Kasir</option>
                @foreach($kasirList ?? [] as $kasir)
                    <option value="{{ strtolower($kasir->username) }}">{{ $kasir->username }}</option>
                @endforeach
            </select>
            <select id="filterKursus" onchange="filterTable()"
                class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white text-gray-600">
                <option value="">Semua Kursus</option>
                @foreach($kelasList ?? [] as $kelas)
                    <option value="{{ strtolower($kelas->nama_kelas) }}">{{ $kelas->nama_kelas }}</option>
                @endforeach
            </select>
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
            <select id="filterTahun" onchange="filterTable()"
                class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white text-gray-600">
                <option value="">Semua Tahun</option>
                <option value="2024">2024</option>
                <option value="2025">2025</option>
                <option value="2026">2026</option>
            </select>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-primary-700 text-white text-left">
                        <th class="px-5 py-3.5 font-semibold">No</th>
                        <th class="px-5 py-3.5 font-semibold">No. Unik</th>
                        <th class="px-5 py-3.5 font-semibold">Nama Peserta</th>
                        <th class="px-5 py-3.5 font-semibold">Kursus</th>
                        <th class="px-5 py-3.5 font-semibold">Bulan/Tahun</th>
                        <th class="px-5 py-3.5 font-semibold">Total Tagihan</th>
                        <th class="px-5 py-3.5 font-semibold">Uang Bayar</th>
                        <th class="px-5 py-3.5 font-semibold">Kembalian</th>
                        <th class="px-5 py-3.5 font-semibold">Kasir</th>
                        <th class="px-5 py-3.5 font-semibold text-center">Struk</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($riwayat ?? [] as $index => $r)
                        <tr class="hover:bg-gray-50 transition riwayat-row"
                            data-nama="{{ strtolower($r->peserta->nama ?? '') }}"
                            data-nomor="{{ strtolower($r->nomor_unik ?? '') }}"
                            data-kasir="{{ strtolower($r->user->username ?? '') }}"
                            data-kursus="{{ strtolower($r->tagihan->peserta->kelas->pluck('nama_kelas')->implode(', ') ?? '') }}"
                            data-bulan="{{ strtolower(explode('/', $r->tagihan->bulan_tahun ?? '/')[0]) }}"
                            data-tahun="{{ explode('/', $r->tagihan->bulan_tahun ?? '/')[1] ?? '' }}">

                            <td class="px-5 py-3.5 text-gray-400 font-medium text-xs">{{ $index + 1 }}</td>

                            {{-- No Unik --}}
                            <td class="px-5 py-3.5">
                                <span class="font-mono text-xs bg-gray-100 text-gray-700 px-2.5 py-1 rounded-lg font-semibold">
                                    {{ $r->nomor_unik }}
                                </span>
                            </td>

                            {{-- Nama --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                                        <span class="text-primary-700 font-bold text-xs">
                                            {{ strtoupper(substr($r->peserta->nama ?? 'P', 0, 1)) }}
                                        </span>
                                    </div>
                                    <span class="font-semibold text-gray-800">{{ $r->peserta->nama ?? '-' }}</span>
                                </div>
                            </td>

                            {{-- Kursus --}}
                            <td class="px-5 py-3.5">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($r->tagihan->peserta->kelas ?? [] as $k)
                                        <span class="bg-primary-50 text-primary-700 text-xs font-medium px-2 py-0.5 rounded-full border border-primary-100">
                                            {{ $k->nama_kelas }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>

                            {{-- Bulan/Tahun --}}
                            <td class="px-5 py-3.5 text-gray-600 font-medium">
                                {{ $r->tagihan->bulan_tahun ?? '-' }}
                            </td>

                            {{-- Total Tagihan --}}
                            <td class="px-5 py-3.5 font-semibold text-gray-800">
                                Rp {{ number_format($r->tagihan->total_tagihan ?? 0, 0, ',', '.') }}
                            </td>

                            {{-- Uang Bayar --}}
                            <td class="px-5 py-3.5 font-semibold text-green-700">
                                Rp {{ number_format($r->uang_bayar ?? 0, 0, ',', '.') }}
                            </td>

                            {{-- Kembalian --}}
                            <td class="px-5 py-3.5 font-semibold text-blue-600">
                                Rp {{ number_format($r->uang_kembali ?? 0, 0, ',', '.') }}
                            </td>

                            {{-- Kasir --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-5 h-5 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                                        <span class="text-primary-700 font-bold" style="font-size:9px;">
                                            {{ strtoupper(substr($r->user->username ?? 'K', 0, 1)) }}
                                        </span>
                                    </div>
                                    <span class="text-xs text-gray-600 font-medium">{{ $r->user->username ?? '-' }}</span>
                                </div>
                            </td>

                            {{-- Struk PDF --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center">
                                    <button
                                        onclick="cetakStruk(
                                            @json($r->nomor_unik),
                                            @json($r->peserta->nama ?? '-'),
                                            @json($r->tagihan->peserta->kelas->pluck('nama_kelas')->implode(', ') ?? '-'),
                                            @json($r->tagihan->bulan_tahun ?? '-'),
                                            {{ $r->tagihan->total_tagihan ?? 0 }},
                                            {{ $r->uang_bayar ?? 0 }},
                                            {{ $r->uang_kembali ?? 0 }},
                                            @json($r->user->username ?? '-'),
                                            @json($r->created_at ?? now())
                                        )"
                                        title="Cetak Struk PDF"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 hover:bg-red-100 text-red-500 transition">
                                        <i class="fa-solid fa-file-pdf text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-5 py-16 text-center text-gray-400">
                                <i class="fa-solid fa-clock-rotate-left text-4xl mb-3 block text-gray-200"></i>
                                <p class="font-medium">Belum ada riwayat transaksi</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($riwayat) && method_exists($riwayat, 'hasPages') && $riwayat->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between text-sm text-gray-500">
                <span>Menampilkan {{ $riwayat->firstItem() }}–{{ $riwayat->lastItem() }} dari {{ $riwayat->total() }} transaksi</span>
                <div>{{ $riwayat->links() }}</div>
            </div>
        @endif
    </div>


    {{-- Struk Template (hidden) --}}
    <div id="struk-print" class="hidden">
        <div id="struk-content" style="font-family:'Courier New',monospace;width:300px;padding:20px;background:white;font-size:12px;line-height:1.6;">
            <div style="text-align:center;border-bottom:1px dashed #000;padding-bottom:10px;margin-bottom:10px;">
                <p style="font-size:16px;font-weight:bold;margin:0;">EduCourse</p>
                <p style="margin:2px 0;font-size:11px;">Lembaga Kursus & Pelatihan</p>
                <p style="margin:2px 0;font-size:10px;color:#555;">Jl. Pendidikan No. 1, Indonesia</p>
                <p style="margin:4px 0;font-size:13px;font-weight:bold;letter-spacing:2px;">BUKTI PEMBAYARAN</p>
            </div>
            <div style="margin-bottom:10px;">
                <table style="width:100%;font-size:11px;border-collapse:collapse;">
                    <tr><td style="width:45%;padding:1px 0;color:#555;">No. Unik</td><td style="padding:1px 0;">: <span id="struk_nomor" style="font-weight:bold;"></span></td></tr>
                    <tr><td style="padding:1px 0;color:#555;">Tanggal</td><td style="padding:1px 0;">: <span id="struk_tanggal"></span></td></tr>
                    <tr><td style="padding:1px 0;color:#555;">Kasir</td><td style="padding:1px 0;">: <span id="struk_kasir"></span></td></tr>
                </table>
            </div>
            <div style="border-top:1px dashed #000;border-bottom:1px dashed #000;padding:8px 0;margin-bottom:10px;">
                <table style="width:100%;font-size:11px;border-collapse:collapse;">
                    <tr><td style="padding:1px 0;color:#555;">Peserta</td><td style="padding:1px 0;text-align:right;font-weight:bold;"><span id="struk_nama"></span></td></tr>
                    <tr><td style="padding:1px 0;color:#555;">Kursus</td><td style="padding:1px 0;text-align:right;"><span id="struk_kursus"></span></td></tr>
                    <tr><td style="padding:1px 0;color:#555;">Periode</td><td style="padding:1px 0;text-align:right;"><span id="struk_periode"></span></td></tr>
                </table>
            </div>
            <div style="margin-bottom:10px;">
                <table style="width:100%;font-size:11px;border-collapse:collapse;">
                    <tr><td style="padding:2px 0;color:#555;">Total Tagihan</td><td style="padding:2px 0;text-align:right;"><span id="struk_total"></span></td></tr>
                    <tr><td style="padding:2px 0;color:#555;">Uang Bayar</td><td style="padding:2px 0;text-align:right;"><span id="struk_bayar"></span></td></tr>
                    <tr style="border-top:1px solid #000;">
                        <td style="padding:4px 0;font-weight:bold;font-size:12px;">Kembalian</td>
                        <td style="padding:4px 0;text-align:right;font-weight:bold;font-size:12px;"><span id="struk_kembali"></span></td>
                    </tr>
                </table>
            </div>
            <div style="text-align:center;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:6px;padding:6px;margin-bottom:12px;">
                <span style="color:#16a34a;font-weight:bold;font-size:12px;">✓ LUNAS</span>
            </div>
            <div style="text-align:center;border-top:1px dashed #000;padding-top:10px;font-size:10px;color:#555;">
                <p style="margin:2px 0;">Terima kasih atas kepercayaan Anda</p>
                <p style="margin:2px 0;">Struk ini merupakan bukti pembayaran sah</p>
                <p style="margin:4px 0;font-size:9px;">Dicetak: <span id="struk_dicetak"></span></p>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function filterTable() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const kasir  = document.getElementById('filterKasir').value.toLowerCase();
            const kursus = document.getElementById('filterKursus').value.toLowerCase();
            const bulan  = document.getElementById('filterBulan').value.toLowerCase();
            const tahun  = document.getElementById('filterTahun').value;

            document.querySelectorAll('.riwayat-row').forEach(row => {
                const ok = (row.dataset.nama.includes(search) || row.dataset.nomor.includes(search))
                        && (!kasir  || row.dataset.kasir.includes(kasir))
                        && (!kursus || row.dataset.kursus.includes(kursus))
                        && (!bulan  || row.dataset.bulan === bulan)
                        && (!tahun  || row.dataset.tahun === tahun);
                row.style.display = ok ? '' : 'none';
            });
        }

        function rupiah(angka) {
            return 'Rp ' + angka.toLocaleString('id-ID');
        }

        function formatTanggal(dateStr) {
            const d = new Date(dateStr);
            if (isNaN(d)) return dateStr;
            return d.toLocaleDateString('id-ID', { day:'2-digit', month:'long', year:'numeric', hour:'2-digit', minute:'2-digit' });
        }

        function cetakStruk(nomor, nama, kursus, periode, total, bayar, kembali, kasir, tanggal) {
            document.getElementById('struk_nomor').textContent   = nomor;
            document.getElementById('struk_tanggal').textContent = formatTanggal(tanggal);
            document.getElementById('struk_kasir').textContent   = kasir;
            document.getElementById('struk_nama').textContent    = nama;
            document.getElementById('struk_kursus').textContent  = kursus;
            document.getElementById('struk_periode').textContent = periode;
            document.getElementById('struk_total').textContent   = rupiah(total);
            document.getElementById('struk_bayar').textContent   = rupiah(bayar);
            document.getElementById('struk_kembali').textContent = rupiah(kembali);
            document.getElementById('struk_dicetak').textContent = new Date().toLocaleString('id-ID');

            const opt = {
                margin:      [5,5,5,5],
                filename:    `Struk-${nomor}.pdf`,
                image:       { type:'jpeg', quality:0.98 },
                html2canvas: { scale:2, useCORS:true },
                jsPDF:       { unit:'mm', format:[80,160], orientation:'portrait' }
            };

            Swal.fire({ title:'Membuat PDF...', text:'Mohon tunggu sebentar', allowOutsideClick:false, didOpen:() => Swal.showLoading() });

            html2pdf().set(opt).from(document.getElementById('struk-content')).save().then(() => {
                Swal.fire({ icon:'success', title:'Struk Berhasil Diunduh!', text:`File: Struk-${nomor}.pdf`, confirmButtonColor:'#1e5399', timer:3000, timerProgressBar:true });
            });
        }
    </script>

@endsection