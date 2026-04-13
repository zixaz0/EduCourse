@extends('Layout.admin')

@section('content')

    <div class="mb-6">
        <a href="{{ route('admin.guru.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-primary-700 transition mb-3">
            <i class="fa-solid fa-arrow-left text-xs"></i> Kembali ke Data Guru
        </a>
        <h1 class="text-xl font-bold text-gray-800">Edit Guru</h1>
        <p class="text-sm text-gray-500 mt-0.5">Perbarui informasi data guru</p>
    </div>

    <form method="POST" action="{{ route('admin.guru.update', $guru->id) }}" id="formGuru">
        @csrf
        @method('PUT')
        <div class="flex flex-col lg:flex-row gap-6">

            <div class="flex-1 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

                <div class="flex items-center gap-3 px-6 py-4 bg-primary-700">
                    <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                        <i class="fa-solid fa-chalkboard-user text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="text-white font-bold text-sm">Form Edit Guru</p>
                        <p class="text-white/70 text-xs">Kolom bertanda <span class="text-red-300">*</span> wajib diisi</p>
                    </div>
                </div>

                <div class="px-6 py-6 grid grid-cols-1 sm:grid-cols-2 gap-5">

                    <div class="sm:col-span-2">
                        <label class="text-xs font-medium text-gray-500 mb-1.5 block">Nama Lengkap <span class="text-red-400">*</span></label>
                        <input type="text" name="nama" id="inp_nama" value="{{ old('nama', $guru->nama) }}" required
                            placeholder="Masukkan nama lengkap guru" oninput="updatePreview()"
                            class="w-full px-4 py-2.5 text-sm border {{ $errors->has('nama') ? 'border-red-400' : 'border-gray-200' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-300" />
                        @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-500 mb-1.5 block">No. HP <span class="text-red-400">*</span></label>
                        <input type="text" name="no_hp" id="inp_nohp" value="{{ old('no_hp', $guru->no_hp) }}" required
                            placeholder="Contoh: 081234567890" oninput="updatePreview()"
                            class="w-full px-4 py-2.5 text-sm border {{ $errors->has('no_hp') ? 'border-red-400' : 'border-gray-200' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-300" />
                        @error('no_hp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-500 mb-1.5 block">Email <span class="text-red-400">*</span></label>
                        <input type="email" name="email" id="inp_email" value="{{ old('email', $guru->email) }}" required
                            placeholder="Contoh: guru@email.com" oninput="updatePreview()"
                            class="w-full px-4 py-2.5 text-sm border {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-300" />
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="text-xs font-medium text-gray-500 mb-2 block">Jenis Kelamin <span class="text-red-400">*</span></label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="jk-option flex items-center gap-3 border-2 border-gray-200 rounded-xl px-4 py-3 cursor-pointer transition hover:border-primary-300"
                                id="label_laki">
                                <input type="radio" name="jenis_kelamin" value="laki-laki" class="hidden jk-radio"
                                    {{ old('jenis_kelamin', $guru->jenis_kelamin) === 'laki-laki' ? 'checked' : '' }}
                                    onchange="updatePreview()">
                                <i class="fa-solid fa-mars text-blue-500"></i>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">Laki-laki</p>
                                </div>
                            </label>
                            <label class="jk-option flex items-center gap-3 border-2 border-gray-200 rounded-xl px-4 py-3 cursor-pointer transition hover:border-primary-300"
                                id="label_perempuan">
                                <input type="radio" name="jenis_kelamin" value="perempuan" class="hidden jk-radio"
                                    {{ old('jenis_kelamin', $guru->jenis_kelamin) === 'perempuan' ? 'checked' : '' }}
                                    onchange="updatePreview()">
                                <i class="fa-solid fa-venus text-pink-500"></i>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">Perempuan</p>
                                </div>
                            </label>
                        </div>
                        @error('jenis_kelamin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                </div>
            </div>

            <div class="w-full lg:w-72 flex flex-col gap-4">
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                    <p class="text-sm font-bold text-gray-700 mb-4 border-l-4 border-primary-700 pl-3">Preview Guru</p>

                    <div class="flex flex-col items-center gap-3 py-4">
                        <div id="prev_avatar" class="w-16 h-16 rounded-full flex items-center justify-center bg-primary-100">
                            <span id="prev_initial" class="font-bold text-2xl text-primary-700">
                                {{ strtoupper(substr($guru->nama, 0, 1)) }}
                            </span>
                        </div>
                        <div class="text-center">
                            <p class="font-semibold text-gray-800 text-sm" id="prev_nama">{{ $guru->nama }}</p>
                            <p class="text-xs text-gray-400 mt-0.5" id="prev_email">{{ $guru->email }}</p>
                            <p class="text-xs text-gray-400 mt-0.5" id="prev_nohp">{{ $guru->no_hp }}</p>
                        </div>
                        <div id="prev_jk_wrap" class="mt-1"></div>
                    </div>
                </div>

                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 bg-primary-700 hover:bg-primary-800 text-white font-semibold text-sm px-4 py-3 rounded-xl transition">
                    <i class="fa-solid fa-floppy-disk"></i> Perbarui Guru
                </button>
                <a href="{{ route('admin.guru.index') }}"
                    class="w-full flex items-center justify-center gap-2 bg-white hover:bg-gray-50 text-gray-600 font-medium text-sm px-4 py-3 rounded-xl border border-gray-200 transition">
                    <i class="fa-solid fa-xmark"></i> Batal
                </a>
            </div>

        </div>
    </form>

    <script>
        function updatePreview() {
            const nama  = document.getElementById('inp_nama').value;
            const email = document.getElementById('inp_email').value;
            const nohp  = document.getElementById('inp_nohp').value;
            const jk    = document.querySelector('input[name="jenis_kelamin"]:checked')?.value ?? 'laki-laki';

            document.getElementById('prev_nama').textContent    = nama  || '—';
            document.getElementById('prev_email').textContent   = email || '—';
            document.getElementById('prev_nohp').textContent    = nohp  || '—';
            document.getElementById('prev_initial').textContent = nama ? nama.charAt(0).toUpperCase() : '?';

            const avatar  = document.getElementById('prev_avatar');
            const initial = document.getElementById('prev_initial');

            if (jk === 'perempuan') {
                avatar.className  = 'w-16 h-16 rounded-full bg-pink-100 flex items-center justify-center';
                initial.className = 'font-bold text-2xl text-pink-600';
                document.getElementById('prev_jk_wrap').innerHTML = `
                    <span class="inline-flex items-center gap-1.5 bg-pink-50 text-pink-600 text-xs font-semibold px-3 py-1 rounded-full border border-pink-100">
                        <i class="fa-solid fa-venus text-xs"></i> Perempuan
                    </span>`;
            } else {
                avatar.className  = 'w-16 h-16 rounded-full bg-primary-100 flex items-center justify-center';
                initial.className = 'font-bold text-2xl text-primary-700';
                document.getElementById('prev_jk_wrap').innerHTML = `
                    <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-600 text-xs font-semibold px-3 py-1 rounded-full border border-blue-100">
                        <i class="fa-solid fa-mars text-xs"></i> Laki-laki
                    </span>`;
            }

            document.querySelectorAll('.jk-option').forEach(el => {
                el.classList.remove('border-primary-500', 'bg-primary-50');
                el.classList.add('border-gray-200');
            });
            const checked = document.querySelector('input[name="jenis_kelamin"]:checked');
            if (checked) {
                const parentLabel = checked.closest('.jk-option');
                parentLabel.classList.add('border-primary-500', 'bg-primary-50');
                parentLabel.classList.remove('border-gray-200');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            updatePreview();
        });
    </script>

@endsection