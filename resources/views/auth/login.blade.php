<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config("app.name") }} - Login</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @vite('resources/css/app.css') @vite('resources/js/app.js')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('styles')
        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">
</head>

<body class="flex h-screen">
    <div class="hidden w-1/2 p-8 bg-gray-200 bg-bottom bg-cover lg:block"
        style="background-image: url(/images/login-image.png);">
        <h1 class="text-5xl leading-normal text-white">Selamat Datang <br> <span class="italic">di</span> Toko Emas <br>
            Majusari</h1>
    </div>
    <div class="flex flex-col justify-center w-full p-8 lg:w-1/2">
        <div class="max-w-md mx-auto">
            <h1 class="max-w-sm mb-3 text-3xl text-center">
                Login ke halaman dashboard
            </h1>
            <p class="mb-6 text-xs text-center">
                Masukkan email dan password yang valid
            </p>
            {{-- alert error --}}
            @error('email')
            <div class="p-4 mt-2 text-sm text-red-800 bg-red-100 border border-red-200 rounded-lg" role="alert"
                tabindex="-1" aria-labelledby="hs-soft-color-danger-label">
                {{ $message }}
            </div>
            @enderror
            @error('password')
            <div class="p-4 mt-2 text-sm text-red-800 bg-red-100 border border-red-200 rounded-lg" role="alert"
                tabindex="-1" aria-labelledby="hs-soft-color-danger-label">
                {{ $message }}
            </div>
            @enderror

            <form method="POST" action="{{ url('login') }}">
                @csrf
                <div class="my-4">
                    <label class="block mb-2 text-sm font-bold text-gray-700" for="email">Email</label>
                    <input name="email"
                        class="w-full px-3 py-3 text-sm leading-tight text-gray-700 border border-gray-400 rounded-lg outline-none appearance-none focus:outline-none focus:shadow-outline"
                        id="email" type="email" placeholder="Masukkan email" />
                </div>
                <div class="mb-6" x-data="{ show: false }">
                    <label class="block mb-2 text-sm font-bold text-gray-700" for="password">Password</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" name="password"
                            class="w-full px-3 py-3 text-sm leading-tight text-gray-700 border border-gray-400 rounded-lg outline-none appearance-none focus:outline-none focus:shadow-outline"
                            id="password" placeholder="Masukkan Password" />
                        <button @click="show = !show" type="button"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-700">
                            <i x-show="show" class="ph ph-eye"></i>
                            <i x-show="!show" class="ph ph-eye-slash"></i>
                        </button>
                    </div>
                </div>
                <div class="flex items-center justify-between mb-6">
                    <label class="inline-flex items-center text-sm">
                        <input type="checkbox" name="remember" class="form-checkbox" />
                        <span class="ml-2">Remember Me</span>
                    </label>
                    <a href="/forgot-password" class="text-sm text-gray-500 hover:text-gray-700 hover:underline">Lupa
                        password?
                        <span class="font-bold">Reset Sekarang</span></a>
                </div>
                <div class="mb-6">
                    <button type="submit"
                        class="w-full px-4 py-2 font-bold text-white bg-[#7F56D9] rounded-lg disabled:bg-gray-300 focus:outline-none focus:shadow-outline hover:bg-purple-700"
                        type="button">
                        Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
