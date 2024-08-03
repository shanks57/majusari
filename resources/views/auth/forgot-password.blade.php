<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config("app.name") }} - Lupa Password</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @vite('resources/css/app.css') @vite('resources/js/app.js')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('styles')
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
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-bold text-gray-700" for="email">Email</label>
                    <input
                        class="w-full px-3 py-3 text-sm leading-tight text-gray-700 border border-gray-400 rounded-lg outline-none appearance-none focus:outline-none focus:shadow-outline @error('email') is-invalid @enderror"
                        id="email" name="email" type="email" placeholder="Masukkan email" value="{{ old('email') }}"
                        required autocomplete="email" autofocus />

                    @error('email')
                    <div class="mt-2 text-sm text-red-400">
                        Email tidak ditemukan.
                    </div>
                    @enderror
                </div>

                <div class="mb-6">
                    <button
                        class="w-full px-4 py-2 font-bold text-white bg-purple-500 rounded disabled:bg-gray-300 focus:outline-none focus:shadow-outline hover:bg-purple-600"
                        type="submit">
                        Kirim
                    </button>
                </div>
            </form>
            @if (session('status'))
                <div class="flex items-center gap-4 px-4 py-3 bg-gray-200 rounded-xl">
                    <i class="text-2xl ph ph-telegram-logo"></i>
                    <p class="text-sm">Reset link terkirim ke email anda, silahkan cek di inbox atau spam.</p>
                </div>
            @endif
        </div>
    </div>
</body>

</html>
