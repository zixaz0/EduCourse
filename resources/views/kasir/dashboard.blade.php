@extends('Layout.kasir')

@section('content')

    {{-- Page Title --}}
    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-800">Dasboard Kasir</h1>
        <p class="text-sm text-gray-500 mt-0.5">
            Hi <span class="font-semibold text-primary-700">{{ Auth::user()->username ?? Auth::user()->name ?? 'Kasir' }}</span>, selamat datang!
        </p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

        {{-- Total Kelas --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition">
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Kelas</p>
                <h2 class="text-3xl font-bold text-primary-700 mt-1">{{ $totalKelas ?? 0 }}</h2>
            </div>
            <div class="w-12 h-12 rounded-xl bg-primary-50 flex items-center justify-center">
                <i class="fa-solid fa-door-open text-primary-600 text-xl"></i>
            </div>
        </div>

        {{-- Peserta Aktif --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition">
            <div>
                <p class="text-sm text-gray-500 font-medium">Peserta Aktif</p>
                <h2 class="text-3xl font-bold text-primary-700 mt-1">{{ $pesertaAktif ?? 0 }}</h2>
            </div>
            <div class="w-12 h-12 rounded-xl bg-primary-50 flex items-center justify-center">
                <i class="fa-solid fa-user-group text-primary-600 text-xl"></i>
            </div>
        </div>

        {{-- Tagihan belum Dibayar --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-5 flex items-center justify-between shadow-sm hover:shadow-md transition">
            <div>
                <p class="text-sm text-gray-500 font-medium">Tagihan belum Dibayar</p>
                <h2 class="text-3xl font-bold text-primary-700 mt-1">{{ $tagihanBelumDibayar ?? 0 }}</h2>
            </div>
            <div class="w-12 h-12 rounded-xl bg-primary-50 flex items-center justify-center">
                <i class="fa-solid fa-dollar-sign text-primary-600 text-xl"></i>
            </div>
        </div>

    </div>

@endsection