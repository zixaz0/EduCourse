@extends('Layout.kasir')

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Riwayat Transaksi</h1>
            <p class="text-sm text-gray-500 mt-0.5">Riwayat semua transaksi pembayaran peserta</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 mb-5 flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari nama peserta atau no. unik..."
                class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent" />
        </div>
        <select id="filterKursus" onchange="filterTable()"
            class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white text-gray-600">
            <option value="">Semua Kursus</option>
            @foreach ($kelasList ?? [] as $kelas)
                <option value="{{ strtolower($kelas->nama_kelas) }}">{{ $kelas->nama_kelas }}</option>
            @endforeach
        </select>
        <select id="filterBulan" onchange="filterTable()"
            class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white text-gray-600">
            <option value="">Semua Bulan</option>
            <option value="01">Januari</option>
            <option value="02">Februari</option>
            <option value="03">Maret</option>
            <option value="04">April</option>
            <option value="05">Mei</option>
            <option value="06">Juni</option>
            <option value="07">Juli</option>
            <option value="08">Agustus</option>
            <option value="09">September</option>
            <option value="10">Oktober</option>
            <option value="11">November</option>
            <option value="12">Desember</option>
        </select>
        <select id="filterTahun" onchange="filterTable()"
            class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white text-gray-600">
            <option value="">Semua Tahun</option>
            <option value="2024">2024</option>
            <option value="2025">2025</option>
            <option value="2026">2026</option>
        </select>
    </div>

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
                        <th class="px-5 py-3.5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($riwayat ?? [] as $index => $r)
                        @php
                            $parts = explode('-', $r->tagihan->bulan_tahun ?? '');
                            $bulanAngka = $parts[0] ?? '';
                            $tahunAngka = $parts[1] ?? '';
                            $bulanMap = [
                                '01' => 'Januari',
                                '02' => 'Februari',
                                '03' => 'Maret',
                                '04' => 'April',
                                '05' => 'Mei',
                                '06' => 'Juni',
                                '07' => 'Juli',
                                '08' => 'Agustus',
                                '09' => 'September',
                                '10' => 'Oktober',
                                '11' => 'November',
                                '12' => 'Desember',
                            ];
                            $periodeLabel = ($bulanMap[$bulanAngka] ?? $bulanAngka) . ' / ' . $tahunAngka;
                            $kelasTampil = [];
                            if (!empty($r->tagihan->kelas_snapshot)) {
                                $kelasTampil = is_array($r->tagihan->kelas_snapshot)
                                    ? $r->tagihan->kelas_snapshot
                                    : json_decode($r->tagihan->kelas_snapshot, true) ?? [];
                            } elseif ($r->tagihan && $r->tagihan->peserta) {
                                $kelasTampil = $r->tagihan->peserta->kelas->pluck('nama_kelas')->toArray();
                            }
                            $namaKursus = implode(', ', $kelasTampil);
                            $kasirNama = $r->user->username ?? ($r->user->name ?? '-');
                        @endphp
                        <tr class="hover:bg-gray-50 transition riwayat-row"
                            data-nama="{{ strtolower($r->tagihan->peserta->nama ?? '') }}"
                            data-nomor="{{ strtolower($r->nomor_unik ?? '') }}" data-kursus="{{ strtolower($namaKursus) }}"
                            data-bulan="{{ $bulanAngka }}" data-tahun="{{ $tahunAngka }}">

                            <td class="px-5 py-3.5 text-gray-500 font-medium">{{ $index + 1 }}</td>

                            <td class="px-5 py-3.5">
                                <span class="font-mono text-xs bg-gray-100 text-gray-700 px-2.5 py-1 rounded-lg font-semibold">
                                    {{ $r->nomor_unik ?? '-' }}
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

                            <td class="px-5 py-3.5 text-gray-600 font-medium">
                                {{ $periodeLabel }}
                            </td>

                            <td class="px-5 py-3.5 font-semibold text-gray-800">
                                Rp {{ number_format($r->tagihan->total_tagihan ?? 0, 0, ',', '.') }}
                            </td>

                            <td class="px-5 py-3.5 font-semibold text-green-700">
                                Rp {{ number_format($r->uang_bayar ?? 0, 0, ',', '.') }}
                            </td>

                            <td class="px-5 py-3.5 font-semibold text-blue-600">
                                Rp {{ number_format($r->uang_kembalian ?? 0, 0, ',', '.') }}
                            </td>

                            <td class="px-5 py-3.5 text-gray-600 text-xs">
                                {{ $kasirNama }}
                            </td>

                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center">
                                    <button onclick="cetakStruk(this)" data-nomor="{{ $r->nomor_unik ?? '' }}"
                                        data-nama="{{ $r->tagihan->peserta->nama ?? '-' }}"
                                        data-kursus="{{ $namaKursus ?: '-' }}" data-periode="{{ $periodeLabel }}"
                                        data-total="{{ $r->tagihan->total_tagihan ?? 0 }}"
                                        data-bayar="{{ $r->uang_bayar ?? 0 }}"
                                        data-kembali="{{ $r->uang_kembalian ?? 0 }}" data-kasir="{{ $kasirNama }}"
                                        data-tanggal="{{ $r->created_at ?? now() }}" title="Cetak Struk PDF"
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
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        function filterTable() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const kursus = document.getElementById('filterKursus').value.toLowerCase();
            const bulan = document.getElementById('filterBulan').value;
            const tahun = document.getElementById('filterTahun').value;

            document.querySelectorAll('.riwayat-row').forEach(row => {
                const matchSearch = row.dataset.nama.includes(search) || row.dataset.nomor.includes(search);
                const matchKursus = !kursus || row.dataset.kursus.includes(kursus);
                const matchBulan = !bulan || row.dataset.bulan === bulan;
                const matchTahun = !tahun || row.dataset.tahun === tahun;
                row.style.display = (matchSearch && matchKursus && matchBulan && matchTahun) ? '' : 'none';
            });
        }

        function rupiah(angka) {
            return 'Rp ' + Number(angka).toLocaleString('id-ID');
        }

        function formatTanggal(dateStr) {
            const d = new Date(dateStr);
            if (isNaN(d)) return dateStr;
            return d.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function cetakStruk(btn) {
            const d = btn.dataset;
            const { jsPDF } = window.jspdf;

            const doc = new jsPDF({
                unit: 'mm',
                format: [80, 160],
                orientation: 'portrait'
            });

            const W = 80;
            let y = 8;

            const lineCenter = (text, size = 9, bold = false) => {
                doc.setFontSize(size);
                doc.setFont('courier', bold ? 'bold' : 'normal');
                doc.text(text, W / 2, y, { align: 'center' });
                y += size * 0.45;
            };
            const lineLeft = (text, size = 8) => {
                doc.setFontSize(size);
                doc.setFont('courier', 'normal');
                doc.text(text, 5, y);
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

            lineCenter('EduCourse', 13, true);
            y += 1;
            lineCenter('Lembaga Kursus & Pelatihan', 7);
            y += 1;
            lineCenter('BUKTI PEMBAYARAN', 10, true);
            y += 2;
            dashedLine();

            lineRow('No. Unik', d.nomor, 8, true);
            y += 1;
            lineRow('Tanggal', formatTanggal(d.tanggal), 7);
            y += 1;
            lineRow('Kasir', d.kasir, 7);
            y += 2;
            dashedLine();

            lineRow('Peserta', d.nama, 8, true);
            y += 1;

            const kursusLines = doc.splitTextToSize(d.kursus, 35);
            doc.setFontSize(7);
            doc.setFont('courier', 'normal');
            doc.text('Kursus', 5, y);
            doc.text(kursusLines, W - 5, y, { align: 'right' });
            y += (kursusLines.length * 3.5);

            y += 1;
            lineRow('Periode', d.periode, 7);
            y += 2;
            dashedLine();

            lineRow('Total Tagihan', rupiah(d.total), 8);
            y += 1;
            lineRow('Uang Bayar', rupiah(d.bayar), 8);
            y += 1;
            solidLine();
            lineRow('Kembalian', rupiah(d.kembali), 9, true);
            y += 3;

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