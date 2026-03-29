@extends('Layout.kasir')

@section('content')

    {{-- Breadcrumb --}}
    <div class="mb-6">
        <a href="{{ route('kasir.transaksi.index') }}"
            class="inline-flex items-center gap-2 text-sm text-primary-700 hover:text-primary-900 font-medium transition">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            Kembali ke Transaksi
        </a>
        <h1 class="text-xl font-bold text-gray-800 mt-2">Form Pembayaran</h1>
        <p class="text-sm text-gray-500 mt-0.5">Proses pembayaran tagihan peserta</p>
    </div>

    <form id="form-bayar" action="{{ route('kasir.transaksi.proses', $tagihan->id) }}" method="POST">
        @csrf
        <input type="hidden" name="nomor_unik" id="hidden_nomor_unik">
        <input type="hidden" name="uang_bayar" id="hidden_uang_bayar">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- ===== KIRI: Info Tagihan ===== --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden h-fit">
                <div class="px-6 py-4 bg-primary-700 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                        <i class="fa-solid fa-receipt text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="text-white font-bold text-sm">Detail tagihan</p>
                        <p class="text-blue-200 text-xs">{{ $tagihan->peserta->nama ?? '-' }}</p>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    {{-- Nama & No HP --}}
                    <div>
                        <p class="font-bold text-gray-800 text-base">{{ $tagihan->peserta->nama ?? '-' }}</p>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $tagihan->peserta->no_hp ?? '-' }}</p>
                    </div>

                    <hr class="border-gray-100">

                    {{-- Info rows --}}
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Kursus</span>
                            <div class="flex flex-wrap gap-1 justify-end max-w-[60%]">
                                @foreach($tagihan->peserta->kelas ?? [] as $k)
                                    <span class="bg-primary-50 text-primary-700 text-xs font-medium px-2.5 py-1 rounded-full border border-primary-100">
                                        {{ $k->nama_kelas }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Periode</span>
                            @php
                                $parts = explode('-', $tagihan->bulan_tahun ?? '');
                                $bulanMap = [
                                    '01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April',
                                    '05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus',
                                    '09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember',
                                ];
                                $periodeLabel = ($bulanMap[$parts[0]] ?? ($parts[0] ?? '-')) . ' / ' . ($parts[1] ?? '');
                            @endphp
                            <span class="font-bold text-gray-800">{{ $periodeLabel }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Status</span>
                            <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 text-xs font-semibold px-3 py-1 rounded-full border border-red-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span> Belum Lunas
                            </span>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    {{-- Total --}}
                    <div class="bg-primary-50 border border-primary-100 rounded-xl px-4 py-3 flex justify-between items-center">
                        <span class="text-sm text-gray-600 font-medium">Total Tagihan</span>
                        <span class="text-xl font-bold text-primary-700">
                            Rp {{ number_format($tagihan->total_tagihan ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- ===== KANAN: Form Bayar ===== --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden h-fit">
                <div class="px-6 py-4 bg-primary-700 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                        <i class="fa-solid fa-money-bill-wave text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="text-white font-bold text-sm">Input Pembayaran</p>
                        <p class="text-blue-200 text-xs">isi data pembayaran dibawah!</p>
                    </div>
                </div>
                <div class="p-6 space-y-5">

                    {{-- Validation Errors --}}
                    @if($errors->any())
                        <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3">
                            <ul class="list-disc list-inside text-xs text-red-600 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Nomor Unik --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                            Nomor Unik <span class="text-gray-400 font-normal">(otomatis)</span>
                        </label>
                        <div class="relative">
                            <input type="text" id="input_nomor_unik" readonly
                                class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 bg-gray-50 text-gray-600 font-mono focus:outline-none cursor-not-allowed">
                            <button type="button" onclick="regenNomorUnik()" title="Generate ulang"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-primary-600 hover:text-primary-800 transition">
                                <i class="fa-solid fa-rotate-right text-sm"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Uang Bayar --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                            Uang bayar <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">Rp</span>
                            <input type="number" id="input_uang_bayar" oninput="hitungKembali()"
                                placeholder="0" min="0"
                                class="w-full text-sm border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent transition">
                        </div>
                    </div>

                    {{-- Uang Kembalian --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Uang Kembalian</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">Rp</span>
                            <input type="text" id="input_uang_kembali" readonly value="0"
                                class="w-full text-sm border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 bg-gray-50 text-gray-700 font-bold focus:outline-none cursor-not-allowed">
                        </div>
                        <p id="kembali_warning" class="text-xs text-red-500 mt-1.5 hidden">
                            <i class="fa-solid fa-triangle-exclamation mr-1"></i>Uang bayar kurang dari total tagihan!
                        </p>
                        <p id="kembali_ok" class="text-xs text-green-600 mt-1.5 hidden">
                            <i class="fa-solid fa-circle-check mr-1"></i>Uang bayar cukup.
                        </p>
                    </div>

                    {{-- Ringkasan --}}
                    <div id="ringkasan" class="hidden bg-gray-50 border border-gray-200 rounded-xl p-4 space-y-2 text-sm">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Ringkasan</p>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Total Tagihan</span>
                            <span class="font-semibold">Rp {{ number_format($tagihan->total_tagihan ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Uang Bayar</span>
                            <span id="ring_bayar" class="font-semibold">Rp 0</span>
                        </div>
                        <hr class="border-gray-200">
                        <div class="flex justify-between">
                            <span class="text-gray-500 font-medium">Kembalian</span>
                            <span id="ring_kembali" class="font-bold text-green-600">Rp 0</span>
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex items-center gap-3 pt-1">
                        <a href="{{ route('kasir.transaksi.index') }}"
                            class="flex-1 text-center px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                            <i class="fa-solid fa-xmark mr-1.5"></i> Batal
                        </a>
                        <button type="button" id="btn_bayar" onclick="submitBayar()"
                            class="flex-1 px-5 py-2.5 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-xl shadow transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-check"></i> Bayar Sekarang
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </form>

    <script>
        const totalTagihan = {{ $tagihan->total_tagihan ?? 0 }};

        function generateNomorUnik() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let result = 'TRX-';
            for (let i = 0; i < 8; i++) result += chars.charAt(Math.floor(Math.random() * chars.length));
            return result;
        }
        function regenNomorUnik() {
            document.getElementById('input_nomor_unik').value = generateNomorUnik();
        }
        document.getElementById('input_nomor_unik').value = generateNomorUnik();

        function hitungKembali() {
            const bayar    = parseInt(document.getElementById('input_uang_bayar').value) || 0;
            const kembali  = bayar - totalTagihan;
            const warning  = document.getElementById('kembali_warning');
            const ok       = document.getElementById('kembali_ok');
            const ringkasan = document.getElementById('ringkasan');
            const btnBayar = document.getElementById('btn_bayar');

            if (bayar <= 0) {
                document.getElementById('input_uang_kembali').value = '0';
                warning.classList.add('hidden');
                ok.classList.add('hidden');
                ringkasan.classList.add('hidden');
                btnBayar.disabled = false;
                btnBayar.classList.remove('opacity-50', 'cursor-not-allowed');
                return;
            }

            if (kembali < 0) {
                document.getElementById('input_uang_kembali').value = '0';
                warning.classList.remove('hidden');
                ok.classList.add('hidden');
                ringkasan.classList.add('hidden');
                btnBayar.disabled = true;
                btnBayar.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                document.getElementById('input_uang_kembali').value = kembali.toLocaleString('id-ID');
                warning.classList.add('hidden');
                ok.classList.remove('hidden');
                document.getElementById('ring_bayar').textContent   = 'Rp ' + bayar.toLocaleString('id-ID');
                document.getElementById('ring_kembali').textContent = 'Rp ' + kembali.toLocaleString('id-ID');
                ringkasan.classList.remove('hidden');
                btnBayar.disabled = false;
                btnBayar.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }

        function submitBayar() {
            const bayar = parseInt(document.getElementById('input_uang_bayar').value) || 0;

            if (bayar <= 0) {
                Swal.fire({ icon: 'warning', title: 'Uang Bayar Kosong', text: 'Masukkan jumlah uang bayar terlebih dahulu.', confirmButtonColor: '#1e5399' });
                return;
            }
            if (bayar < totalTagihan) {
                Swal.fire({ icon: 'error', title: 'Uang Kurang!', text: 'Uang bayar tidak cukup untuk melunasi tagihan.', confirmButtonColor: '#1e5399' });
                return;
            }

            const nomorUnik = document.getElementById('input_nomor_unik').value;
            const kembali   = bayar - totalTagihan;

            Swal.fire({
                title: 'Konfirmasi Pembayaran?',
                html: `
                    <div class="text-left space-y-2 text-sm mt-2">
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-500">Nomor Unik</span>
                            <span class="font-mono font-semibold">${nomorUnik}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-500">Total Tagihan</span>
                            <span class="font-semibold">Rp ${totalTagihan.toLocaleString('id-ID')}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-500">Uang Bayar</span>
                            <span class="font-semibold">Rp ${bayar.toLocaleString('id-ID')}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Kembalian</span>
                            <span class="font-bold text-green-600">Rp ${kembali.toLocaleString('id-ID')}</span>
                        </div>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fa-solid fa-check mr-1"></i> Ya, Bayar!',
                cancelButtonText: 'Cek Lagi',
            }).then(result => {
                if (result.isConfirmed) {
                    // Set hidden inputs lalu submit form
                    document.getElementById('hidden_nomor_unik').value = nomorUnik;
                    document.getElementById('hidden_uang_bayar').value = bayar;
                    document.getElementById('form-bayar').submit();
                }
            });
        }

        @if(session('error'))
            Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session("error") }}', confirmButtonColor: '#1e5399' });
        @endif
    </script>

@endsection