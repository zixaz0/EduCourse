@extends('Layout.admin')

@section('content')

    <div class="mb-6">
        <a href="{{ url('/admin/user') }}"
            class="inline-flex items-center gap-2 text-sm text-primary-700 hover:text-primary-900 font-medium transition">
            <i class="fa-solid fa-arrow-left text-xs"></i> Kembali ke Manajemen User
        </a>
        <h1 class="text-xl font-bold text-gray-800 mt-2">Tambah User Baru</h1>
        <p class="text-sm text-gray-500 mt-0.5">Buat akun kasir atau admin baru</p>
    </div>

    <form method="POST" action="{{ url('/admin/user') }}">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ===== KIRI: Form ===== --}}
            <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden h-fit">

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

                    {{-- Username, Nama & Email --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                                Username <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="username" id="inp_username" value="{{ old('username') }}"
                                placeholder="Masukkan username" oninput="updatePreview()"
                                class="w-full text-sm border @error('username') border-red-400 @else border-gray-200 @enderror rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 transition">
                            @error('username')
                                <p class="text-xs text-red-500 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama" id="inp_nama" value="{{ old('nama') }}"
                                placeholder="Masukkan nama lengkap" oninput="updatePreview()"
                                class="w-full text-sm border @error('nama') border-red-400 @else border-gray-200 @enderror rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 transition">
                            @error('nama')
                                <p class="text-xs text-red-500 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" id="inp_email" value="{{ old('email') }}"
                                placeholder="contoh@email.com" oninput="updatePreview()"
                                class="w-full text-sm border @error('email') border-red-400 @else border-gray-200 @enderror rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 transition">
                            @error('email')
                                <p class="text-xs text-red-500 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Password & Konfirmasi --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" name="password" id="password" placeholder="Minimal 8 karakter"
                                    class="w-full text-sm border @error('password') border-red-400 @else border-gray-200 @enderror rounded-xl px-4 py-2.5 pr-11 focus:outline-none focus:ring-2 focus:ring-primary-300 transition">
                                <button type="button" onclick="togglePassword('password','eye-pass')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                                    <i id="eye-pass" class="fa-solid fa-eye text-sm"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-xs text-red-500 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                                Konfirmasi Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="password_confirm" placeholder="Ulangi password"
                                    class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 pr-11 focus:outline-none focus:ring-2 focus:ring-primary-300 transition">
                                <button type="button" onclick="togglePassword('password_confirm','eye-confirm')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                                    <i id="eye-confirm" class="fa-solid fa-eye text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Role --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-2">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-300 hover:bg-blue-50 transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                <input type="radio" name="role" value="kasir"
                                    {{ old('role') === 'kasir' ? 'checked' : '' }}
                                    onchange="updatePreview()" class="accent-blue-700 flex-shrink-0 w-4 h-4">
                                <div>
                                    <div class="flex items-center gap-1.5 mb-0.5">
                                        <i class="fa-solid fa-cash-register text-blue-600 text-xs"></i>
                                        <p class="text-sm font-bold text-gray-700">Kasir</p>
                                    </div>
                                    <p class="text-xs text-gray-400">Transaksi & peserta</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-purple-300 hover:bg-purple-50 transition has-[:checked]:border-purple-500 has-[:checked]:bg-purple-50">
                                <input type="radio" name="role" value="admin"
                                    {{ old('role') === 'admin' ? 'checked' : '' }}
                                    onchange="updatePreview()" class="accent-purple-700 flex-shrink-0 w-4 h-4">
                                <div>
                                    <div class="flex items-center gap-1.5 mb-0.5">
                                        <i class="fa-solid fa-user-shield text-purple-600 text-xs"></i>
                                        <p class="text-sm font-bold text-gray-700">Admin</p>
                                    </div>
                                    <p class="text-xs text-gray-400">Akses penuh sistem</p>
                                </div>
                            </label>
                        </div>
                        @error('role')
                            <p class="text-xs text-red-500 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-2">Status</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-green-300 hover:bg-green-50 transition has-[:checked]:border-green-500 has-[:checked]:bg-green-50">
                                <input type="radio" name="status" value="aktif"
                                    {{ old('status', 'aktif') === 'aktif' ? 'checked' : '' }}
                                    onchange="updatePreview()" class="accent-green-600 flex-shrink-0 w-4 h-4">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-green-500 flex-shrink-0"></span>
                                    <p class="text-sm font-bold text-gray-700">Aktif</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-red-300 hover:bg-red-50 transition has-[:checked]:border-red-400 has-[:checked]:bg-red-50">
                                <input type="radio" name="status" value="nonaktif"
                                    {{ old('status') === 'nonaktif' ? 'checked' : '' }}
                                    onchange="updatePreview()" class="accent-red-500 flex-shrink-0 w-4 h-4">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-red-400 flex-shrink-0"></span>
                                    <p class="text-sm font-bold text-gray-700">Non-Aktif</p>
                                </div>
                            </label>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ===== KANAN: Preview + Aksi ===== --}}
            <div class="space-y-4">

                {{-- Preview Card --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                        <div class="w-1 h-5 bg-primary-700 rounded-full"></div>
                        <h3 class="text-sm font-bold text-gray-700">Preview User</h3>
                    </div>
                    <div class="p-5 space-y-4">
                        <div class="flex justify-center">
                            <div id="prev_avatar" class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center transition-all">
                                <span id="prev_initial" class="font-bold text-2xl text-gray-400">?</span>
                            </div>
                        </div>
                        <div class="text-center">
                            <p id="prev_username" class="font-bold text-gray-800 text-base">—</p>
                            <p id="prev_nama" class="text-sm text-gray-500 mt-0.5">—</p>
                            <p id="prev_email" class="text-xs text-gray-400 mt-0.5 break-all">—</p>
                        </div>
                        <div class="flex flex-col items-center gap-2">
                            <div id="prev_role_badge">
                                <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-400 text-xs font-semibold px-3 py-1 rounded-full">
                                    <i class="fa-solid fa-user text-xs"></i> Belum dipilih
                                </span>
                            </div>
                            <div id="prev_status_badge">
                                <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 text-xs font-semibold px-3 py-1 rounded-full border border-green-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 space-y-3">
                    <button type="submit"
                        class="w-full px-5 py-2.5 text-sm font-medium text-white bg-primary-700 hover:bg-primary-800 rounded-xl shadow transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-save"></i> Simpan User
                    </button>
                    <a href="{{ url('/admin/user') }}"
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
                            <p>Password minimal 8 karakter.</p>
                            <p>User <b>Non-Aktif</b> tidak bisa login ke sistem.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon  = document.getElementById(iconId);
            if (input.type === 'password') { input.type = 'text'; icon.classList.replace('fa-eye','fa-eye-slash'); }
            else { input.type = 'password'; icon.classList.replace('fa-eye-slash','fa-eye'); }
        }

        function updatePreview() {
            const username = document.getElementById('inp_username').value;
            const nama     = document.getElementById('inp_nama').value;
            const email    = document.getElementById('inp_email').value;
            const role     = document.querySelector('[name="role"]:checked')?.value ?? '';
            const status   = document.querySelector('[name="status"]:checked')?.value ?? 'aktif';

            document.getElementById('prev_username').textContent = username || '—';
            document.getElementById('prev_nama').textContent     = nama     || '—';
            document.getElementById('prev_email').textContent    = email    || '—';
            document.getElementById('prev_initial').textContent  = username ? username.charAt(0).toUpperCase() : '?';

            const avatar  = document.getElementById('prev_avatar');
            const initial = document.getElementById('prev_initial');
            const roleMap = {
                admin: ['bg-purple-100', 'text-purple-700'],
                kasir: ['bg-primary-100', 'text-primary-700'],
            };
            if (roleMap[role]) {
                avatar.className  = `w-16 h-16 rounded-full ${roleMap[role][0]} flex items-center justify-center transition-all`;
                initial.className = `font-bold text-2xl ${roleMap[role][1]}`;
            } else {
                avatar.className  = 'w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center transition-all';
                initial.className = 'font-bold text-2xl text-gray-400';
            }

            const roleBadge = document.getElementById('prev_role_badge');
            const roleHTML = {
                admin: `<span class="inline-flex items-center gap-1.5 bg-purple-50 text-purple-700 text-xs font-semibold px-3 py-1 rounded-full border border-purple-100"><i class="fa-solid fa-user-shield text-xs"></i> Admin</span>`,
                kasir: `<span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full border border-blue-100"><i class="fa-solid fa-cash-register text-xs"></i> Kasir</span>`,
            };
            roleBadge.innerHTML = roleHTML[role] ?? `<span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-400 text-xs font-semibold px-3 py-1 rounded-full"><i class="fa-solid fa-user text-xs"></i> Belum dipilih</span>`;

            document.getElementById('prev_status_badge').innerHTML = status === 'nonaktif'
                ? `<span class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 text-xs font-semibold px-3 py-1 rounded-full border border-red-100"><span class="w-1.5 h-1.5 rounded-full bg-red-400"></span> Non-Aktif</span>`
                : `<span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 text-xs font-semibold px-3 py-1 rounded-full border border-green-100"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif</span>`;
        }

        updatePreview();
    </script>

@endsection