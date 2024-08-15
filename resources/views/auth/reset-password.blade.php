<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config("app.name") }} - Reset Password</title>
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
                Lupa Password
            </h1>
            <p class="mb-6 text-xs text-center">
                Masukkan email yang valid untuk melakukan reset password
            </p>
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-bold text-gray-700" for="email">Email</label>
                    <input
                        class="w-full px-3 py-3 text-sm leading-tight text-gray-700 border border-gray-400 rounded-lg outline-none appearance-none focus:outline-none focus:shadow-outline @error('email') is-invalid @enderror"
                        id="email" name="email" type="email" placeholder="Masukkan email"
                        value="{{ $request->email ?? old('email') }}" required autocomplete="email" autofocus readonly required/>
                    @error('email')
                    <div class="mt-2 text-sm text-red-400">
                        <strong>{{ $message }}</strong>
                    </div>
                    @enderror
                </div>
                <div class="mb-6" x-data="{ show: false }">
                    <label class="block mb-2 text-sm font-bold text-gray-700" for="password">Password Baru</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'"
                            class="w-full px-3 py-3 text-sm leading-tight text-gray-700 border border-gray-400 rounded-lg outline-none appearance-none focus:outline-none focus:shadow-outline"
                            id="password" name="password" placeholder="Masukkan Password" required/>
                        <button @click="show = !show" type="button"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-700">
                            <!-- You can add an eye icon here for show/hide password functionality -->
                            <i x-show="show" class="ph ph-eye"></i>
                            <i x-show="!show" class="ph ph-eye-slash"></i>
                        </button>
                    </div>
                    @error('password')
                    <div class="mt-2 text-sm text-red-400">
                        <strong>{{ $message }}</strong>
                    </div>
                    @enderror
                </div>
                <div class="mb-6" x-data="{ show: false }">
                    <label class="block mb-2 text-sm font-bold text-gray-700" for="password">Ulangi Password
                        Baru</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'"
                            class="w-full px-3 py-3 text-sm leading-tight text-gray-700 border border-gray-400 rounded-lg outline-none appearance-none focus:outline-none focus:shadow-outline"
                            id="password" name="password_confirmation" placeholder="Masukkan Password" required/>
                        <button @click="show = !show" type="button"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-700">
                            <!-- You can add an eye icon here for show/hide password functionality -->
                            <i x-show="show" class="ph ph-eye"></i>
                            <i x-show="!show" class="ph ph-eye-slash"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                    <div class="mt-2 text-sm text-red-400">
                        <strong>{{ $message }}</strong>
                    </div>
                    @enderror
                </div>

                <div class="flex flex-col gap-2 mb-6">
                    <button
                        class="w-full px-4 py-2 font-bold text-white bg-[#7F56D9] rounded-lg disabled:bg-gray-300 focus:outline-none focus:shadow-outline hover:bg-purple-700"
                        type="submit">
                        Kirim
                    </button>
                    <a href="{{ route('login') }}"
                        class="w-full px-4 py-2 font-bold text-center text-white bg-gray-400 border rounded-lg disabled:bg-gray-300 focus:outline-none focus:shadow-outline hover:bg-gray-500"
                        type="submit">
                        Kembali
                    </a>
                </div>
            </form>

        </div>
    </div>
</body>

</html>
