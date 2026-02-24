@extends('Layout.kasir')

@section('content')

    {{-- Breadcrumb / Back --}}
    <div class="mb-6">
        <a href="{{ url('/kasir/peserta') }}"
            class="inline-flex items-center gap-2 text-sm text-primary-700 hover:text-primary-900 font-medium transition">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            Kembali ke Data Peserta
        </a>
        <h1 class="text-xl font-bold text-gray-800 mt-2">Edit Peserta</h1>
        <p class="text-sm text-gray-500 mt-0.5">
            Mengubah data peserta
            <span class="font-semibold text-primary-700">{{ $peserta->nama ?? '-' }}</span>
        </p>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

        {{-- Card Header --}}
        <div class="px-6 py-4 bg-primary-700 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                <span class="text-white font-bold text-base">{{ strtoupper(substr($peserta->nama ?? 'P', 0, 1)) }}</span>
            </div>
            <div>
                <p class="text-white font-bold text-sm">{{ $peserta->nama ?? 'Nama Peserta' }}</p>
                <p class="text-blue-200 text-xs">{{ $peserta->email ?? '-' }}</p>
            </div>
            <div class="ml-auto">
                @if(isset($peserta) && strtolower($peserta->status) === 'aktif')
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
                        <input type="text" name="nama" value="{{ $peserta->nama ?? '' }}" placeholder="Masukkan nama lengkap"
                            class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ $peserta->email ?? '' }}" placeholder="contoh@email.com"
                            class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                            No. HP <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">+62</span>
                            <input type="text" name="no_hp" value="{{ $peserta->no_hp ?? '' }}" placeholder="8xxxxxxxxxx"
                                class="w-full text-sm border border-gray-200 rounded-xl pl-12 pr-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Status</label>
                        <select name="status"
                            class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent bg-white transition">
                            <option value="aktif" {{ (isset($peserta) && strtolower($peserta->status) === 'aktif') ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ (isset($peserta) && strtolower($peserta->status) === 'nonaktif') ? 'selected' : '' }}>Non-Aktif</option>
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
                        <input type="text" name="nama_orangtua" value="{{ $peserta->nama_orangtua ?? '' }}" placeholder="Masukkan nama orang tua"
                            class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">No. HP Orang Tua / Wali</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">+62</span>
                            <input type="text" name="no_orangtua" value="{{ $peserta->no_orangtua ?? '' }}" placeholder="8xxxxxxxxxx"
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

                {{-- Kelas yang sedang diikuti --}}
                @php
                    $kelasAktifIds = isset($peserta) ? $peserta->kelas->pluck('id')->toArray() : [];
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @forelse($kelasList ?? [] as $kelas)
                        <label class="flex items-center gap-3 p-3 border rounded-xl cursor-pointer transition group
                            {{ in_array($kelas->id, $kelasAktifIds)
                                ? 'border-primary-400 bg-primary-50'
                                : 'border-gray-200 hover:border-primary-300 hover:bg-primary-50' }}">
                            <input type="checkbox" name="kelas[]" value="{{ $kelas->id }}"
                                {{ in_array($kelas->id, $kelasAktifIds) ? 'checked' : '' }}
                                class="w-4 h-4 accent-primary-700 flex-shrink-0">
                            <div>
                                <p class="text-sm font-semibold text-gray-700 group-hover:text-primary-700 transition">{{ $kelas->nama_kelas }}</p>
                                @if(isset($kelas->deskripsi))
                                    <p class="text-xs text-gray-400">{{ $kelas->deskripsi }}</p>
                                @endif
                            </div>
                            @if(in_array($kelas->id, $kelasAktifIds))
                                <i class="fa-solid fa-circle-check text-primary-600 ml-auto text-sm"></i>
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
            <div class="flex items-center justify-between pt-2">
                {{-- Danger zone: delete --}}
                <button type="button" onclick="confirmDelete('{{ $peserta->nama ?? '' }}')"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-red-500 bg-red-50 hover:bg-red-100 rounded-xl transition">
                    <i class="fa-solid fa-trash text-xs"></i> Hapus Peserta
                </button>

                <div class="flex items-center gap-3">
                    <a href="{{ url('/kasir/peserta') }}"
                        class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                        <i class="fa-solid fa-xmark mr-1.5"></i> Batal
                    </a>
                    <button type="button" onclick="submitForm()"
                        class="px-6 py-2.5 text-sm font-medium text-white bg-primary-700 hover:bg-primary-800 rounded-xl shadow transition">
                        <i class="fa-solid fa-save mr-1.5"></i> Update Peserta
                    </button>
                </div>
            </div>
        </div>
    </div>

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
            });
        }

        function confirmDelete(nama) {
            Swal.fire({
                title: 'Hapus Peserta?',
                html: `Data <b>${nama}</b> akan dihapus permanen.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
            });
        }
    </script>

@endsection