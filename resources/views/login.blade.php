<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EduCourse</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20% { transform: translateX(-8px); }
            40% { transform: translateX(8px); }
            60% { transform: translateX(-5px); }
            80% { transform: translateX(5px); }
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .error-alert {
            animation: slideDown 0.3s ease forwards;
        }

        .input-error {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.12) !important;
        }

        .shake {
            animation: shake 0.4s ease;
        }
    </style>
</head>
<body class="min-h-screen flex">

    <!-- LEFT: Gambar fullscreen -->
    <div class="hidden md:block flex-1 relative overflow-hidden">
        <img
            src="images/panel_kiri.jpg"
            alt="Background"
            class="absolute inset-0 w-full h-full object-cover object-center"
        >
    </div>

    <!-- RIGHT: Area abu-abu, card floating di tengah -->
    <div class="w-full md:w-[520px] md:min-w-[480px] flex items-center justify-center px-10 py-12" style="background-color: #e2e2e2;">

        <!-- Card -->
        <div class="w-full rounded-3xl flex flex-col items-center px-10 py-12" style="background-color: #ebebeb;">

            <!-- Logo PNG -->
            <div class="mb-4">
                <img src="images/logo.png" alt="Logo EduCourse" style="width: 90px; height: auto; object-fit: contain;">
            </div>

            <!-- Brand Name -->
            <h1 class="text-[28px] font-extrabold mb-2 tracking-tight" style="color: #1a2e6e;">
                EduCourse
            </h1>

            <!-- Subtitle -->
            <p class="text-sm mb-7 text-center" style="color: #999;">
                Masukkan email dan password untuk melanjutkan
            </p>

            <form class="w-full" method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                <!-- Email -->
                <div class="mb-5">
                    <label class="block text-sm mb-1.5" style="color: #999;">Email</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none" style="color: #bbb;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                        </span>
                        <input
                            type="email"
                            name="email"
                            id="emailInput"
                            value="{{ old('email') }}"
                            placeholder="Masukan Email"
                            class="w-full border rounded-full pl-11 pr-5 py-3 text-sm focus:outline-none transition {{ $errors->has('email') || $errors->any() ? 'input-error' : '' }}"
                            style="background:#fff; border-color:{{ $errors->any() ? '#ef4444' : '#e0e0e0' }}; color:#333;"
                            onfocus="this.style.borderColor='#1a2e6e'; this.style.boxShadow='0 0 0 3px rgba(26,46,110,0.1)'; this.classList.remove('input-error')"
                            onblur="this.style.boxShadow='none'"
                        >
                    </div>
                    @error('email')
                        <p class="text-xs mt-1.5 ml-4" style="color: #ef4444;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-8">
                    <label class="block text-sm mb-1.5" style="color: #999;">Password</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none" style="color: #bbb;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </span>
                        <input
                            type="password"
                            name="password"
                            id="passwordInput"
                            placeholder="Masukan Password"
                            class="w-full border rounded-full pl-11 pr-10 py-3 text-sm focus:outline-none transition {{ $errors->any() ? 'input-error' : '' }}"
                            style="background:#fff; border-color:{{ $errors->any() ? '#ef4444' : '#e0e0e0' }}; color:#333;"
                            onfocus="this.style.borderColor='#1a2e6e'; this.style.boxShadow='0 0 0 3px rgba(26,46,110,0.1)'; this.classList.remove('input-error')"
                            onblur="this.style.boxShadow='none'"
                        >
                        {{-- Toggle show/hide password --}}
                        <button
                            type="button"
                            onclick="togglePassword()"
                            class="absolute right-4 top-1/2 -translate-y-1/2 transition-opacity hover:opacity-60"
                            style="color: #bbb;"
                            tabindex="-1"
                        >
                            <svg id="eyeIcon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg id="eyeOffIcon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="hidden">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                                <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs mt-1.5 ml-4" style="color: #ef4444;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tombol Login -->
                <button
                    type="submit"
                    class="w-full text-white font-semibold text-sm rounded-full py-3 transition-all active:scale-[.98]"
                    style="background-color: #1a2e6e;"
                    onmouseover="this.style.backgroundColor='#152459'"
                    onmouseout="this.style.backgroundColor='#1a2e6e'"
                >
                    Login
                </button>

            </form>
        </div>
    </div>

    <script>
        // Shake animation saat ada error
        document.addEventListener('DOMContentLoaded', function () {
            const hasError = {{ $errors->any() ? 'true' : 'false' }};
            if (hasError) {
                const card = document.querySelector('.rounded-3xl');
                card.classList.add('shake');
                setTimeout(() => card.classList.remove('shake'), 500);
            }
        });

        // Toggle show/hide password
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const eyeIcon = document.getElementById('eyeIcon');
            const eyeOffIcon = document.getElementById('eyeOffIcon');

            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        }
    </script>

</body>
</html>