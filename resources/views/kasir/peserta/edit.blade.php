@extends('Layout.kasir')

@section('content')

    {{-- Breadcrumb / Back --}}
    <div class="mb-6">
        <a href="{{ route('kasir.peserta.index') }}"
            class="inline-flex items-center gap-2 text-sm text-primary-700 hover:text-primary-900 font-medium transition">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            Kembali ke Data Peserta
        </a>
        <h1 class="text-xl font-bold text-gray-800 mt-2">Edit Peserta</h1>
        <p class="text-sm text-gray-500 mt-0.5">
            Mengubah data peserta <span class="font-semibold text-primary-700">{{ $peserta->nama ?? '-' }}</span>
        </p>
    </div>

    {{-- Form Card --}}
    <form id="form-peserta" action="{{ route('kasir.peserta.update', $peserta->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

            {{-- Card Header --}}
            <div class="px-6 py-4 bg-primary-700 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                    <span
                        class="text-white font-bold text-base">{{ strtoupper(substr($peserta->nama ?? 'P', 0, 1)) }}</span>
                </div>
                <div>
                    <p class="text-white font-bold text-sm">{{ $peserta->nama ?? 'Nama Peserta' }}</p>
                    <p class="text-blue-200 text-xs">{{ $peserta->email ?? '-' }}</p>
                </div>
            </div>

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="mx-6 mt-5 bg-red-50 border border-red-200 rounded-xl px-4 py-3">
                    <ul class="list-disc list-inside text-xs text-red-600 space-y-1">
                        @foreach ($errors->all() as $error)
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
                            <input type="text" name="nama" value="{{ old('nama', $peserta->nama) }}"
                                placeholder="Masukkan nama lengkap"
                                class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent transition @error('nama') border-red-400 @enderror">
                        </div>

                        {{-- No HP --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                                No. HP <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', $peserta->no_hp) }}"
                                placeholder="08xxxxxxxxxx"
                                class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent transition @error('no_hp') border-red-400 @enderror">
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email', $peserta->email) }}"
                                placeholder="contoh@email.com"
                                class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent transition @error('email') border-red-400 @enderror">
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                                Jenis Kelamin <span class="text-red-500">*</span>
                            </label>
                            <div class="flex gap-3">
                                @php $jk = old('jenis_kelamin', $peserta->jenis_kelamin); @endphp
                                <label
                                    class="flex-1 flex items-center gap-3 p-2.5 border rounded-xl cursor-pointer transition
                                    {{ $jk === 'laki-laki' ? 'border-primary-400 bg-primary-50' : 'border-gray-200 hover:border-primary-300 hover:bg-primary-50' }}">
                                    <input type="radio" name="jenis_kelamin" value="laki-laki"
                                        {{ $jk === 'laki-laki' ? 'checked' : '' }} class="accent-primary-700">
                                    <span class="text-sm text-gray-700 font-medium">Laki - laki</span>
                                </label>
                                <label
                                    class="flex-1 flex items-center gap-3 p-2.5 border rounded-xl cursor-pointer transition
                                    {{ $jk === 'perempuan' ? 'border-primary-400 bg-primary-50' : 'border-gray-200 hover:border-primary-300 hover:bg-primary-50' }}">
                                    <input type="radio" name="jenis_kelamin" value="perempuan"
                                        {{ $jk === 'perempuan' ? 'checked' : '' }} class="accent-primary-700">
                                    <span class="text-sm text-gray-700 font-medium">Perempuan</span>
                                </label>
                            </div>
                        </div>

                        {{-- Level --}}
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                                Level <span class="text-red-500">*</span>
                            </label>
                            @php $lvl = old('level', $peserta->level); @endphp
                            <select name="level"
                                class="cursor-pointer w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent transition bg-white @error('level') border-red-400 @enderror">
                                <option value="">-- Pilih Level --</option>
                                <option value="cukup"  {{ $lvl === 'cukup'  ? 'selected' : '' }}>Cukup</option>
                                <option value="baik"   {{ $lvl === 'baik'   ? 'selected' : '' }}>Baik</option>
                                <option value="mahir"  {{ $lvl === 'mahir'  ? 'selected' : '' }}>Mahir</option>
                            </select>
                            @error('level')
                                <p class="text-xs text-red-500 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                            @enderror
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
                            <input type="text" name="nama_ortu" value="{{ old('nama_ortu', $peserta->nama_ortu) }}"
                                placeholder="Masukkan nama orang tua / wali"
                                class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent transition @error('nama_ortu') border-red-400 @enderror">
                        </div>

                        {{-- No Ortu --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                                No. HP Orang Tua / Wali <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="no_ortu" value="{{ old('no_ortu', $peserta->no_ortu) }}"
                                placeholder="08xxxxxxxxxx"
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
                    @php
                        $kelasAktifIds = $peserta->kelas->pluck('id')->toArray();
                    @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @forelse($kelasList ?? [] as $kelas)
                            @php $checked = in_array($kelas->id, old('kelas', $kelasAktifIds)); @endphp
                            <label
                                class="flex items-center gap-3 p-3 border rounded-xl cursor-pointer transition group
                                {{ $checked ? 'border-primary-400 bg-primary-50' : 'border-gray-200 hover:border-primary-300 hover:bg-primary-50' }}">
                                <input type="checkbox" name="kelas[]" value="{{ $kelas->id }}"
                                    {{ $checked ? 'checked' : '' }} class="w-4 h-4 accent-primary-700 flex-shrink-0">
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-700 group-hover:text-primary-700 transition">
                                        {{ $kelas->nama_kelas }}</p>
                                    <p class="text-xs text-gray-400">Rp
                                        {{ number_format($kelas->harga_kelas, 0, ',', '.') }}/bln</p>
                                </div>
                                @if ($checked)
                                    <i class="fa-solid fa-circle-check text-primary-600 text-sm"></i>
                                @endif
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
                        <i class="fa-solid fa-floppy-disk mr-1.5"></i> Update Peserta
                    </button>
                </div>

            </div>
        </div>
    </form>

    <script>
        function submitForm() {
            Swal.fire({
                title: 'Simpan Perubahan?',
                text: 'Data peserta akan diperbarui.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1e5399',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Update',
                cancelButtonText: 'Cek Lagi',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-peserta').submit();
                }
            });
        }
    </script>

@endsection