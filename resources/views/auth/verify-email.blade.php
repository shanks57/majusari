<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config("app.name") }} - Lupa Password</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            <h1 class="mb-3 text-3xl text-center">
                Verifikasi alamat email Anda
            </h1>

            <p class="mb-8 text-xs font-medium text-center text-gray-700 ">
                Terima kasih telah mendaftar! Sebelum memulai, harap verifikasi alamat email Anda dengan mengeklik
                tombol dibawah ini. Jika Anda tidak menerima email tersebut, kami akan
                dengan senang hati mengirimkan email lain kepada Anda.
            </p>
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                @if (session('status') == 'verification-link-sent')
                <div class="flex items-center justify-between w-full">
                    <button type="submit"
                        class="w-full px-4 py-2 font-bold text-white bg-[#7F56D9] rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:ring-opacity-50">
                        Resend Verification Email
                    </button>
                </div>
                @else
                <div class="flex items-center justify-between w-full">
                    <button type="submit"
                        class="w-full px-4 py-2 font-bold text-white bg-[#7F56D9] rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:ring-opacity-50">
                        Send Verification Email
                    </button>
                </div>
                @endif

            </form>

            <a href="{{ route('logout') }}" class="mt-4 text-sm text-gray-600 underline hover:text-gray-900"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Keluar
            </a>
            <form method="POST" action="{{ route('logout') }}" style="display: none" id="logout-form">
                @csrf
            </form>


            @if (session('status') == 'verification-link-sent')
            <div class="flex items-center gap-4 px-4 py-3 bg-gray-200 rounded-xl">
                <i class="text-2xl ph ph-telegram-logo"></i>
                <p class="text-sm">Reset link terkirim ke email anda, silahkan cek di inbox atau spam.</p>
            </div>
            @endif
        </div>
    </div>
</body>

</html>
