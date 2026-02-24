@extends('Layout.admin')

@section('content')

    <div class="mb-6">
        <a href="{{ url('/admin/kelas') }}"
            class="inline-flex items-center gap-2 text-sm text-primary-700 hover:text-primary-900 font-medium transition">
            <i class="fa-solid fa-arrow-left text-xs"></i> Kembali ke Data Kelas
        </a>
        <h1 class="text-xl font-bold text-gray-800 mt-2">Edit Kelas</h1>
        <p class="text-sm text-gray-500 mt-0.5">Mengubah data kelas <span class="font-semibold text-primary-700">{{ $kelas->nama_kelas }}</span></p>
    </div>

    <div class="max-w-lg">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

            <div class="px-6 py-4 bg-primary-700 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fa-solid fa-chalkboard-user text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-white font-bold text-sm">{{ $kelas->nama_kelas }}</p>
                    <p class="text-blue-200 text-xs">Rp {{ number_format($kelas->harga_kelas, 0, ',', '.') }} / bulan</p>
                </div>
            </div>

            <form method="POST" action="{{ url('/admin/kelas/' . $kelas->id) }}" class="px-6 py-6 space-y-5">
                @csrf
                @method('PUT')

                {{-- Nama Kelas --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Nama Kelas <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_kelas" value="{{ old('nama_kelas', $kelas->nama_kelas) }}"
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
                        <input type="number" name="harga_kelas" value="{{ old('harga_kelas', $kelas->harga_kelas) }}"
                            min="0" step="1000"
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
                        // Support old() untuk validasi fail, atau dari database
                        $oldHari = old('hari_kelas')
                            ? old('hari_kelas')
                            : collect(explode(',', $kelas->hari_kelas))->map(fn($h) => trim($h))->toArray();
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
                                <span class="inline-block text-xs font-semibold px-4 py-2 rounded-xl border-2 border-gray-200 text-gray-500 bg-white
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

                {{-- Buttons --}}
                <div class="flex items-center justify-end gap-3 pt-1">
                    <a href="{{ url('/admin/kelas') }}"
                        class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                        <i class="fa-solid fa-xmark mr-1.5"></i> Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 text-sm font-medium text-white bg-primary-700 hover:bg-primary-800 rounded-xl shadow transition">
                        <i class="fa-solid fa-save mr-1.5"></i> Update Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection