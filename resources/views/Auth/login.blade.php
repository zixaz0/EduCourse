<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>EduCore - Login</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
        }

        .login-container {
            height: 100vh;
            /* ganti dari min-height */
        }

        .image-section {
            background: linear-gradient(135deg, #7c5a3f 0%, #5a4030 100%);
        }

        .form-section {
            background: linear-gradient(135deg, #d4a574 0%, #c19a6b 100%);
        }
    </style>

</head>

<body>
    <div class="login-container flex h-screen overflow-hidden">
        <!-- Left Side - Image Section -->
        <div class="image-section w-full md:w-1/2 flex items-center justify-center p-8 md:p-12">
            <div class="max-w-md w-full">
                <img src="{{ asset('images/classroom-illustration.png') }}"
                    class="w-full max-h-[80vh] object-contain rounded-2xl shadow-2xl">
            </div>
        </div>

        <!-- Right Side - Form Section -->
        <div class="form-section w-full md:w-1/2 flex items-center justify-center p-8 md:p-12 overflow-y-auto">
            <div class="max-w-md w-full">
                <!-- Logo Section -->
                <div class="text-center mb-8">
                    <div class="inline-block bg-white/20 backdrop-blur-sm p-4 rounded-2xl mb-4 shadow-lg">
                        <img src="{{ asset('images/logo-light.png') }}" alt="EduCore Logo" class="w-20 h-20 mx-auto">
                    </div>
                    <h1 class="text-3xl font-bold text-white mb-2">EduCore</h1>
                    <p class="text-white/90 text-sm">study to achieve dreams</p>
                </div>

                <!-- Login Form -->
                <div class="bg-white/10 backdrop-blur-md rounded-3xl shadow-2xl p-8 border border-white/20">
                    @if (session('error'))
                        <div
                            class="bg-red-500/20 border border-red-500 text-white px-4 py-3 rounded-xl mb-6 flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    @if (session('success'))
                        <div
                            class="bg-green-500/20 border border-green-500 text-white px-4 py-3 rounded-xl mb-6 flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    <form action="" method="POST" class="space-y-6">
                        @csrf

                        <!-- Email Input -->
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-600"></i>
                            </div>
                            <input type="email" name="email" id="email" placeholder="Username/Email" required
                                value="{{ old('email') }}"
                                class="w-full pl-12 pr-4 py-3.5 bg-white border-2 border-transparent rounded-xl focus:outline-none focus:border-amber-700 focus:ring-2 focus:ring-amber-500/50 transition duration-300 text-gray-800 placeholder-gray-500">
                            @error('email')
                                <p class="mt-2 text-sm text-red-300 flex items-center">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Password Input -->
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-600"></i>
                            </div>
                            <input type="password" name="password" id="password" placeholder="Password" required
                                class="w-full pl-12 pr-12 py-3.5 bg-white border-2 border-transparent rounded-xl focus:outline-none focus:border-amber-700 focus:ring-2 focus:ring-amber-500/50 transition duration-300 text-gray-800 placeholder-gray-500">
                            <button type="button" onclick="togglePassword()"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-600 hover:text-amber-700 transition">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                            @error('password')
                                <p class="mt-2 text-sm text-red-300 flex items-center">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between text-sm">
                            <label class="flex items-center text-white cursor-pointer group">
                                <input type="checkbox" name="remember"
                                    class="w-4 h-4 text-amber-700 bg-white border-gray-300 rounded focus:ring-amber-500 focus:ring-2 cursor-pointer">
                                <span class="ml-2 group-hover:text-white/80 transition">Remember Me</span>
                            </label>
                            <a href="" class="text-white hover:text-white/80 transition underline">
                                Forgot Password?
                            </a>
                        </div>

                        <!-- Login Button -->
                        <button type="submit"
                            class="w-full bg-amber-900 hover:bg-amber-950 text-white font-semibold py-3.5 rounded-xl transition duration-300 transform hover:scale-[1.02] active:scale-[0.98] shadow-lg hover:shadow-xl flex items-center justify-center space-x-2">
                            <span>Login</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>

                        <!-- Register Link -->
                        <div class="text-center text-white text-sm">
                            Don't have an account?
                            <a href="" class="font-semibold underline hover:text-white/80 transition">
                                Register here
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <div class="text-center mt-6 text-white/70 text-xs">
                    <p>&copy; {{ date('Y') }} EduCore. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('[class*="bg-red-500"], [class*="bg-green-500"]');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);
    </script>
</body>

</html>
