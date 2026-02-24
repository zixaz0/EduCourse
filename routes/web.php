<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Owner\OwnerDashboardController;
use App\Http\Controllers\Kasir\KasirDashboardController;

// Auth
Route::get('/', fn() => redirect()->route('login'));
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});

// Owner
Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/owner/dashboard', [OwnerDashboardController::class, 'index'])->name('owner.dashboard');
});

Route::prefix('kasir')->middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('kasir.dashboard');
    })->name('kasir.dashboard');


    // ========================
    // PESERTA ROUTES
    // ========================
    Route::prefix('peserta')->group(function () {

        // ---- DATA DUMMY ----
        $dummyKelas = collect([
            (object)['id' => 1, 'nama_kelas' => 'Python Dasar'],
            (object)['id' => 2, 'nama_kelas' => 'Web Design'],
            (object)['id' => 3, 'nama_kelas' => 'Matematika'],
            (object)['id' => 4, 'nama_kelas' => 'Bahasa Inggris'],
            (object)['id' => 5, 'nama_kelas' => 'Desain Grafis'],
        ]);

        $dummyPeserta = collect([
            (object)[
                'id'            => 1,
                'nama'          => 'Andi Saputra',
                'email'         => 'andi@email.com',
                'no_hp'         => '081234567890',
                'nama_orangtua' => 'Hendra Saputra',
                'no_orangtua'   => '081234500000',
                'status'        => 'aktif',
                'kelas'         => collect([
                    (object)['id' => 1, 'nama_kelas' => 'Python Dasar'],
                    (object)['id' => 2, 'nama_kelas' => 'Web Design'],
                ]),
            ],
            (object)[
                'id'            => 2,
                'nama'          => 'Budi Santoso',
                'email'         => 'budi@email.com',
                'no_hp'         => '089876543210',
                'nama_orangtua' => 'Agus Santoso',
                'no_orangtua'   => '089876500000',
                'status'        => 'aktif',
                'kelas'         => collect([
                    (object)['id' => 3, 'nama_kelas' => 'Matematika'],
                ]),
            ],
            (object)[
                'id'            => 3,
                'nama'          => 'Citra Dewi',
                'email'         => 'citra@email.com',
                'no_hp'         => '082345678901',
                'nama_orangtua' => 'Sari Dewi',
                'no_orangtua'   => '082345600000',
                'status'        => 'nonaktif',
                'kelas'         => collect([
                    (object)['id' => 1, 'nama_kelas' => 'Python Dasar'],
                ]),
            ],
            (object)[
                'id'            => 4,
                'nama'          => 'Deni Firmansyah',
                'email'         => 'deni@email.com',
                'no_hp'         => '085678901234',
                'nama_orangtua' => 'Firman Wahyu',
                'no_orangtua'   => '085678900000',
                'status'        => 'aktif',
                'kelas'         => collect([
                    (object)['id' => 2, 'nama_kelas' => 'Web Design'],
                    (object)['id' => 3, 'nama_kelas' => 'Matematika'],
                ]),
            ],
            (object)[
                'id'            => 5,
                'nama'          => 'Erika Putri',
                'email'         => 'erika@email.com',
                'no_hp'         => '087654321098',
                'nama_orangtua' => 'Putri Handayani',
                'no_orangtua'   => '087654300000',
                'status'        => 'aktif',
                'kelas'         => collect([
                    (object)['id' => 4, 'nama_kelas' => 'Bahasa Inggris'],
                    (object)['id' => 5, 'nama_kelas' => 'Desain Grafis'],
                ]),
            ],
            (object)[
                'id'            => 6,
                'nama'          => 'Fajar Nugroho',
                'email'         => 'fajar@email.com',
                'no_hp'         => '083456789012',
                'nama_orangtua' => 'Nugroho Aji',
                'no_orangtua'   => '083456700000',
                'status'        => 'nonaktif',
                'kelas'         => collect([
                    (object)['id' => 3, 'nama_kelas' => 'Matematika'],
                    (object)['id' => 4, 'nama_kelas' => 'Bahasa Inggris'],
                ]),
            ],
            (object)[
                'id'            => 7,
                'nama'          => 'Gita Rahayu',
                'email'         => 'gita@email.com',
                'no_hp'         => '081987654321',
                'nama_orangtua' => 'Rahayu Budi',
                'no_orangtua'   => '081987600000',
                'status'        => 'aktif',
                'kelas'         => collect([
                    (object)['id' => 1, 'nama_kelas' => 'Python Dasar'],
                    (object)['id' => 5, 'nama_kelas' => 'Desain Grafis'],
                ]),
            ],
        ]);
        // ---- END DATA DUMMY ----

        Route::get('/', function () use ($dummyPeserta, $dummyKelas) {
            $peserta   = $dummyPeserta;
            $kelasList = $dummyKelas;
            return view('kasir.peserta.index', compact('peserta', 'kelasList'));
        })->name('kasir.peserta.index');

        Route::get('/add', function () use ($dummyKelas) {
            $kelasList = $dummyKelas;
            return view('kasir.peserta.add', compact('kelasList'));
        })->name('kasir.peserta.add');

        Route::get('/{id}/edit', function ($id) use ($dummyPeserta, $dummyKelas) {
            $peserta   = $dummyPeserta->firstWhere('id', (int) $id);
            $kelasList = $dummyKelas;
            if (!$peserta) abort(404, 'Peserta tidak ditemukan.');
            return view('kasir.peserta.edit', compact('peserta', 'kelasList'));
        })->name('kasir.peserta.edit');

    }); // ← tutup prefix('peserta')


    // ========================
    // TRANSAKSI ROUTES
    // ========================
    Route::prefix('transaksi')->group(function () {

        // ---- DATA DUMMY ----
        $dummyKelas = collect([
            (object)['id' => 1, 'nama_kelas' => 'Python Dasar'],
            (object)['id' => 2, 'nama_kelas' => 'Web Design'],
            (object)['id' => 3, 'nama_kelas' => 'Matematika'],
            (object)['id' => 4, 'nama_kelas' => 'Bahasa Inggris'],
            (object)['id' => 5, 'nama_kelas' => 'Desain Grafis'],
        ]);

        $dummyTagihan = collect([
            (object)[
                'id'            => 1,
                'bulan_tahun'   => 'Februari/2026',
                'total_tagihan' => 400000,
                'status'        => 'belum lunas',
                'peserta'       => (object)[
                    'nama'  => 'Andi Saputra',
                    'kelas' => collect([(object)['nama_kelas' => 'Matematika']]),
                ],
            ],
            (object)[
                'id'            => 2,
                'bulan_tahun'   => 'Februari/2026',
                'total_tagihan' => 350000,
                'status'        => 'belum lunas',
                'peserta'       => (object)[
                    'nama'  => 'Siti Maimunah',
                    'kelas' => collect([(object)['nama_kelas' => 'Bahasa Inggris']]),
                ],
            ],
            (object)[
                'id'            => 3,
                'bulan_tahun'   => 'Januari/2026',
                'total_tagihan' => 400000,
                'status'        => 'lunas',
                'peserta'       => (object)[
                    'nama'  => 'Rizky Pratama',
                    'kelas' => collect([(object)['nama_kelas' => 'Matematika']]),
                ],
            ],
            (object)[
                'id'            => 4,
                'bulan_tahun'   => 'Februari/2026',
                'total_tagihan' => 400000,
                'status'        => 'belum lunas',
                'peserta'       => (object)[
                    'nama'  => 'Rizky Pratama',
                    'kelas' => collect([(object)['nama_kelas' => 'Matematika']]),
                ],
            ],
            (object)[
                'id'            => 5,
                'bulan_tahun'   => 'Februari/2026',
                'total_tagihan' => 350000,
                'status'        => 'belum lunas',
                'peserta'       => (object)[
                    'nama'  => 'Fajar Nugroho',
                    'kelas' => collect([(object)['nama_kelas' => 'Bahasa Inggris']]),
                ],
            ],
            (object)[
                'id'            => 6,
                'bulan_tahun'   => 'Januari/2026',
                'total_tagihan' => 500000,
                'status'        => 'lunas',
                'peserta'       => (object)[
                    'nama'  => 'Erika Putri',
                    'kelas' => collect([
                        (object)['nama_kelas' => 'Bahasa Inggris'],
                        (object)['nama_kelas' => 'Desain Grafis'],
                    ]),
                ],
            ],
            (object)[
                'id'            => 7,
                'bulan_tahun'   => 'Februari/2026',
                'total_tagihan' => 500000,
                'status'        => 'belum lunas',
                'peserta'       => (object)[
                    'nama'  => 'Erika Putri',
                    'kelas' => collect([
                        (object)['nama_kelas' => 'Bahasa Inggris'],
                        (object)['nama_kelas' => 'Desain Grafis'],
                    ]),
                ],
            ],
            (object)[
                'id'            => 8,
                'bulan_tahun'   => 'Februari/2026',
                'total_tagihan' => 450000,
                'status'        => 'belum lunas',
                'peserta'       => (object)[
                    'nama'  => 'Gita Rahayu',
                    'kelas' => collect([
                        (object)['nama_kelas' => 'Python Dasar'],
                        (object)['nama_kelas' => 'Desain Grafis'],
                    ]),
                ],
            ],
        ]);
        // ---- END DATA DUMMY ----

        Route::get('/', function () use ($dummyTagihan, $dummyKelas) {
            $tagihan   = $dummyTagihan;
            $kelasList = $dummyKelas;
            return view('kasir.transaksi.index', compact('tagihan', 'kelasList'));
        })->name('kasir.transaksi.index');

        // BAYAR - Form Pembayaran
        Route::get('/{id}/bayar', function ($id) use ($dummyTagihan) {
            $tagihan = $dummyTagihan->firstWhere('id', (int) $id);
            if (!$tagihan) abort(404, 'Tagihan tidak ditemukan.');
            if (strtolower($tagihan->status) === 'lunas') {
                return redirect()->route('kasir.transaksi.index')
                    ->with('error', 'Tagihan ini sudah lunas.');
            }
            return view('kasir.transaksi.bayar', compact('tagihan'));
        })->name('kasir.transaksi.bayar');

    }); // ← tutup prefix('transaksi')

    // ========================
    // RIWAYAT TRANSAKSI
    // ========================
    Route::prefix('riwayat')->group(function () {

        // ---- DATA DUMMY ----
        $dummyKelas = collect([
            (object)['id' => 1, 'nama_kelas' => 'Python Dasar'],
            (object)['id' => 2, 'nama_kelas' => 'Web Design'],
            (object)['id' => 3, 'nama_kelas' => 'Matematika'],
            (object)['id' => 4, 'nama_kelas' => 'Bahasa Inggris'],
            (object)['id' => 5, 'nama_kelas' => 'Desain Grafis'],
        ]);

        $dummyRiwayat = collect([
            (object)[
                'id'          => 1,
                'nomor_unik'  => 'TRX-A1B2C3D4',
                'uang_bayar'  => 400000,
                'uang_kembali'=> 0,
                'created_at'  => '2026-02-01 09:15:00',
                'peserta'     => (object)['nama' => 'Andi Saputra'],
                'user'        => (object)['name' => 'Kasir 1'],
                'tagihan'     => (object)['bulan_tahun' => 'Januari/2026', 'total_tagihan' => 400000,
                    'peserta' => (object)['kelas' => collect([(object)['nama_kelas' => 'Matematika']])]],
            ],
            (object)[
                'id'          => 2,
                'nomor_unik'  => 'TRX-E5F6G7H8',
                'uang_bayar'  => 400000,
                'uang_kembali'=> 50000,
                'created_at'  => '2026-02-03 10:30:00',
                'peserta'     => (object)['nama' => 'Budi Santoso'],
                'user'        => (object)['name' => 'Kasir 1'],
                'tagihan'     => (object)['bulan_tahun' => 'Januari/2026', 'total_tagihan' => 350000,
                    'peserta' => (object)['kelas' => collect([(object)['nama_kelas' => 'Bahasa Inggris']])]],
            ],
            (object)[
                'id'          => 3,
                'nomor_unik'  => 'TRX-I9J0K1L2',
                'uang_bayar'  => 500000,
                'uang_kembali'=> 0,
                'created_at'  => '2026-02-05 08:45:00',
                'peserta'     => (object)['nama' => 'Citra Dewi'],
                'user'        => (object)['name' => 'Kasir 2'],
                'tagihan'     => (object)['bulan_tahun' => 'Januari/2026', 'total_tagihan' => 500000,
                    'peserta' => (object)['kelas' => collect([(object)['nama_kelas' => 'Python Dasar'], (object)['nama_kelas' => 'Web Design']])]],
            ],
            (object)[
                'id'          => 4,
                'nomor_unik'  => 'TRX-M3N4O5P6',
                'uang_bayar'  => 500000,
                'uang_kembali'=> 100000,
                'created_at'  => '2026-02-10 13:00:00',
                'peserta'     => (object)['nama' => 'Deni Firmansyah'],
                'user'        => (object)['name' => 'Kasir 1'],
                'tagihan'     => (object)['bulan_tahun' => 'Januari/2026', 'total_tagihan' => 400000,
                    'peserta' => (object)['kelas' => collect([(object)['nama_kelas' => 'Matematika']])]],
            ],
            (object)[
                'id'          => 5,
                'nomor_unik'  => 'TRX-Q7R8S9T0',
                'uang_bayar'  => 350000,
                'uang_kembali'=> 0,
                'created_at'  => '2026-02-12 11:20:00',
                'peserta'     => (object)['nama' => 'Erika Putri'],
                'user'        => (object)['name' => 'Kasir 2'],
                'tagihan'     => (object)['bulan_tahun' => 'Februari/2026', 'total_tagihan' => 350000,
                    'peserta' => (object)['kelas' => collect([(object)['nama_kelas' => 'Bahasa Inggris']])]],
            ],
            (object)[
                'id'          => 6,
                'nomor_unik'  => 'TRX-U1V2W3X4',
                'uang_bayar'  => 600000,
                'uang_kembali'=> 100000,
                'created_at'  => '2026-02-14 09:00:00',
                'peserta'     => (object)['nama' => 'Fajar Nugroho'],
                'user'        => (object)['name' => 'Kasir 1'],
                'tagihan'     => (object)['bulan_tahun' => 'Februari/2026', 'total_tagihan' => 500000,
                    'peserta' => (object)['kelas' => collect([(object)['nama_kelas' => 'Bahasa Inggris'], (object)['nama_kelas' => 'Desain Grafis']])]],
            ],
            (object)[
                'id'          => 7,
                'nomor_unik'  => 'TRX-Y5Z6A7B8',
                'uang_bayar'  => 450000,
                'uang_kembali'=> 0,
                'created_at'  => '2026-02-18 14:30:00',
                'peserta'     => (object)['nama' => 'Gita Rahayu'],
                'user'        => (object)['name' => 'Kasir 2'],
                'tagihan'     => (object)['bulan_tahun' => 'Februari/2026', 'total_tagihan' => 450000,
                    'peserta' => (object)['kelas' => collect([(object)['nama_kelas' => 'Python Dasar'], (object)['nama_kelas' => 'Desain Grafis']])]],
            ],
        ]);
        // ---- END DATA DUMMY ----

        Route::get('/', function () use ($dummyRiwayat, $dummyKelas) {
            $riwayat   = $dummyRiwayat;
            $kelasList = $dummyKelas;
            return view('kasir.riwayat.index', compact('riwayat', 'kelasList'));
        })->name('kasir.riwayat.index');

    }); // ← tutup prefix('riwayat')


    // ========================
    // LOG AKTIVITAS
    // ========================
    Route::prefix('log')->group(function () {

        $dummyUsers = collect([
            (object)['id' => 1, 'name' => 'Kasir 1', 'username' => 'kasir1', 'email' => 'kasir1@educourse.id'],
            (object)['id' => 2, 'name' => 'Kasir 2', 'username' => 'kasir2', 'email' => 'kasir2@educourse.id'],
            (object)['id' => 3, 'name' => 'Admin',   'username' => 'admin',  'email' => 'admin@educourse.id'],
        ]);

        $dummyLogs = collect([
            (object)['id' => 1,  'id_user' => 1, 'aktifitas' => 'Login ke sistem',                             'created_at' => now()->subMinutes(5),            'user' => (object)['name' => 'Kasir 1', 'email' => 'kasir1@educourse.id']],
            (object)['id' => 2,  'id_user' => 1, 'aktifitas' => 'Bayar tagihan Andi Saputra - TRX-A1B2C3D4',  'created_at' => now()->subMinutes(10),           'user' => (object)['name' => 'Kasir 1', 'email' => 'kasir1@educourse.id']],
            (object)['id' => 3,  'id_user' => 1, 'aktifitas' => 'Tambah peserta baru: Hendra Kurniawan',       'created_at' => now()->subMinutes(30),           'user' => (object)['name' => 'Kasir 1', 'email' => 'kasir1@educourse.id']],
            (object)['id' => 4,  'id_user' => 2, 'aktifitas' => 'Login ke sistem',                             'created_at' => now()->subHours(1),              'user' => (object)['name' => 'Kasir 2', 'email' => 'kasir2@educourse.id']],
            (object)['id' => 5,  'id_user' => 2, 'aktifitas' => 'Bayar tagihan Erika Putri - TRX-Q7R8S9T0',   'created_at' => now()->subHours(1)->subMinutes(10), 'user' => (object)['name' => 'Kasir 2', 'email' => 'kasir2@educourse.id']],
            (object)['id' => 6,  'id_user' => 2, 'aktifitas' => 'Edit data peserta: Citra Dewi',               'created_at' => now()->subHours(2),              'user' => (object)['name' => 'Kasir 2', 'email' => 'kasir2@educourse.id']],
            (object)['id' => 7,  'id_user' => 3, 'aktifitas' => 'Login ke sistem',                             'created_at' => now()->subHours(3),              'user' => (object)['name' => 'Admin',   'email' => 'admin@educourse.id']],
            (object)['id' => 8,  'id_user' => 3, 'aktifitas' => 'Hapus peserta: Budi Lama',                    'created_at' => now()->subHours(3)->subMinutes(5), 'user' => (object)['name' => 'Admin',   'email' => 'admin@educourse.id']],
            (object)['id' => 9,  'id_user' => 1, 'aktifitas' => 'Logout dari sistem',                          'created_at' => now()->subHours(4),              'user' => (object)['name' => 'Kasir 1', 'email' => 'kasir1@educourse.id']],
            (object)['id' => 10, 'id_user' => 1, 'aktifitas' => 'Login ke sistem',                             'created_at' => now()->subDay(),                 'user' => (object)['name' => 'Kasir 1', 'email' => 'kasir1@educourse.id']],
            (object)['id' => 11, 'id_user' => 1, 'aktifitas' => 'Bayar tagihan Fajar Nugroho - TRX-U1V2W3X4', 'created_at' => now()->subDay()->subHours(1),    'user' => (object)['name' => 'Kasir 1', 'email' => 'kasir1@educourse.id']],
            (object)['id' => 12, 'id_user' => 2, 'aktifitas' => 'Tambah peserta baru: Siti Rahmawati',         'created_at' => now()->subDays(2),               'user' => (object)['name' => 'Kasir 2', 'email' => 'kasir2@educourse.id']],
            (object)['id' => 13, 'id_user' => 3, 'aktifitas' => 'Edit data peserta: Andi Saputra',             'created_at' => now()->subDays(3),               'user' => (object)['name' => 'Admin',   'email' => 'admin@educourse.id']],
            (object)['id' => 14, 'id_user' => 2, 'aktifitas' => 'Logout dari sistem',                          'created_at' => now()->subDays(3)->subHours(2),  'user' => (object)['name' => 'Kasir 2', 'email' => 'kasir2@educourse.id']],
            (object)['id' => 15, 'id_user' => 3, 'aktifitas' => 'Hapus tagihan: Rizky Pratama Jan/2025',       'created_at' => now()->subDays(5),               'user' => (object)['name' => 'Admin',   'email' => 'admin@educourse.id']],
        ]);

        Route::get('/', function () use ($dummyLogs) {
            // Hanya tampilkan log milik user yang sedang login
            $logs = $dummyLogs->where('id_user', Auth::id());
            return view('kasir.log.index', compact('logs'));
        })->name('kasir.log.index');

    }); // ← tutup prefix('log')

}); // ← tutup prefix('kasir')