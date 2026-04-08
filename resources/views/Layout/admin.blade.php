<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - EduCourse</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { poppins: ['Poppins', 'sans-serif'] },
                    colors: {
                        primary: {
                            50: '#e8eef7', 100: '#c5d5ec', 200: '#9eb9de',
                            300: '#759dd0', 400: '#5488c7', 500: '#3373be',
                            600: '#2a65ad', 700: '#1e5399', 800: '#154286', 900: '#062465',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .sidebar-link.active { background-color: rgba(255,255,255,0.25); border-radius: 8px; }
        .sidebar-link:hover { background-color: rgba(255,255,255,0.15); border-radius: 8px; transition: background-color 0.2s ease; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #3373be; border-radius: 10px; }

    /* ===== PAGE LOADING SCREEN ===== */
    #pageLoader {
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: #1e5399;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 20px;
        transition: opacity 0.4s ease, visibility 0.4s ease;
    }
    #pageLoader.hide {
        opacity: 0;
        visibility: hidden;
    }
    .loader-logo {
        width: 56px;
        height: 56px;
        background: white;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        animation: logoPulse 1.2s ease-in-out infinite;
    }
    .loader-logo img { width: 40px; height: 40px; object-fit: contain; }
    .loader-bar-wrap {
        width: 180px;
        height: 4px;
        background: rgba(255,255,255,0.2);
        border-radius: 99px;
        overflow: hidden;
    }
    .loader-bar {
        height: 100%;
        width: 0%;
        background: white;
        border-radius: 99px;
        animation: loadBar 0.6s ease forwards;
    }
    .loader-text {
        color: rgba(255,255,255,0.7);
        font-size: 12px;
        font-family: 'Poppins', sans-serif;
        letter-spacing: 0.05em;
    }
    @keyframes logoPulse {
        0%, 100% { transform: scale(1); box-shadow: 0 8px 32px rgba(0,0,0,0.2); }
        50% { transform: scale(1.07); box-shadow: 0 12px 40px rgba(0,0,0,0.3); }
    }
    @keyframes loadBar {
        to { width: 100%; }
    }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex overflow-x-hidden">

    <!-- PAGE LOADER -->
    <div id="pageLoader">
        <div class="loader-logo">
            <img src="{{ asset('images/logo.webp') }}" alt="Logo">
        </div>
        <div class="loader-bar-wrap">
            <div class="loader-bar"></div>
        </div>
        <p class="loader-text">Memuat halaman...</p>
    </div>


    <aside class="w-56 min-h-screen bg-primary-700 flex flex-col fixed top-0 left-0 z-30 shadow-xl">
        <div class="flex items-center gap-3 px-5 py-5 border-b border-primary-600">
            <div class="w-9 h-9 bg-white rounded-lg flex items-center justify-center shadow overflow-hidden">
                <img src="{{ asset('images/logo.webp') }}" alt="Logo" class="w-7 h-7 object-contain">
            </div>
            <span class="text-white font-bold text-lg tracking-wide">EduCourse</span>
        </div>

        <div class="px-4 pt-4 pb-1">
            <span class="text-xs font-bold text-blue-300 uppercase tracking-widest">Admin Panel</span>
        </div>

        <nav class="flex-1 px-3 py-2 space-y-0.5 overflow-y-auto">
            <a href="{{ url('/admin/dashboard') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-blue-100 text-sm font-medium">
                <i class="fa-solid fa-gauge-high w-5 text-center"></i><span>Dashboard</span>
            </a>

            <p class="text-xs text-blue-400 font-semibold px-3 pt-3 pb-1 uppercase tracking-wider">Master Data</p>

            <a href="{{ url('/admin/user') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-blue-100 text-sm font-medium">
                <i class="fa-solid fa-user-gear w-5 text-center"></i><span>Manajemen User</span>
            </a>
            <a href="{{ url('/admin/peserta') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-blue-100 text-sm font-medium">
                <i class="fa-solid fa-users w-5 text-center"></i><span>Data Peserta</span>
            </a>
            <a href="{{ url('/admin/guru') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-blue-100 text-sm font-medium">
                <i class="fa-solid fa-graduation-cap w-5 text-center"></i><span>Data Guru</span>
            </a>
            <a href="{{ url('/admin/kelas') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-blue-100 text-sm font-medium">
                <i class="fa-solid fa-chalkboard-user w-5 text-center"></i><span>Data Kelas</span>
            </a>

            <p class="text-xs text-blue-400 font-semibold px-3 pt-3 pb-1 uppercase tracking-wider">Keuangan</p>

            <a href="{{ url('/admin/riwayat') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-blue-100 text-sm font-medium">
                <i class="fa-solid fa-clock-rotate-left w-5 text-center"></i><span>Riwayat Transaksi</span>
            </a>

            <p class="text-xs text-blue-400 font-semibold px-3 pt-3 pb-1 uppercase tracking-wider">Sistem</p>

            <a href="{{ url('/admin/log') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-blue-100 text-sm font-medium">
                <i class="fa-solid fa-clipboard-list w-5 text-center"></i><span>Log Aktivitas</span>
            </a>
        </nav>

        <div class="px-3 py-4 border-t border-primary-600">
            <form id="logoutForm" method="POST" action="{{ route('logout') }}">@csrf</form>
            <button onclick="confirmLogout(event)" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 text-blue-100 text-sm font-medium">
                <i class="fa-solid fa-right-from-bracket w-5 text-center"></i><span>Logout</span>
            </button>
        </div>
    </aside>

    <div class="ml-56 flex-1 flex flex-col min-h-screen min-w-0 w-0">
        <header class="bg-primary-600 text-white px-6 py-3 flex items-center justify-between shadow-md sticky top-0 z-20">
            <div class="flex items-center gap-4 text-sm font-medium">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-clock text-blue-200"></i>
                    <span id="jam">--:--:--</span>
                </div>
                <div class="w-px h-4 bg-blue-400"></div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-calendar-days text-blue-200"></i>
                    <span id="tanggal">--/--/----</span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-xs text-blue-200">Selamat datang,</p>
                    <p class="text-sm font-semibold">{{ Auth::user()->name ?? Auth::user()->username ?? 'Admin' }}</p>
                </div>
                <div class="w-9 h-9 rounded-full border-2 border-blue-300 flex items-center justify-center bg-primary-800">
                    <span class="text-white text-sm font-bold">
                        {{ strtoupper(substr(Auth::user()->name ?? Auth::user()->username ?? 'A', 0, 1)) }}
                    </span>
                </div>
            </div>
        </header>

        <main class="flex-1 p-6 bg-gray-50 min-w-0">
            @yield('content')
        </main>
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            document.getElementById('jam').textContent = now.toLocaleTimeString('id-ID', { hour12: false });
            document.getElementById('tanggal').textContent = now.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' });
        }
        updateClock(); setInterval(updateClock, 1000);

        function confirmLogout(e) {
            e.preventDefault();
            Swal.fire({ title: 'Logout?', text: 'Apakah Anda yakin ingin keluar?', icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#1e5399', cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Logout', cancelButtonText: 'Batal',
            }).then(result => {
                if (result.isConfirmed) {
                    const loader = document.getElementById('pageLoader');
                    loader.querySelector('.loader-bar').style.animation = 'none';
                    loader.querySelector('.loader-bar').style.width = '0%';
                    loader.classList.remove('hide');
                    void loader.querySelector('.loader-bar').offsetWidth;
                    loader.querySelector('.loader-bar').style.animation = 'loadBar 0.5s ease forwards';
                    setTimeout(() => document.getElementById('logoutForm').submit(), 200);
                }
            });
        }

        const links = document.querySelectorAll('.sidebar-link');
        const currentPath = window.location.pathname;
        links.forEach(link => {
            const href = link.getAttribute('href');
            if (!href || href === '#') return;
            let linkPath = href;
            try { linkPath = new URL(href).pathname; } catch(e) {}
            if (currentPath === linkPath || currentPath.startsWith(linkPath + '/')) {
                link.classList.add('active', 'text-white');
                link.classList.remove('text-blue-100');
            } else {
                link.classList.remove('active', 'text-white');
                link.classList.add('text-blue-100');
            }
        });

        // ===== PAGE LOADER =====
        window.addEventListener('load', () => {
            const loader = document.getElementById('pageLoader');
            setTimeout(() => loader.classList.add('hide'), 300);
        });

        document.querySelectorAll('a[href]').forEach(link => {
            const href = link.getAttribute('href');
            if (!href || href.startsWith('#') || href.startsWith('javascript') || link.getAttribute('target') === '_blank') return;
            link.addEventListener('click', function(e) {
                const loader = document.getElementById('pageLoader');
                loader.querySelector('.loader-bar').style.animation = 'none';
                loader.querySelector('.loader-bar').style.width = '0%';
                loader.classList.remove('hide');
                void loader.querySelector('.loader-bar').offsetWidth; // reflow
                loader.querySelector('.loader-bar').style.animation = 'loadBar 0.5s ease forwards';
            });
        });

        // Form submit juga tampilkan loader
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                const loader = document.getElementById('pageLoader');
                loader.querySelector('.loader-bar').style.animation = 'none';
                loader.querySelector('.loader-bar').style.width = '0%';
                loader.classList.remove('hide');
                void loader.querySelector('.loader-bar').offsetWidth;
                loader.querySelector('.loader-bar').style.animation = 'loadBar 0.5s ease forwards';
            });
        });
    </script>
</body>
</html>