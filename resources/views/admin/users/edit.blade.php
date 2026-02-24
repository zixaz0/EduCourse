@extends('Layout.admin')

@section('content')

    <div class="mb-6">
        <a href="{{ url('/admin/user') }}"
            class="inline-flex items-center gap-2 text-sm text-primary-700 hover:text-primary-900 font-medium transition">
            <i class="fa-solid fa-arrow-left text-xs"></i> Kembali ke Manajemen User
        </a>
        <h1 class="text-xl font-bold text-gray-800 mt-2">Edit User</h1>
        <p class="text-sm text-gray-500 mt-0.5">Mengubah data akun <span class="font-semibold text-primary-700">{{ $user->username }}</span></p>
    </div>

    <div class="max-w-lg">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

            {{-- Header dengan info user --}}
            <div class="px-6 py-4 bg-primary-700 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full {{ $user->role === 'admin' ? 'bg-purple-400/30' : 'bg-white/20' }} flex items-center justify-center flex-shrink-0">
                    <span class="text-white font-bold text-base">{{ strtoupper(substr($user->username, 0, 1)) }}</span>
                </div>
                <div class="flex-1">
                    <p class="text-white font-bold text-sm">{{ $user->username }}</p>
                    <p class="text-blue-200 text-xs">{{ $user->email }}</p>
                </div>
                <div>
                    @if($user->status === 'aktif')
                        <span class="inline-flex items-center gap-1.5 bg-green-400/20 text-green-200 text-xs font-semibold px-3 py-1 rounded-full border border-green-300/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span> Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 bg-red-400/20 text-red-200 text-xs font-semibold px-3 py-1 rounded-full border border-red-300/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span> Non-Aktif
                        </span>
                    @endif
                </div>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ url('/admin/user/' . $user->id) }}" class="px-6 py-6 space-y-5">
                @csrf
                @method('PUT')

                {{-- Username --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Username <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}"
                        class="w-full text-sm border @error('username') border-red-400 @else border-gray-200 @enderror rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 transition">
                    @error('username')
                        <p class="text-xs text-red-500 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        class="w-full text-sm border @error('email') border-red-400 @else border-gray-200 @enderror rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 transition">
                    @error('email')
                        <p class="text-xs text-red-500 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password (opsional) --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Password Baru <span class="text-gray-400 font-normal">(kosongkan jika tidak diubah)</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password"
                            placeholder="Masukkan password baru"
                            class="w-full text-sm border @error('password') border-red-400 @else border-gray-200 @enderror rounded-xl px-4 py-2.5 pr-11 focus:outline-none focus:ring-2 focus:ring-primary-300 transition">
                        <button type="button" onclick="togglePassword('password', 'eye-pass')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                            <i id="eye-pass" class="fa-solid fa-eye text-sm"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs text-red-500 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Konfirmasi Password Baru</label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirm"
                            placeholder="Ulangi password baru"
                            class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 pr-11 focus:outline-none focus:ring-2 focus:ring-primary-300 transition">
                        <button type="button" onclick="togglePassword('password_confirm', 'eye-confirm')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                            <i id="eye-confirm" class="fa-solid fa-eye text-sm"></i>
                        </button>
                    </div>
                </div>

                {{-- Role --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:border-primary-300 hover:bg-primary-50 transition group has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50">
                            <input type="radio" name="role" value="kasir"
                                {{ old('role', $user->role) === 'kasir' ? 'checked' : '' }}
                                class="accent-blue-700 flex-shrink-0">
                            <div>
                                <p class="text-sm font-semibold text-gray-700 group-hover:text-primary-700">Kasir</p>
                                <p class="text-xs text-gray-400">Akses transaksi & peserta</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:border-purple-300 hover:bg-purple-50 transition group has-[:checked]:border-purple-500 has-[:checked]:bg-purple-50">
                            <input type="radio" name="role" value="admin"
                                {{ old('role', $user->role) === 'admin' ? 'checked' : '' }}
                                class="accent-purple-700 flex-shrink-0">
                            <div>
                                <p class="text-sm font-semibold text-gray-700 group-hover:text-purple-700">Admin</p>
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
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Status</label>
                    <select name="status"
                        class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white transition">
                        <option value="aktif"     {{ old('status', $user->status) === 'aktif'     ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif"  {{ old('status', $user->status) === 'nonaktif'  ? 'selected' : '' }}>Non-Aktif</option>
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="flex items-center justify-end gap-3 pt-1">
                    <a href="{{ url('/admin/user') }}"
                        class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                        <i class="fa-solid fa-xmark mr-1.5"></i> Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 text-sm font-medium text-white bg-primary-700 hover:bg-primary-800 rounded-xl shadow transition">
                        <i class="fa-solid fa-save mr-1.5"></i> Update User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon  = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>

@endsection