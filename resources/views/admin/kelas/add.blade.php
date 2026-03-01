@extends('Layout.admin')

@section('content')

    <div class="mb-6">
        <a href="{{ url('/admin/kelas') }}"
            class="inline-flex items-center gap-2 text-sm text-primary-700 hover:text-primary-900 font-medium transition">
            <i class="fa-solid fa-arrow-left text-xs"></i> Kembali ke Data Kelas
        </a>
        <h1 class="text-xl font-bold text-gray-800 mt-2">Tambah Kelas Baru</h1>
        <p class="text-sm text-gray-500 mt-0.5">Buat kelas kursus baru</p>
    </div>

    <form method="POST" action="{{ url('/admin/kelas') }}">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ===== KIRI: Form Input ===== --}}
            <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-primary-700 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                        <i class="fa-solid fa-chalkboard-user text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="text-white font-bold text-sm">Form Tambah Kelas</p>
                        <p class="text-blue-200 text-xs">Kolom bertanda <span class="text-red-300 font-bold">*</span> wajib diisi</p>
                    </div>
                </div>

                <div class="px-6 py-6 space-y-5">

                    {{-- Nama Kelas --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                            Nama Kelas <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_kelas" value="{{ old('nama_kelas') }}"
                            placeholder="contoh: Python Dasar"
                            class="w-full text-sm border @error('nama_kelas') border-red-400 @else border-gray-200 @enderror rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 transition">
                        @error('nama_kelas')
                            <p class="text-xs text-red-500 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Harga Kelas --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                            Harga Kelas <span class="text-red-500">*</span>
                            <span class="text-gray-400 font-normal">(per bulan)</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">Rp</span>
                            <input type="number" name="harga_kelas" value="{{ old('harga_kelas') }}"
                                placeholder="0" min="0" step="1000"
                                class="w-full text-sm border @error('harga_kelas') border-red-400 @else border-gray-200 @enderror rounded-xl pl-10 pr-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 transition">
                        </div>
                        @error('harga_kelas')
                            <p class="text-xs text-red-500 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Hari Kelas --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-2">
                            Hari Kelas <span class="text-red-500">*</span>
                            <span class="text-gray-400 font-normal">(bisa pilih lebih dari satu)</span>
                        </label>
                        @php
                            $hariOptions = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
                            $oldHari = old('hari_kelas', []);
                            $hariColor = [
                                'Senin'  => 'peer-checked:bg-blue-600 peer-checked:border-blue-600',
                                'Selasa' => 'peer-checked:bg-purple-600 peer-checked:border-purple-600',
                                'Rabu'   => 'peer-checked:bg-green-600 peer-checked:border-green-600',
                                'Kamis'  => 'peer-checked:bg-yellow-500 peer-checked:border-yellow-500',
                                'Jumat'  => 'peer-checked:bg-orange-500 peer-checked:border-orange-500',
                                'Sabtu'  => 'peer-checked:bg-pink-600 peer-checked:border-pink-600',
                                'Minggu' => 'peer-checked:bg-red-600 peer-checked:border-red-600',
                            ];
                        @endphp
                        <div class="flex flex-wrap gap-2">
                            @foreach($hariOptions as $hari)
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="hari_kelas[]" value="{{ $hari }}"
                                        class="peer hidden"
                                        {{ in_array($hari, $oldHari) ? 'checked' : '' }}>
                                    <span class="inline-block text-xs font-semibold px-5 py-2.5 rounded-xl border-2 border-gray-200 text-gray-500 bg-white
                                        transition-all peer-checked:text-white {{ $hariColor[$hari] }}">
                                        {{ $hari }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('hari_kelas')
                            <p class="text-xs text-red-500 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- ===== KANAN: Preview + Aksi ===== --}}
            <div class="space-y-4">

                {{-- Preview Card --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                        <div class="w-1 h-5 bg-primary-700 rounded-full"></div>
                        <h3 class="text-sm font-bold text-gray-700">Preview Kelas</h3>
                    </div>
                    <div class="p-5 space-y-4">
                        <div class="flex justify-center">
                            <div class="w-16 h-16 rounded-2xl bg-primary-50 border border-primary-100 flex items-center justify-center">
                                <i class="fa-solid fa-chalkboard-user text-primary-600 text-2xl"></i>
                            </div>
                        </div>
                        <div class="text-center">
                            <p id="prev_nama" class="font-bold text-gray-800 text-base">—</p>
                            <p id="prev_harga" class="text-sm text-primary-700 font-semibold mt-1">Rp 0 / bulan</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium mb-2 text-center">Hari Kelas</p>
                            <div id="prev_hari" class="flex flex-wrap gap-1 justify-center">
                                <span class="text-xs text-gray-300">Belum dipilih</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 space-y-3">
                    <button type="submit"
                        class="w-full px-5 py-2.5 text-sm font-medium text-white bg-primary-700 hover:bg-primary-800 rounded-xl shadow transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-save"></i> Simpan Kelas
                    </button>
                    <a href="{{ url('/admin/kelas') }}"
                        class="w-full px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-xmark"></i> Batal
                    </a>
                </div>

                {{-- Info --}}
                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4">
                    <div class="flex gap-3">
                        <i class="fa-solid fa-circle-info text-blue-500 mt-0.5 flex-shrink-0"></i>
                        <div class="text-xs text-blue-700 space-y-1">
                            <p class="font-semibold">Catatan:</p>
                            <p>Harga kelas akan digunakan sebagai dasar perhitungan tagihan peserta tiap bulan.</p>
                            <p class="mt-1">Hari kelas bisa dipilih lebih dari satu sesuai jadwal.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>

    <script>
        // Live Preview
        document.querySelector('[name="nama_kelas"]').addEventListener('input', function () {
            document.getElementById('prev_nama').textContent = this.value || '—';
        });

        document.querySelector('[name="harga_kelas"]').addEventListener('input', function () {
            const val = parseInt(this.value) || 0;
            document.getElementById('prev_harga').textContent = 'Rp ' + val.toLocaleString('id-ID') + ' / bulan';
        });

        document.querySelectorAll('[name="hari_kelas[]"]').forEach(cb => {
            cb.addEventListener('change', updateHariPreview);
        });

        const hariColors = {
            'Senin': 'bg-blue-100 text-blue-700', 'Selasa': 'bg-purple-100 text-purple-700',
            'Rabu': 'bg-green-100 text-green-700', 'Kamis': 'bg-yellow-100 text-yellow-700',
            'Jumat': 'bg-orange-100 text-orange-700', 'Sabtu': 'bg-pink-100 text-pink-700',
            'Minggu': 'bg-red-100 text-red-700',
        };

        function updateHariPreview() {
            const checked = [...document.querySelectorAll('[name="hari_kelas[]"]:checked')].map(c => c.value);
            const el = document.getElementById('prev_hari');
            el.innerHTML = checked.length
                ? checked.map(h => `<span class="text-xs font-semibold px-2.5 py-1 rounded-full ${hariColors[h] || 'bg-gray-100 text-gray-600'}">${h}</span>`).join('')
                : '<span class="text-xs text-gray-300">Belum dipilih</span>';
        }
    </script>

@endsection