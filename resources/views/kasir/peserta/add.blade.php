@extends('Layout.kasir')

@section('content')

    {{-- Breadcrumb / Back --}}
    <div class="mb-6">
        <a href="{{ url('/kasir/peserta') }}"
            class="inline-flex items-center gap-2 text-sm text-primary-700 hover:text-primary-900 font-medium transition">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            Kembali ke Data Peserta
        </a>
        <h1 class="text-xl font-bold text-gray-800 mt-2">Tambah Peserta Baru</h1>
        <p class="text-sm text-gray-500 mt-0.5">Isi data peserta dengan lengkap dan benar</p>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

        {{-- Card Header --}}
        <div class="px-6 py-4 bg-primary-700 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                <i class="fa-solid fa-user-plus text-white text-sm"></i>
            </div>
            <div>
                <p class="text-white font-bold text-sm">Form Pendaftaran Peserta</p>
                <p class="text-blue-200 text-xs">Kolom bertanda <span class="text-red-300 font-bold">*</span> wajib diisi</p>
            </div>
        </div>

        {{-- Form Body --}}
        <div class="px-6 py-6">

            {{-- Section: Data Pribadi --}}
            <div class="mb-6">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-1 h-5 bg-primary-700 rounded-full"></div>
                    <h2 class="text-sm font-bold text-gray-700">Data Pribadi</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" placeholder="Masukkan nama lengkap"
                            class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" placeholder="contoh@email.com"
                            class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                            No. HP <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">+62</span>
                            <input type="text" name="no_hp" placeholder="8xxxxxxxxxx"
                                class="w-full text-sm border border-gray-200 rounded-xl pl-12 pr-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Status</label>
                        <select name="status"
                            class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent bg-white transition">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Non-Aktif</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Divider --}}
            <hr class="border-gray-100 mb-6">

            {{-- Section: Data Orang Tua --}}
            <div class="mb-6">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-1 h-5 bg-primary-700 rounded-full"></div>
                    <h2 class="text-sm font-bold text-gray-700">Data Orang Tua / Wali</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Orang Tua / Wali</label>
                        <input type="text" name="nama_orangtua" placeholder="Masukkan nama orang tua"
                            class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">No. HP Orang Tua / Wali</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">+62</span>
                            <input type="text" name="no_orangtua" placeholder="8xxxxxxxxxx"
                                class="w-full text-sm border border-gray-200 rounded-xl pl-12 pr-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent transition">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Divider --}}
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
                                class="w-4 h-4 accent-primary-700 flex-shrink-0">
                            <div>
                                <p class="text-sm font-semibold text-gray-700 group-hover:text-primary-700 transition">{{ $kelas->nama_kelas }}</p>
                                @if(isset($kelas->deskripsi))
                                    <p class="text-xs text-gray-400">{{ $kelas->deskripsi }}</p>
                                @endif
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
                <a href="{{ url('/kasir/peserta') }}"
                    class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                    <i class="fa-solid fa-xmark mr-1.5"></i> Batal
                </a>
                <button type="button" onclick="submitForm()"
                    class="px-6 py-2.5 text-sm font-medium text-white bg-primary-700 hover:bg-primary-800 rounded-xl shadow transition">
                    <i class="fa-solid fa-save mr-1.5"></i> Simpan Peserta
                </button>
            </div>
        </div>
    </div>

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
            });
        }
    </script>

@endsection