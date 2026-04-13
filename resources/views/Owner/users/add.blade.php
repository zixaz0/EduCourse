@extends('Layout.owner')

@section('content')

    <div class="mb-6">
        <a href="{{ route('owner.users.index') }}"
            class="inline-flex items-center gap-2 text-sm text-primary-700 hover:text-primary-900 font-medium transition">
            <i class="fa-solid fa-arrow-left text-xs"></i> Kembali ke Data User
        </a>
        <h1 class="text-xl font-bold text-gray-800 mt-2">Tambah User Baru</h1>
        <p class="text-sm text-gray-500 mt-0.5">Buat akun admin atau kasir baru untuk sistem</p>
    </div>

    <form method="POST" action="{{ route('owner.users.store') }}">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-primary-700 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                        <i class="fa-solid fa-user-plus text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="text-white font-bold text-sm">Form Tambah User</p>
                        <p class="text-blue-200 text-xs">Kolom bertanda <span class="text-red-300 font-bold">*</span> wajib diisi</p>
                    </div>
                </div>

                <div class="px-6 py-6 space-y-5">

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Username <span class="text-red-500">*</span></label>
                        <input type="text" name="username" value="{{ old('username') }}" placeholder="contoh: admin01" id="inp_username"
                            class="w-full text-sm border @error('username') border-red-400 @else border-gray-200 @enderror rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 transition">
                        @error('username')
                            <p class="text-xs text-red-500 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" placeholder="contoh: Budi Santoso" id="inp_nama"
                            class="w-full text-sm border @error('nama') border-red-400 @else border-gray-200 @enderror rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 transition">
                        @error('nama')
                            <p class="text-xs text-red-500 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="contoh: user@email.com"
                            class="w-full text-sm border @error('email') border-red-400 @else border-gray-200 @enderror rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 transition">
                        @error('email')
                            <p class="text-xs text-red-500 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

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

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-2">Role <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="role" value="admin" id="role_admin" class="peer hidden"
                                    {{ old('role', 'kasir') === 'admin' ? 'checked' : '' }}>
                                <div class="border-2 border-gray-200 rounded-xl p-4 flex items-center gap-3 transition
                                    peer-checked:border-purple-500 peer-checked:bg-purple-50">
                                    <div class="w-9 h-9 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
                                        <i class="fa-solid fa-user-shield text-purple-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-800">Admin</p>
                                        <p class="text-xs text-gray-400">Akses penuh sistem</p>
                                    </div>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="role" value="kasir" id="role_kasir" class="peer hidden"
                                    {{ old('role', 'kasir') === 'kasir' ? 'checked' : '' }}>
                                <div class="border-2 border-gray-200 rounded-xl p-4 flex items-center gap-3 transition
                                    peer-checked:border-blue-500 peer-checked:bg-blue-50">
                                    <div class="w-9 h-9 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                                        <i class="fa-solid fa-cash-register text-blue-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-800">Kasir</p>
                                        <p class="text-xs text-gray-400">Akses transaksi</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                        @error('role')
                            <p class="text-xs text-red-500 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Status <span class="text-red-500">*</span></label>
                        <select name="status"
                            class="w-full text-sm border @error('status') border-red-400 @else border-gray-200 @enderror rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white transition">
                            <option value="aktif"    {{ old('status', 'aktif') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                        @error('status')
                            <p class="text-xs text-red-500 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            <div class="space-y-4">

                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                        <div class="w-1 h-5 bg-primary-700 rounded-full"></div>
                        <h3 class="text-sm font-bold text-gray-700">Preview Akun</h3>
                    </div>
                    <div class="p-5 space-y-4">
                        <div class="flex justify-center">
                            <div id="prev_avatar" class="w-16 h-16 rounded-full bg-blue-100 border-2 border-blue-200 flex items-center justify-center transition">
                                <span id="prev_initial" class="font-bold text-2xl text-blue-700">?</span>
                            </div>
                        </div>
                        <div class="text-center">
                            <p id="prev_username" class="font-bold text-gray-800 text-base">—</p>
                            <p id="prev_nama" class="text-xs text-gray-500 mt-0.5">—</p>
                            <div id="prev_role_badge" class="mt-2">
                                <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full border border-blue-100">
                                    <i class="fa-solid fa-cash-register text-xs"></i> Kasir
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 space-y-3">
                    <button type="submit"
                        class="w-full px-5 py-2.5 text-sm font-medium text-white bg-primary-700 hover:bg-primary-800 rounded-xl shadow transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-save"></i> Simpan Akun
                    </button>
                    <a href="{{ route('owner.users.index') }}"
                        class="w-full px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-xmark"></i> Batal
                    </a>
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
            document.getElementById('prev_username').textContent = this.value || '—';
            document.getElementById('prev_initial').textContent  = this.value ? this.value[0].toUpperCase() : '?';
        });

        document.getElementById('inp_nama').addEventListener('input', function () {
            document.getElementById('prev_nama').textContent = this.value || '—';
        });

        const roleBadges = {
            admin: `<span class="inline-flex items-center gap-1.5 bg-purple-50 text-purple-700 text-xs font-semibold px-3 py-1 rounded-full border border-purple-100"><i class="fa-solid fa-user-shield text-xs"></i> Admin</span>`,
            kasir: `<span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full border border-blue-100"><i class="fa-solid fa-cash-register text-xs"></i> Kasir</span>`,
        };

        const avatarStyles = {
            admin: { bg: 'bg-purple-100 border-purple-200', text: 'text-purple-700' },
            kasir: { bg: 'bg-blue-100 border-blue-200',   text: 'text-blue-700'   },
        };

        document.querySelectorAll('input[name="role"]').forEach(function(radio) {
            radio.addEventListener('change', function () {
                const role    = this.value;
                const style   = avatarStyles[role];
                const avatar  = document.getElementById('prev_avatar');
                const initial = document.getElementById('prev_initial');

                avatar.className  = 'w-16 h-16 rounded-full border-2 flex items-center justify-center transition ' + style.bg;
                initial.className = 'font-bold text-2xl ' + style.text;
                document.getElementById('prev_role_badge').innerHTML = roleBadges[role];
            });
        });
    </script>

@endsection