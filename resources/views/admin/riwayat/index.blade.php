@extends('Layout.admin')

@section('content')

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
                <p class="text-xl font-bold text-gray-800">{{ $totalTransaksi }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-green-50 border border-green-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-money-bill-wave text-green-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium">Total Pemasukan</p>
                <p class="text-xl font-bold text-gray-800">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-calendar-day text-blue-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium">Transaksi Hari Ini</p>
                <p class="text-xl font-bold text-gray-800">{{ $transaksiHariIni }}</p>
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
                @foreach(['januari','februari','maret','april','mei','juni','juli','agustus','september','oktober','november','desember'] as $bln)
                    <option value="{{ $bln }}">{{ ucfirst($bln) }}</option>
                @endforeach
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
                    @forelse($riwayat as $index => $r)
                        @php
                            // Ambil kelas dari snapshot (frozen), fallback ke relasi peserta
                            $kelasTampil = [];
                            if (!empty($r->tagihan->kelas_snapshot)) {
                                $kelasTampil = is_array($r->tagihan->kelas_snapshot)
                                    ? $r->tagihan->kelas_snapshot
                                    : json_decode($r->tagihan->kelas_snapshot, true) ?? [];
                            } elseif ($r->tagihan && $r->tagihan->peserta) {
                                $kelasTampil = $r->tagihan->peserta->kelas->pluck('nama_kelas')->toArray();
                            }
                            $namaKursus = implode(', ', $kelasTampil);
                            $bulanTahun  = $r->tagihan->bulan_tahun ?? '';
                            // support format "MM-YYYY" atau "bulanname/YYYY"
                            $parts       = explode('-', $bulanTahun);
                            $bulanAngka  = $parts[0] ?? '';
                            $tahunAngka  = $parts[1] ?? '';
                            $bulanMap    = [
                                '01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April',
                                '05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus',
                                '09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember',
                            ];
                            $periodeLabel = ($bulanMap[$bulanAngka] ?? $bulanAngka) . ' / ' . $tahunAngka;
                            $kasirNama    = $r->user->username ?? $r->user->name ?? '-';
                        @endphp
                        <tr class="hover:bg-gray-50 transition riwayat-row"
                            data-nama="{{ strtolower($r->tagihan->peserta->nama ?? '') }}"
                            data-nomor="{{ strtolower($r->nomor_unik ?? '') }}"
                            data-kasir="{{ strtolower($kasirNama) }}"
                            data-kursus="{{ strtolower($namaKursus) }}"
                            data-bulan="{{ strtolower($bulanMap[$bulanAngka] ?? $bulanAngka) }}"
                            data-tahun="{{ $tahunAngka }}">

                            <td class="px-5 py-3.5 text-gray-400 font-medium text-xs">{{ $riwayat->firstItem() + $index }}</td>

                            <td class="px-5 py-3.5">
                                <span class="font-mono text-xs bg-gray-100 text-gray-700 px-2.5 py-1 rounded-lg font-semibold">
                                    {{ $r->nomor_unik }}
                                </span>
                            </td>

                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                                        <span class="text-primary-700 font-bold text-xs">
                                            {{ strtoupper(substr($r->tagihan->peserta->nama ?? 'P', 0, 1)) }}
                                        </span>
                                    </div>
                                    <span class="font-semibold text-gray-800">{{ $r->tagihan->peserta->nama ?? '-' }}</span>
                                </div>
                            </td>

                            <td class="px-5 py-3.5">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($kelasTampil as $namaKelas)
                                        <span class="bg-primary-50 text-primary-700 text-xs font-medium px-2 py-0.5 rounded-full border border-primary-100">
                                            {{ $namaKelas }}
                                        </span>
                                    @empty
                                        <span class="text-gray-400 text-xs">-</span>
                                    @endforelse
                                </div>
                            </td>

                            <td class="px-5 py-3.5 text-gray-600 font-medium">{{ $periodeLabel }}</td>
                            <td class="px-5 py-3.5 font-semibold text-gray-800">Rp {{ number_format($r->tagihan->total_tagihan ?? 0, 0, ',', '.') }}</td>
                            <td class="px-5 py-3.5 font-semibold text-green-700">Rp {{ number_format($r->uang_bayar ?? 0, 0, ',', '.') }}</td>
                            <td class="px-5 py-3.5 font-semibold text-blue-600">Rp {{ number_format($r->uang_kembalian ?? 0, 0, ',', '.') }}</td>

                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-5 h-5 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                                        <span class="text-primary-700 font-bold" style="font-size:9px;">
                                            {{ strtoupper(substr($kasirNama, 0, 1)) }}
                                        </span>
                                    </div>
                                    <span class="text-xs text-gray-600 font-medium">{{ $kasirNama }}</span>
                                </div>
                            </td>

                            {{-- Aksi: Cetak Struk — pakai data-* attribute, TIDAK pakai @json() di onclick --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center">
                                    <button
                                        onclick="cetakStruk(this)"
                                        data-nomor="{{ $r->nomor_unik ?? '' }}"
                                        data-nama="{{ $r->tagihan->peserta->nama ?? '-' }}"
                                        data-kursus="{{ $namaKursus ?: '-' }}"
                                        data-periode="{{ $periodeLabel }}"
                                        data-total="{{ $r->tagihan->total_tagihan ?? 0 }}"
                                        data-bayar="{{ $r->uang_bayar ?? 0 }}"
                                        data-kembali="{{ $r->uang_kembalian ?? 0 }}"
                                        data-kasir="{{ $kasirNama }}"
                                        data-tanggal="{{ $r->created_at ?? now() }}"
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

        {{-- ===== PAGINATION ===== --}}
        @if($riwayat->total() > 0)
        <div class="px-5 py-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-sm text-gray-500">
            <div class="flex items-center gap-2">
                <span>Menampilkan {{ $riwayat->firstItem() }}–{{ $riwayat->lastItem() }} dari {{ $riwayat->total() }} transaksi. Tampilkan</span>
                <select onchange="changePerPage(this.value)"
                    class="border border-gray-200 rounded-lg px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white">
                    @foreach([5, 10, 25, 50] as $opt)
                        <option value="{{ $opt }}" {{ $perPage == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
                <span>data</span>
            </div>
            <div class="flex items-center gap-1">
                @if($riwayat->onFirstPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 cursor-not-allowed text-xs">«</span>
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 cursor-not-allowed text-xs">‹</span>
                @else
                    <a href="{{ $riwayat->url(1) }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500 transition text-xs">«</a>
                    <a href="{{ $riwayat->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500 transition text-xs">‹</a>
                @endif

                @php $current = $riwayat->currentPage(); $last = $riwayat->lastPage(); $start = max(1, $current-1); $end = min($last, $current+1); @endphp

                @if($start > 1)
                    <a href="{{ $riwayat->url(1) }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-600 transition text-xs">1</a>
                    @if($start > 2)<span class="w-8 h-8 flex items-center justify-center text-gray-400 text-xs">…</span>@endif
                @endif

                @for($page = $start; $page <= $end; $page++)
                    @if($page == $current)
                        <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-primary-700 text-white font-semibold text-xs">{{ $page }}</span>
                    @else
                        <a href="{{ $riwayat->url($page) }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-600 transition text-xs">{{ $page }}</a>
                    @endif
                @endfor

                @if($end < $last)
                    @if($end < $last - 1)<span class="w-8 h-8 flex items-center justify-center text-gray-400 text-xs">…</span>@endif
                    <a href="{{ $riwayat->url($last) }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-600 transition text-xs">{{ $last }}</a>
                @endif

                @if($riwayat->hasMorePages())
                    <a href="{{ $riwayat->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500 transition text-xs">›</a>
                    <a href="{{ $riwayat->url($riwayat->lastPage()) }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500 transition text-xs">»</a>
                @else
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 cursor-not-allowed text-xs">›</span>
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 cursor-not-allowed text-xs">»</span>
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- jsPDF CDN --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
        function changePerPage(val) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', val);
            url.searchParams.set('page', 1);
            window.location.href = url.toString();
        }

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
            return 'Rp ' + Number(angka).toLocaleString('id-ID');
        }

        function formatTanggal(dateStr) {
            const d = new Date(dateStr);
            if (isNaN(d)) return dateStr;
            return d.toLocaleDateString('id-ID', {
                day: '2-digit', month: 'long', year: 'numeric',
                hour: '2-digit', minute: '2-digit'
            });
        }

        function cetakStruk(btn) {
            const d = btn.dataset;
            const { jsPDF } = window.jspdf;

            const doc = new jsPDF({ unit: 'mm', format: [80, 160], orientation: 'portrait' });
            const W = 80;
            let y = 8;

            const lineCenter = (text, size = 9, bold = false) => {
                doc.setFontSize(size);
                doc.setFont('courier', bold ? 'bold' : 'normal');
                doc.text(text, W / 2, y, { align: 'center' });
                y += size * 0.45;
            };
            const lineRow = (label, value, size = 8, boldValue = false) => {
                doc.setFontSize(size);
                doc.setFont('courier', 'normal');
                doc.text(label, 5, y);
                doc.setFont('courier', boldValue ? 'bold' : 'normal');
                doc.text(value, W - 5, y, { align: 'right' });
                y += size * 0.45;
            };
            const dashedLine = () => {
                doc.setLineDashPattern([1, 1], 0);
                doc.setDrawColor(0);
                doc.line(5, y, W - 5, y);
                y += 4;
            };
            const solidLine = () => {
                doc.setLineDashPattern([], 0);
                doc.setDrawColor(0);
                doc.line(5, y, W - 5, y);
                y += 4;
            };

            // HEADER
            lineCenter('EduCourse', 13, true);
            y += 1;
            lineCenter('Lembaga Kursus & Pelatihan', 7);
            y += 1;
            lineCenter('BUKTI PEMBAYARAN', 10, true);
            y += 2;
            dashedLine();

            // INFO TRANSAKSI
            lineRow('No. Unik', d.nomor, 8, true);
            y += 1;
            lineRow('Tanggal', formatTanggal(d.tanggal), 7);
            y += 1;
            lineRow('Kasir', d.kasir, 7);
            y += 2;
            dashedLine();

            // DATA PESERTA
            lineRow('Peserta', d.nama, 8, true);
            y += 1;

            // Kursus — wrap jika panjang
            doc.setFontSize(7);
            doc.setFont('courier', 'normal');
            doc.text('Kursus', 5, y);
            const kursusLines = doc.splitTextToSize(d.kursus, 40);
            if (kursusLines.length === 1) {
                doc.text(kursusLines[0], W - 5, y, { align: 'right' });
                y += 4;
            } else {
                y += 4;
                kursusLines.forEach(line => {
                    doc.text(line, W - 5, y, { align: 'right' });
                    y += 3.5;
                });
            }

            y += 1;
            lineRow('Periode', d.periode, 7);
            y += 2;
            dashedLine();

            // PEMBAYARAN
            lineRow('Total Tagihan', rupiah(d.total), 8);
            y += 1;
            lineRow('Uang Bayar', rupiah(d.bayar), 8);
            y += 1;
            solidLine();
            lineRow('Kembalian', rupiah(d.kembali), 9, true);
            y += 3;

            // FOOTER
            dashedLine();
            lineCenter('Terima kasih atas kepercayaan Anda', 7);
            y += 1;
            lineCenter('Struk ini merupakan bukti pembayaran sah', 7);
            y += 2;
            lineCenter('Dicetak: ' + new Date().toLocaleString('id-ID'), 6);

            doc.save('Struk-' + d.nomor + '.pdf');

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Struk Berhasil Diunduh!',
                    text: 'File: Struk-' + d.nomor + '.pdf',
                    confirmButtonColor: '#1e5399',
                    timer: 3000,
                    timerProgressBar: true,
                });
            }
        }
    </script>

@endsection