<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Owner\OwnerDashboardController;
use App\Http\Controllers\Kasir\KasirDashboardController;

// Auth
Route::get('/', fn() => redirect()->route('login'));
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Owner
Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/owner/dashboard', [OwnerDashboardController::class, 'index'])->name('owner.dashboard');
});

Route::prefix('kasir')->middleware(['auth', RoleMiddleware::class . ':kasir'])->group(function () {

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


Route::prefix('admin')->middleware(['auth', RoleMiddleware::class . ':admin'])->group(function () {

    Route::get('/dashboard', function () {

        // ---- DATA DUMMY ----
        $recentTransaksi = collect([
            (object)['peserta' => (object)['nama' => 'Andi Saputra'],  'uang_bayar' => 400000, 'created_at' => now()->subMinutes(10),
                'user' => (object)['name' => 'Kasir 1'],
                'tagihan' => (object)['peserta' => (object)['kelas' => collect([(object)['nama_kelas' => 'Matematika']])]]],
            (object)['peserta' => (object)['nama' => 'Erika Putri'],   'uang_bayar' => 350000, 'created_at' => now()->subHours(1),
                'user' => (object)['name' => 'Kasir 2'],
                'tagihan' => (object)['peserta' => (object)['kelas' => collect([(object)['nama_kelas' => 'Bahasa Inggris']])]]],
            (object)['peserta' => (object)['nama' => 'Gita Rahayu'],   'uang_bayar' => 450000, 'created_at' => now()->subHours(2),
                'user' => (object)['name' => 'Kasir 2'],
                'tagihan' => (object)['peserta' => (object)['kelas' => collect([(object)['nama_kelas' => 'Python Dasar']])]]],
            (object)['peserta' => (object)['nama' => 'Fajar Nugroho'], 'uang_bayar' => 500000, 'created_at' => now()->subHours(3),
                'user' => (object)['name' => 'Kasir 1'],
                'tagihan' => (object)['peserta' => (object)['kelas' => collect([(object)['nama_kelas' => 'Desain Grafis']])]]],
            (object)['peserta' => (object)['nama' => 'Budi Santoso'],  'uang_bayar' => 400000, 'created_at' => now()->subHours(5),
                'user' => (object)['name' => 'Kasir 1'],
                'tagihan' => (object)['peserta' => (object)['kelas' => collect([(object)['nama_kelas' => 'Matematika']])]]],
        ]);

        $kelasTerpopuler = collect([
            (object)['nama_kelas' => 'Matematika',     'jumlah_peserta' => 12, 'persentase' => 80],
            (object)['nama_kelas' => 'Bahasa Inggris', 'jumlah_peserta' => 10, 'persentase' => 67],
            (object)['nama_kelas' => 'Python Dasar',   'jumlah_peserta' => 8,  'persentase' => 53],
            (object)['nama_kelas' => 'Web Design',     'jumlah_peserta' => 6,  'persentase' => 40],
            (object)['nama_kelas' => 'Desain Grafis',  'jumlah_peserta' => 4,  'persentase' => 27],
        ]);

        $recentLog = collect([
            (object)['aktifitas' => 'Bayar tagihan Andi Saputra - TRX-A1B2', 'created_at' => now()->subMinutes(10), 'user' => (object)['name' => 'Kasir 1']],
            (object)['aktifitas' => 'Login ke sistem',                        'created_at' => now()->subMinutes(15), 'user' => (object)['name' => 'Kasir 2']],
            (object)['aktifitas' => 'Tambah peserta baru: Hendra K.',         'created_at' => now()->subMinutes(30), 'user' => (object)['name' => 'Kasir 1']],
            (object)['aktifitas' => 'Edit data peserta: Citra Dewi',          'created_at' => now()->subHours(1),   'user' => (object)['name' => 'Kasir 2']],
            (object)['aktifitas' => 'Hapus tagihan: Rizky Jan/2025',          'created_at' => now()->subHours(2),   'user' => (object)['name' => 'Admin']],
            (object)['aktifitas' => 'Login ke sistem',                        'created_at' => now()->subHours(3),   'user' => (object)['name' => 'Admin']],
        ]);

        $recentTagihanBelumLunas = collect([
            (object)['peserta' => (object)['nama' => 'Andi Saputra'],   'bulan_tahun' => 'Februari/2026', 'total_tagihan' => 400000],
            (object)['peserta' => (object)['nama' => 'Siti Maimunah'],  'bulan_tahun' => 'Februari/2026', 'total_tagihan' => 350000],
            (object)['peserta' => (object)['nama' => 'Rizky Pratama'],  'bulan_tahun' => 'Februari/2026', 'total_tagihan' => 400000],
            (object)['peserta' => (object)['nama' => 'Fajar Nugroho'],  'bulan_tahun' => 'Februari/2026', 'total_tagihan' => 350000],
            (object)['peserta' => (object)['nama' => 'Gita Rahayu'],    'bulan_tahun' => 'Februari/2026', 'total_tagihan' => 450000],
        ]);
        // ---- END DATA DUMMY ----

        return view('admin.dashboard', [
            'totalUser'               => 5,
            'userAktif'               => 2,
            'totalPeserta'            => 24,
            'pesertaAktif'            => 19,
            'totalKelas'              => 5,
            'tagihanBelumLunas'       => 12,
            'pemasukanBulanIni'       => 4750000,
            'totalTransaksi'          => 47,
            'recentTransaksi'         => $recentTransaksi,
            'kelasTerpopuler'         => $kelasTerpopuler,
            'recentLog'               => $recentLog,
            'recentTagihanBelumLunas' => $recentTagihanBelumLunas,
        ]);

    })->name('admin.dashboard');


    // ========================
    // USER MANAGEMENT ROUTES
    // ========================
    Route::prefix('user')->group(function () {

        // ---- DATA DUMMY ----
        $dummyUsers = collect([
            (object)['id' => 1, 'username' => 'admin01',  'email' => 'admin01@educourse.id',  'role' => 'admin',  'status' => 'aktif',    'created_at' => now()->subDays(30)],
            (object)['id' => 2, 'username' => 'kasir01',  'email' => 'kasir01@educourse.id',  'role' => 'kasir',  'status' => 'aktif',    'created_at' => now()->subDays(25)],
            (object)['id' => 3, 'username' => 'kasir02',  'email' => 'kasir02@educourse.id',  'role' => 'kasir',  'status' => 'aktif',    'created_at' => now()->subDays(20)],
            (object)['id' => 4, 'username' => 'kasir03',  'email' => 'kasir03@educourse.id',  'role' => 'kasir',  'status' => 'nonaktif', 'created_at' => now()->subDays(15)],
            (object)['id' => 5, 'username' => 'kasir04',  'email' => 'kasir04@educourse.id',  'role' => 'kasir',  'status' => 'aktif',    'created_at' => now()->subDays(5)],
        ]);
        // ---- END DATA DUMMY ----

        // INDEX
        Route::get('/', function () use ($dummyUsers) {
            return view('admin.users.index', ['users' => $dummyUsers]);
        })->name('admin.user.index');

        // ADD FORM
        Route::get('/add', function () {
            return view('admin.users.add');
        })->name('admin.user.add');

        // STORE
        Route::post('/', function (\Illuminate\Http\Request $request) {
            $request->validate([
                'username' => 'required|string|max:255|unique:users,username',
                'email'    => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'role'     => 'required|in:admin,kasir',
                'status'   => 'required|in:aktif,nonaktif',
            ]);

            \App\Models\User::create([
                'username' => $request->username,
                'email'    => $request->email,
                'password' => bcrypt($request->password),
                'role'     => $request->role,
                'status'   => $request->status,
            ]);

            return redirect()->route('admin.user.index')
                ->with('success', "User {$request->username} berhasil ditambahkan.");
        })->name('admin.user.store');

        // EDIT FORM
        Route::get('/{id}/edit', function ($id) use ($dummyUsers) {
            $user = $dummyUsers->firstWhere('id', (int) $id);
            if (!$user) abort(404);
            return view('admin.users.edit', compact('user'));
        })->name('admin.user.edit');

        // UPDATE
        Route::put('/{id}', function (\Illuminate\Http\Request $request, $id) {
            $request->validate([
                'username' => "required|string|max:255|unique:users,username,{$id}",
                'email'    => "required|email|unique:users,email,{$id}",
                'password' => 'nullable|string|min:8|confirmed',
                'role'     => 'required|in:admin,kasir',
                'status'   => 'required|in:aktif,nonaktif',
            ]);

            $user = \App\Models\User::findOrFail($id);
            $user->username = $request->username;
            $user->email    = $request->email;
            $user->role     = $request->role;
            $user->status   = $request->status;
            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
            }
            $user->save();

            return redirect()->route('admin.user.index')
                ->with('success', "User {$user->username} berhasil diperbarui.");
        })->name('admin.user.update');

        // TOGGLE STATUS
        Route::get('/{id}/toggle', function ($id) {
            $user = \App\Models\User::findOrFail($id);
            $user->status = $user->status === 'aktif' ? 'nonaktif' : 'aktif';
            $user->save();

            $label = $user->status === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';
            return redirect()->route('admin.user.index')
                ->with('success', "User {$user->username} berhasil {$label}.");
        })->name('admin.user.toggle');

    }); // ← tutup prefix('user')


    // ========================
    // PESERTA ROUTES (ADMIN)
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
            (object)['id' => 1, 'nama' => 'Andi Saputra',    'email' => 'andi@mail.com',    'no_hp' => '081234567890', 'status' => 'aktif',    'nama_orangtua' => 'Budi Saputra',   'no_orangtua' => '081234567800',
                'kelas' => collect([(object)['nama_kelas' => 'Matematika'], (object)['nama_kelas' => 'Bahasa Inggris']])],
            (object)['id' => 2, 'nama' => 'Siti Maimunah',   'email' => 'siti@mail.com',    'no_hp' => '081234567891', 'status' => 'aktif',    'nama_orangtua' => 'Ahmad Maimun',   'no_orangtua' => '081234567801',
                'kelas' => collect([(object)['nama_kelas' => 'Python Dasar']])],
            (object)['id' => 3, 'nama' => 'Rizky Pratama',   'email' => 'rizky@mail.com',   'no_hp' => '081234567892', 'status' => 'aktif',    'nama_orangtua' => 'Hendra Pratama', 'no_orangtua' => '081234567802',
                'kelas' => collect([(object)['nama_kelas' => 'Web Design'], (object)['nama_kelas' => 'Desain Grafis']])],
            (object)['id' => 4, 'nama' => 'Fajar Nugroho',   'email' => 'fajar@mail.com',   'no_hp' => '081234567893', 'status' => 'nonaktif', 'nama_orangtua' => 'Dedi Nugroho',   'no_orangtua' => '081234567803',
                'kelas' => collect([(object)['nama_kelas' => 'Bahasa Inggris']])],
            (object)['id' => 5, 'nama' => 'Erika Putri',     'email' => 'erika@mail.com',   'no_hp' => '081234567894', 'status' => 'aktif',    'nama_orangtua' => 'Wati Putri',     'no_orangtua' => '081234567804',
                'kelas' => collect([(object)['nama_kelas' => 'Matematika']])],
            (object)['id' => 6, 'nama' => 'Gita Rahayu',     'email' => 'gita@mail.com',    'no_hp' => '081234567895', 'status' => 'aktif',    'nama_orangtua' => 'Tono Rahayu',    'no_orangtua' => '081234567805',
                'kelas' => collect([(object)['nama_kelas' => 'Python Dasar'], (object)['nama_kelas' => 'Desain Grafis']])],
            (object)['id' => 7, 'nama' => 'Hendra Kurniawan','email' => 'hendra@mail.com',  'no_hp' => '081234567896', 'status' => 'nonaktif', 'nama_orangtua' => 'Iwan Kurniawan', 'no_orangtua' => '081234567806',
                'kelas' => collect([(object)['nama_kelas' => 'Web Design']])],
        ]);
        // ---- END DATA DUMMY ----

        // INDEX
        Route::get('/', function () use ($dummyPeserta, $dummyKelas) {
            return view('admin.peserta.index', ['peserta' => $dummyPeserta, 'kelasList' => $dummyKelas]);
        })->name('admin.peserta.index');

        // DELETE
        Route::delete('/{id}', function ($id) {
            $peserta = \App\Models\Peserta::findOrFail($id);
            $nama = $peserta->nama;
            $peserta->kelas()->detach();
            $peserta->delete();
            return redirect()->route('admin.peserta.index')->with('success', "Peserta {$nama} berhasil dihapus.");
        })->name('admin.peserta.delete');

    }); // ← tutup prefix('peserta')


    // ========================
    // KELAS ROUTES (ADMIN)
    // ========================
    Route::prefix('kelas')->group(function () {

        // ---- DATA DUMMY ----
        $dummyKelas = collect([
            (object)['id' => 1, 'nama_kelas' => 'Python Dasar',    'harga_kelas' => 400000, 'hari_kelas' => 'Senin, Rabu',    'jumlah_peserta' => 12, 'created_at' => now()->subDays(60)],
            (object)['id' => 2, 'nama_kelas' => 'Web Design',      'harga_kelas' => 450000, 'hari_kelas' => 'Selasa, Kamis',  'jumlah_peserta' => 8,  'created_at' => now()->subDays(50)],
            (object)['id' => 3, 'nama_kelas' => 'Matematika',      'harga_kelas' => 350000, 'hari_kelas' => 'Senin, Jumat',   'jumlah_peserta' => 15, 'created_at' => now()->subDays(45)],
            (object)['id' => 4, 'nama_kelas' => 'Bahasa Inggris',  'harga_kelas' => 375000, 'hari_kelas' => 'Rabu, Sabtu',    'jumlah_peserta' => 10, 'created_at' => now()->subDays(30)],
            (object)['id' => 5, 'nama_kelas' => 'Desain Grafis',   'harga_kelas' => 500000, 'hari_kelas' => 'Kamis, Sabtu',   'jumlah_peserta' => 6,  'created_at' => now()->subDays(15)],
        ]);
        // ---- END DATA DUMMY ----

        // INDEX
        Route::get('/', function () use ($dummyKelas) {
            return view('admin.kelas.index', ['kelas' => $dummyKelas]);
        })->name('admin.kelas.index');

        // ADD FORM
        Route::get('/add', function () {
            return view('admin.kelas.add');
        })->name('admin.kelas.add');

        // STORE
        Route::post('/', function (\Illuminate\Http\Request $request) {
            $request->validate([
                'nama_kelas'  => 'required|string|max:255',
                'harga_kelas' => 'required|numeric|min:0',
                'hari_kelas'  => 'required|array|min:1',
            ]);
            \App\Models\Kelas::create([
                'nama_kelas'  => $request->nama_kelas,
                'harga_kelas' => $request->harga_kelas,
                'hari_kelas'  => implode(', ', $request->hari_kelas),
            ]);
            return redirect()->route('admin.kelas.index')
                ->with('success', "Kelas {$request->nama_kelas} berhasil ditambahkan.");
        })->name('admin.kelas.store');

        // EDIT FORM
        Route::get('/{id}/edit', function ($id) use ($dummyKelas) {
            $kelas = $dummyKelas->firstWhere('id', (int) $id);
            if (!$kelas) abort(404);
            return view('admin.kelas.edit', compact('kelas'));
        })->name('admin.kelas.edit');

        // UPDATE
        Route::put('/{id}', function (\Illuminate\Http\Request $request, $id) {
            $request->validate([
                'nama_kelas'  => 'required|string|max:255',
                'harga_kelas' => 'required|numeric|min:0',
                'hari_kelas'  => 'required|array|min:1',
            ]);
            $kelas = \App\Models\Kelas::findOrFail($id);
            $kelas->update([
                'nama_kelas'  => $request->nama_kelas,
                'harga_kelas' => $request->harga_kelas,
                'hari_kelas'  => implode(', ', $request->hari_kelas),
            ]);
            return redirect()->route('admin.kelas.index')
                ->with('success', "Kelas {$kelas->nama_kelas} berhasil diperbarui.");
        })->name('admin.kelas.update');

        // DELETE
        Route::delete('/{id}', function ($id) {
            $kelas = \App\Models\Kelas::findOrFail($id);
            $nama  = $kelas->nama_kelas;
            // Detach peserta dulu sebelum hapus
            $kelas->peserta()->detach();
            $kelas->delete();
            return redirect()->route('admin.kelas.index')
                ->with('success', "Kelas {$nama} berhasil dihapus.");
        })->name('admin.kelas.delete');

    }); // ← tutup prefix('kelas')


    // ========================
    // LOG AKTIVITAS (ADMIN)
    // ========================
    Route::prefix('log')->group(function () {

        // ---- DATA DUMMY ----
        $dummyLogs = collect([
            (object)['id' => 1,  'id_user' => 1, 'aktifitas' => 'Login ke sistem',                          'created_at' => now()->subMinutes(5),            'user' => (object)['name' => 'admin01', 'email' => 'admin01@educourse.id']],
            (object)['id' => 2,  'id_user' => 1, 'aktifitas' => 'Tambah kelas baru: Python Lanjutan',       'created_at' => now()->subMinutes(20),           'user' => (object)['name' => 'admin01', 'email' => 'admin01@educourse.id']],
            (object)['id' => 3,  'id_user' => 1, 'aktifitas' => 'Edit kelas: Web Design',                   'created_at' => now()->subHours(1),              'user' => (object)['name' => 'admin01', 'email' => 'admin01@educourse.id']],
            (object)['id' => 4,  'id_user' => 2, 'aktifitas' => 'Login ke sistem',                          'created_at' => now()->subHours(2),              'user' => (object)['name' => 'kasir01', 'email' => 'kasir01@educourse.id']],
            (object)['id' => 5,  'id_user' => 1, 'aktifitas' => 'Hapus peserta: Budi Lama',                 'created_at' => now()->subHours(3),              'user' => (object)['name' => 'admin01', 'email' => 'admin01@educourse.id']],
            (object)['id' => 6,  'id_user' => 1, 'aktifitas' => 'Tambah user baru: kasir05',                'created_at' => now()->subHours(4),              'user' => (object)['name' => 'admin01', 'email' => 'admin01@educourse.id']],
            (object)['id' => 7,  'id_user' => 2, 'aktifitas' => 'Bayar tagihan Andi Saputra',               'created_at' => now()->subHours(5),              'user' => (object)['name' => 'kasir01', 'email' => 'kasir01@educourse.id']],
            (object)['id' => 8,  'id_user' => 1, 'aktifitas' => 'Logout dari sistem',                       'created_at' => now()->subDay(),                 'user' => (object)['name' => 'admin01', 'email' => 'admin01@educourse.id']],
            (object)['id' => 9,  'id_user' => 1, 'aktifitas' => 'Login ke sistem',                          'created_at' => now()->subDay()->subHours(1),    'user' => (object)['name' => 'admin01', 'email' => 'admin01@educourse.id']],
            (object)['id' => 10, 'id_user' => 1, 'aktifitas' => 'Edit user: kasir02',                       'created_at' => now()->subDays(2),               'user' => (object)['name' => 'admin01', 'email' => 'admin01@educourse.id']],
            (object)['id' => 11, 'id_user' => 2, 'aktifitas' => 'Edit data peserta: Citra Dewi',            'created_at' => now()->subDays(3),               'user' => (object)['name' => 'kasir01', 'email' => 'kasir01@educourse.id']],
            (object)['id' => 12, 'id_user' => 1, 'aktifitas' => 'Hapus kelas: Matematika Lanjutan',         'created_at' => now()->subDays(4),               'user' => (object)['name' => 'admin01', 'email' => 'admin01@educourse.id']],
        ]);
        // ---- END DATA DUMMY ----

        Route::get('/', function () use ($dummyLogs) {
            // Hanya tampilkan log milik user yang sedang login
            $logs = $dummyLogs->where('id_user', Auth::id());
            return view('admin.log.index', compact('logs'));
        })->name('admin.log.index');

    }); // ← tutup prefix('log')

}); // ← tutup prefix('admin')