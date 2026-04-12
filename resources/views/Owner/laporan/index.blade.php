@extends('Layout.owner')

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Laporan Transaksi</h1>
            <p class="text-sm text-gray-500 mt-0.5">Rekap seluruh transaksi pembayaran kursus</p>
        </div>
        <button onclick="exportPDF()"
            class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium px-4 py-2.5 rounded-xl shadow transition">
            <i class="fa-solid fa-file-pdf"></i> Export PDF
        </button>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 mb-5">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari nama peserta atau no. unik..."
                    class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent" />
            </div>
            <select id="filterKasir" onchange="filterTable()"
                class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white text-gray-600">
                <option value="">Semua Kasir</option>
                @foreach($kasirList ?? [] as $kasir)
                    <option value="{{ strtolower($kasir->username) }}">{{ $kasir->username }}</option>
                @endforeach
            </select>
            <select id="filterKelas" onchange="filterTable()"
                class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white text-gray-600">
                <option value="">Semua Kelas</option>
                @foreach($kelasList ?? [] as $k)
                    <option value="{{ strtolower($k->nama_kelas) }}">{{ $k->nama_kelas }}</option>
                @endforeach
            </select>
            <select id="filterBulan" onchange="filterTable()"
                class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white text-gray-600">
                <option value="">Semua Bulan</option>
                <option value="januari">Januari</option><option value="februari">Februari</option>
                <option value="maret">Maret</option><option value="april">April</option>
                <option value="mei">Mei</option><option value="juni">Juni</option>
                <option value="juli">Juli</option><option value="agustus">Agustus</option>
                <option value="september">September</option><option value="oktober">Oktober</option>
                <option value="november">November</option><option value="desember">Desember</option>
            </select>
            <select id="filterTahun" onchange="filterTable()"
                class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white text-gray-600">
                <option value="">Semua Tahun</option>
                <option value="2024">2024</option><option value="2025">2025</option><option value="2026">2026</option>
            </select>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-primary-50 border border-primary-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-list-check text-primary-600 text-sm"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">Total Transaksi</p>
                <p id="summaryTotal" class="text-lg font-bold text-gray-800">{{ count($transaksi ?? []) }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-green-50 border border-green-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-money-bill-wave text-green-600 text-sm"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">Total Pemasukan</p>
                <p id="summaryPemasukan" class="text-lg font-bold text-gray-800">
                    Rp {{ number_format(collect($transaksi ?? [])->sum('total_tagihan'), 0, ',', '.') }}
                </p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-users text-blue-600 text-sm"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">Peserta Bayar</p>
                <p id="summaryPeserta" class="text-lg font-bold text-gray-800">
                    {{ collect($transaksi ?? [])->pluck('peserta')->unique()->count() }}
                </p>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden" id="tabelLaporan">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-primary-700 text-white text-left">
                        <th class="px-5 py-3.5 font-semibold">No</th>
                        <th class="px-5 py-3.5 font-semibold">No. Unik</th>
                        <th class="px-5 py-3.5 font-semibold">Nama Peserta</th>
                        <th class="px-4 py-3.5 font-semibold w-40">Kelas</th>
                        <th class="px-5 py-3.5 font-semibold">Periode</th>
                        <th class="px-5 py-3.5 font-semibold">Total</th>
                        <th class="px-5 py-3.5 font-semibold">Uang Bayar</th>
                        <th class="px-5 py-3.5 font-semibold">Kembalian</th>
                        <th class="px-5 py-3.5 font-semibold">Kasir</th>
                        <th class="px-5 py-3.5 font-semibold">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="tableBody">
                    @forelse($transaksi ?? [] as $index => $t)
                        <tr class="hover:bg-gray-50 transition laporan-row"
                            data-peserta="{{ strtolower($t->peserta ?? '') }}"
                            data-nomor="{{ strtolower($t->nomor_unik ?? '') }}"
                            data-kasir="{{ strtolower($t->kasir ?? '') }}"
                            data-kelas="{{ strtolower($t->kelas ?? '') }}"
                            data-bulan="{{ strtolower(explode('/', $t->bulan_tahun ?? '/')[0]) }}"
                            data-tahun="{{ explode('/', $t->bulan_tahun ?? '/')[1] ?? '' }}">

                            <td class="px-5 py-3.5 text-gray-400 text-xs font-medium">{{ $index + 1 }}</td>
                            <td class="px-5 py-3.5">
                                <span class="font-mono text-xs bg-gray-100 text-gray-700 px-2.5 py-1 rounded-lg font-semibold">{{ $t->nomor_unik }}</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                                        <span class="text-primary-700 text-xs font-bold">{{ strtoupper(substr($t->peserta ?? 'P', 0, 1)) }}</span>
                                    </div>
                                    <span class="font-semibold text-gray-800">{{ $t->peserta ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 max-w-[160px]">
                                <div class="flex flex-wrap gap-1">
                                    @foreach(explode(',', $t->kelas ?? '-') as $k)
                                        <span class="bg-primary-50 text-primary-700 text-xs font-medium px-2 py-0.5 rounded-md border border-primary-100 whitespace-nowrap">{{ trim($k) }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-gray-600 text-xs font-medium">{{ $t->bulan_tahun ?? '-' }}</td>
                            <td class="px-5 py-3.5 font-bold text-gray-800">Rp {{ number_format($t->total_tagihan ?? 0, 0, ',', '.') }}</td>
                            <td class="px-5 py-3.5 font-semibold text-green-700">Rp {{ number_format($t->uang_bayar ?? 0, 0, ',', '.') }}</td>
                            <td class="px-5 py-3.5 font-semibold text-blue-600">Rp {{ number_format($t->uang_kembali ?? 0, 0, ',', '.') }}</td>
                            <td class="px-5 py-3.5 text-xs text-gray-500">{{ $t->kasir ?? '-' }}</td>
                            <td class="px-5 py-3.5 text-xs text-gray-400">{{ \Carbon\Carbon::parse($t->created_at)->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-5 py-16 text-center text-gray-400">
                                <i class="fa-solid fa-file-chart-column text-4xl mb-3 block text-gray-200"></i>
                                <p class="font-medium">Belum ada data transaksi</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Template PDF (hidden) --}}
    <div id="pdf-template" class="hidden">
        <div id="pdf-content" style="font-family:'Poppins',sans-serif;padding:30px;background:white;min-width:900px;">
            {{-- Header --}}
            <div style="display:flex;justify-content:space-between;align-items:center;border-bottom:3px solid #1e5399;padding-bottom:15px;margin-bottom:20px;">
                <div>
                    <h1 style="font-size:22px;font-weight:700;color:#1e5399;margin:0;">EduCourse</h1>
                    <p style="font-size:12px;color:#666;margin:3px 0;">Lembaga Kursus & Pelatihan</p>
                </div>
                <div style="text-align:right;">
                    <h2 style="font-size:16px;font-weight:700;color:#333;margin:0;">LAPORAN TRANSAKSI</h2>
                    <p id="pdf_periode" style="font-size:11px;color:#666;margin:3px 0;">—</p>
                    <p id="pdf_dicetak" style="font-size:10px;color:#999;margin:0;">—</p>
                </div>
            </div>

            {{-- Summary --}}
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:20px;">
                <div style="background:#f0f5ff;border:1px solid #c5d5ec;border-radius:10px;padding:12px;">
                    <p style="font-size:11px;color:#666;margin:0 0 4px;">Total Transaksi</p>
                    <p id="pdf_total_trx" style="font-size:18px;font-weight:700;color:#1e5399;margin:0;">0</p>
                </div>
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:12px;">
                    <p style="font-size:11px;color:#666;margin:0 0 4px;">Total Pemasukan</p>
                    <p id="pdf_total_nominal" style="font-size:18px;font-weight:700;color:#16a34a;margin:0;">Rp 0</p>
                </div>
                <div style="background:#fefce8;border:1px solid #fde68a;border-radius:10px;padding:12px;">
                    <p style="font-size:11px;color:#666;margin:0 0 4px;">Peserta Bayar</p>
                    <p id="pdf_total_peserta" style="font-size:18px;font-weight:700;color:#d97706;margin:0;">0</p>
                </div>
            </div>

            {{-- Table --}}
            <table style="width:100%;border-collapse:collapse;font-size:11px;">
                <thead>
                    <tr style="background:#1e5399;color:white;">
                        <th style="padding:8px 10px;text-align:left;font-weight:600;">No</th>
                        <th style="padding:8px 10px;text-align:left;font-weight:600;">No. Unik</th>
                        <th style="padding:8px 10px;text-align:left;font-weight:600;">Peserta</th>
                        <th style="padding:8px 10px;text-align:left;font-weight:600;">Kelas</th>
                        <th style="padding:8px 10px;text-align:left;font-weight:600;">Periode</th>
                        <th style="padding:8px 10px;text-align:right;font-weight:600;">Total</th>
                        <th style="padding:8px 10px;text-align:left;font-weight:600;">Kasir</th>
                        <th style="padding:8px 10px;text-align:left;font-weight:600;">Tanggal</th>
                    </tr>
                </thead>
                <tbody id="pdf-table-body"></tbody>
            </table>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function filterTable() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const kasir  = document.getElementById('filterKasir').value.toLowerCase();
            const kelas  = document.getElementById('filterKelas').value.toLowerCase();
            const bulan  = document.getElementById('filterBulan').value.toLowerCase();
            const tahun  = document.getElementById('filterTahun').value;

            let visibleRows = 0;
            let totalPemasukan = 0;
            const pesertaSet = new Set();

            document.querySelectorAll('.laporan-row').forEach(row => {
                const ok = (row.dataset.peserta.includes(search) || row.dataset.nomor.includes(search))
                        && (!kasir || row.dataset.kasir.includes(kasir))
                        && (!kelas || row.dataset.kelas.includes(kelas))
                        && (!bulan || row.dataset.bulan === bulan)
                        && (!tahun || row.dataset.tahun === tahun);
                row.style.display = ok ? '' : 'none';
                if (ok) {
                    visibleRows++;
                    // Ambil total dari cell ke-6 (index 5)
                    const totalText = row.cells[5]?.textContent.replace(/[^0-9]/g, '') || '0';
                    totalPemasukan += parseInt(totalText);
                    pesertaSet.add(row.dataset.peserta);
                }
            });

            document.getElementById('summaryTotal').textContent     = visibleRows;
            document.getElementById('summaryPemasukan').textContent = 'Rp ' + totalPemasukan.toLocaleString('id-ID');
            document.getElementById('summaryPeserta').textContent   = pesertaSet.size;
        }

        function exportPDF() {
            // Kumpulkan baris yang visible
            const rows = [...document.querySelectorAll('.laporan-row')].filter(r => r.style.display !== 'none');

            if (!rows.length) {
                Swal.fire({ icon:'warning', title:'Tidak Ada Data', text:'Tidak ada data yang bisa diexport.', confirmButtonColor:'#1e5399' });
                return;
            }

            // Isi summary PDF
            const total   = rows.length;
            let pemasukan = 0;
            const pSet    = new Set();
            rows.forEach(r => {
                pemasukan += parseInt(r.cells[5]?.textContent.replace(/[^0-9]/g, '') || 0);
                pSet.add(r.dataset.peserta);
            });

            // Filter info untuk periode
            const bulan = document.getElementById('filterBulan').value;
            const tahun = document.getElementById('filterTahun').value;
            const periodeText = [bulan, tahun].filter(Boolean).join(' ') || 'Semua Periode';

            document.getElementById('pdf_periode').textContent      = 'Periode: ' + periodeText;
            document.getElementById('pdf_dicetak').textContent      = 'Dicetak: ' + new Date().toLocaleString('id-ID');
            document.getElementById('pdf_total_trx').textContent    = total;
            document.getElementById('pdf_total_nominal').textContent = 'Rp ' + pemasukan.toLocaleString('id-ID');
            document.getElementById('pdf_total_peserta').textContent = pSet.size;

            // Isi tabel PDF
            document.getElementById('pdf-table-body').innerHTML = rows.map((row, i) => `
                <tr style="background:${i % 2 === 0 ? '#fff' : '#f8faff'};">
                    <td style="padding:7px 10px;border-bottom:1px solid #e5e7eb;">${i + 1}</td>
                    <td style="padding:7px 10px;border-bottom:1px solid #e5e7eb;font-family:monospace;">${row.cells[1]?.textContent.trim()}</td>
                    <td style="padding:7px 10px;border-bottom:1px solid #e5e7eb;font-weight:600;">${row.cells[2]?.textContent.trim()}</td>
                    <td style="padding:7px 10px;border-bottom:1px solid #e5e7eb;">${row.cells[3]?.textContent.trim()}</td>
                    <td style="padding:7px 10px;border-bottom:1px solid #e5e7eb;">${row.cells[4]?.textContent.trim()}</td>
                    <td style="padding:7px 10px;border-bottom:1px solid #e5e7eb;text-align:right;font-weight:700;color:#1e5399;">${row.cells[5]?.textContent.trim()}</td>
                    <td style="padding:7px 10px;border-bottom:1px solid #e5e7eb;">${row.cells[8]?.textContent.trim()}</td>
                    <td style="padding:7px 10px;border-bottom:1px solid #e5e7eb;color:#999;">${row.cells[9]?.textContent.trim()}</td>
                </tr>
            `).join('');

            const filename = `Laporan-Transaksi-${periodeText.replace(/\s+/g,'-')}-${Date.now()}.pdf`;

            Swal.fire({ title:'Membuat Laporan PDF...', text:'Mohon tunggu sebentar', allowOutsideClick:false, didOpen:() => Swal.showLoading() });

            html2pdf().set({
                margin:      [10, 10, 10, 10],
                filename:    filename,
                image:       { type:'jpeg', quality:0.98 },
                html2canvas: { scale:1.5, useCORS:true },
                jsPDF:       { unit:'mm', format:'a4', orientation:'landscape' }
            }).from(document.getElementById('pdf-content')).save().then(() => {
                Swal.fire({ icon:'success', title:'Laporan Berhasil Diexport!', text:`File: ${filename}`, confirmButtonColor:'#1e5399', timer:3000, timerProgressBar:true });
            });
        }
    </script>

@endsection