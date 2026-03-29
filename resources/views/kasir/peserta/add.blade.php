@extends('Layout.kasir')

@section('content')

    {{-- Breadcrumb / Back --}}
    <div class="mb-6">
        <a href="{{ route('kasir.peserta.index') }}"
            class="inline-flex items-center gap-2 text-sm text-primary-700 hover:text-primary-900 font-medium transition">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            Kembali ke Data Peserta
        </a>
        <h1 class="text-xl font-bold text-gray-800 mt-2">Tambah Peserta Baru</h1>
        <p class="text-sm text-gray-500 mt-0.5">Isi data peserta dengan lengkap dan benar</p>
    </div>

    {{-- Form Card --}}
    <form id="form-peserta" action="{{ route('kasir.peserta.store') }}" method="POST">
        @csrf
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

            {{-- Card Header --}}
            <div class="px-6 py-4 bg-primary-700 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fa-solid fa-user-plus text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-white font-bold text-sm">Form Pendaftaran Peserta Baru</p>
                    <p class="text-blue-200 text-xs">Kolom bertanda <span class="text-red-300 font-bold">*</span> wajib diisi</p>
                </div>
            </div>

            {{-- Validation Errors --}}
            @if($errors->any())
                <div class="mx-6 mt-5 bg-red-50 border border-red-200 rounded-xl px-4 py-3">
                    <ul class="list-disc list-inside text-xs text-red-600 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form Body --}}
            <div class="px-6 py-6">

                {{-- Section: Data Pribadi --}}
                <div class="mb-6">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-1 h-5 bg-primary-700 rounded-full"></div>
                        <h2 class="text-sm font-bold text-gray-700">Data Pribadi</h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        {{-- Nama --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Masukkan Nama Lengkap"
                                class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent transition @error('nama') border-red-400 @enderror">
                        </div>

                        {{-- No HP --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                                No. HP <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="no_hp" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx"
                                class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent transition @error('no_hp') border-red-400 @enderror">
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com"
                                class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent transition @error('email') border-red-400 @enderror">
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                                Jenis Kelamin <span class="text-red-500">*</span>
                            </label>
                            <div class="flex gap-3">
                                <label class="flex-1 flex items-center gap-3 p-2.5 border border-gray-200 rounded-xl cursor-pointer hover:border-primary-300 hover:bg-primary-50 transition">
                                    <input type="radio" name="jenis_kelamin" value="laki-laki"
                                        {{ old('jenis_kelamin', 'laki-laki') === 'laki-laki' ? 'checked' : '' }}
                                        class="accent-primary-700">
                                    <span class="text-sm text-gray-700 font-medium">Laki-laki</span>
                                </label>
                                <label class="flex-1 flex items-center gap-3 p-2.5 border border-gray-200 rounded-xl cursor-pointer hover:border-primary-300 hover:bg-primary-50 transition">
                                    <input type="radio" name="jenis_kelamin" value="perempuan"
                                        {{ old('jenis_kelamin') === 'perempuan' ? 'checked' : '' }}
                                        class="accent-primary-700">
                                    <span class="text-sm text-gray-700 font-medium">Perempuan</span>
                                </label>
                            </div>
                        </div>

                        {{-- Kelas Akademik --}}
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                                Kelas <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="kelas_akademik" value="{{ old('kelas_akademik') }}" placeholder="Masukan Kelas Akademi"
                                class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent transition @error('kelas_akademik') border-red-400 @enderror">
                        </div>

                    </div>
                </div>

                <hr class="border-gray-100 mb-6">

                {{-- Section: Data Orang Tua --}}
                <div class="mb-6">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-1 h-5 bg-primary-700 rounded-full"></div>
                        <h2 class="text-sm font-bold text-gray-700">Data Orang Tua / Wali</h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        {{-- Nama Ortu --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                                Nama Orang Tua / Wali <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_ortu" value="{{ old('nama_ortu') }}" placeholder="masukan nama orang tua / wali"
                                class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent transition @error('nama_ortu') border-red-400 @enderror">
                        </div>

                        {{-- No Ortu --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                                No. HP Orang Tua / Wali <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="no_ortu" value="{{ old('no_ortu') }}" placeholder="08xxxxxxxxxx"
                                class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent transition @error('no_ortu') border-red-400 @enderror">
                        </div>

                    </div>
                </div>

                <hr class="border-gray-100 mb-6">

                {{-- Section: Kelas Kursus --}}
                <div class="mb-6">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-1 h-5 bg-primary-700 rounded-full"></div>
                        <h2 class="text-sm font-bold text-gray-700">Kelas Kursus <span class="text-red-500">*</span></h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @forelse($kelasList ?? [] as $kelas)
                            <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:border-primary-300 hover:bg-primary-50 transition group">
                                <input type="checkbox" name="kelas[]" value="{{ $kelas->id }}"
                                    {{ is_array(old('kelas')) && in_array($kelas->id, old('kelas')) ? 'checked' : '' }}
                                    class="w-4 h-4 accent-primary-700 flex-shrink-0">
                                <div>
                                    <p class="text-sm font-semibold text-gray-700 group-hover:text-primary-700 transition">{{ $kelas->nama_kelas }}</p>
                                    <p class="text-xs text-gray-400">Rp {{ number_format($kelas->harga_kelas, 0, ',', '.') }}/bln</p>
                                </div>
                            </label>
                        @empty
                            <div class="col-span-3 text-center py-6 text-gray-400 text-sm">
                                <i class="fa-solid fa-door-open text-2xl mb-2 block text-gray-200"></i>
                                Belum ada kelas tersedia
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('kasir.peserta.index') }}"
                        class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                        <i class="fa-solid fa-xmark mr-1.5"></i> Batal
                    </a>
                    <button type="button" onclick="submitForm()"
                        class="px-6 py-2.5 text-sm font-medium text-white bg-primary-700 hover:bg-primary-800 rounded-xl shadow transition">
                        <i class="fa-solid fa-plus mr-1.5"></i> Simpan Peserta
                    </button>
                </div>

            </div>
        </div>
    </form>

    <script>
        function submitForm() {
            Swal.fire({
                title: 'Simpan Data?',
                text: 'Pastikan semua data sudah benar sebelum disimpan.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1e5399',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Cek Lagi',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-peserta').submit();
                }
            });
        }
    </script>

@endsection