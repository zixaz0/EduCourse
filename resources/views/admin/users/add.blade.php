@extends('Layout.admin')

@section('content')

    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}"
            class="inline-flex items-center gap-2 text-sm text-primary-700 hover:text-primary-900 font-medium transition">
            <i class="fa-solid fa-arrow-left text-xs"></i> Kembali ke Data User
        </a>
        <h1 class="text-xl font-bold text-gray-800 mt-2">Tambah Akun Kasir</h1>
        <p class="text-sm text-gray-500 mt-0.5">Buat akun kasir baru untuk sistem</p>
    </div>

    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        {{-- Role dikunci kasir --}}
        <input type="hidden" name="role" value="kasir">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- KIRI: Form --}}
            <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-primary-700 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                        <i class="fa-solid fa-cash-register text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="text-white font-bold text-sm">Form Tambah Kasir</p>
                        <p class="text-blue-200 text-xs">Kolom bertanda <span class="text-red-300 font-bold">*</span> wajib diisi</p>
                    </div>
                </div>

                <div class="px-6 py-6 space-y-5">

                    {{-- Username --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Username <span class="text-red-500">*</span></label>
                        <input type="text" name="username" value="{{ old('username') }}" placeholder="contoh: kasir01" id="inp_username"
                            class="w-full text-sm border @error('username') border-red-400 @else border-gray-200 @enderror rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 transition">
                        @error('username')
                            <p class="text-xs text-red-500 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nama --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" placeholder="contoh: Budi Santoso" id="inp_nama"
                            class="w-full text-sm border @error('nama') border-red-400 @else border-gray-200 @enderror rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 transition">
                        @error('nama')
                            <p class="text-xs text-red-500 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="contoh: kasir@email.com"
                            class="w-full text-sm border @error('email') border-red-400 @else border-gray-200 @enderror rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 transition">
                        @error('email')
                            <p class="text-xs text-red-500 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Password <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="password" name="password" id="inp_password" placeholder="Min. 8 karakter"
                                    class="w-full text-sm border @error('password') border-red-400 @else border-gray-200 @enderror rounded-xl px-4 py-2.5 pr-10 focus:outline-none focus:ring-2 focus:ring-primary-300 transition">
                                <button type="button" onclick="togglePass('inp_password', 'eye1')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i class="fa-solid fa-eye text-xs" id="eye1"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-xs text-red-500 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Konfirmasi Password <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="inp_password_conf" placeholder="Ulangi password"
                                    class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 pr-10 focus:outline-none focus:ring-2 focus:ring-primary-300 transition">
                                <button type="button" onclick="togglePass('inp_password_conf', 'eye2')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i class="fa-solid fa-eye text-xs" id="eye2"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Status <span class="text-red-500">*</span></label>
                        <select name="status"
                            class="w-full text-sm border @error('status') border-red-400 @else border-gray-200 @enderror rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white transition">
                            <option value="aktif" {{ old('status', 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                        @error('status')
                            <p class="text-xs text-red-500 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- KANAN: Preview + Aksi --}}
            <div class="space-y-4">

                {{-- Preview Card --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                        <div class="w-1 h-5 bg-primary-700 rounded-full"></div>
                        <h3 class="text-sm font-bold text-gray-700">Preview Akun</h3>
                    </div>
                    <div class="p-5 space-y-4">
                        <div class="flex justify-center">
                            <div class="w-16 h-16 rounded-full bg-blue-100 border-2 border-blue-200 flex items-center justify-center">
                                <span id="prev_initial" class="font-bold text-blue-700 text-2xl">?</span>
                            </div>
                        </div>
                        <div class="text-center">
                            <p id="prev_username" class="font-bold text-gray-800 text-base">—</p>
                            <p id="prev_nama" class="text-xs text-gray-500 mt-0.5">—</p>
                            <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full border border-blue-100 mt-2">
                                <i class="fa-solid fa-cash-register text-xs"></i> Kasir
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Tombol --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 space-y-3">
                    <button type="submit"
                        class="w-full px-5 py-2.5 text-sm font-medium text-white bg-primary-700 hover:bg-primary-800 rounded-xl shadow transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-save"></i> Simpan Akun
                    </button>
                    <a href="{{ route('admin.users.index') }}"
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
                            <p>Akun yang dibuat melalui halaman ini otomatis berstatus <b>Kasir</b> dan tidak dapat diubah rolenya.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>

    <script>
        function togglePass(inputId, eyeId) {
            const input = document.getElementById(inputId);
            const eye   = document.getElementById(eyeId);
            if (input.type === 'password') {
                input.type = 'text';
                eye.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                eye.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        document.getElementById('inp_username').addEventListener('input', function () {
            const val = this.value || '—';
            document.getElementById('prev_username').textContent = val;
            document.getElementById('prev_initial').textContent  = this.value ? this.value[0].toUpperCase() : '?';
        });

        document.getElementById('inp_nama').addEventListener('input', function () {
            document.getElementById('prev_nama').textContent = this.value || '—';
        });
    </script>

@endsection