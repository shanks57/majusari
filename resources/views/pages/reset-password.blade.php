<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config("app.name") }} - @yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @vite('resources/css/app.css') @vite('resources/js/app.js')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('styles')
</head>

<body class="h-screen flex">
    <div class="w-1/2 bg-gray-200 hidden lg:block bg-cover bg-bottom p-8" style="background-image: url(/images/login-image.png);">
        <h1 class="text-white text-5xl leading-normal">Selamat Datang <br> <span class="italic">di</span> Toko Emas <br> Majusari</h1>
    </div>
    <div class="flex flex-col justify-center w-full lg:w-1/2 p-8">
        <div class="max-w-md mx-auto">
            <h1 class="text-3xl mb-3 max-w-sm text-center">
                Lupa Password
            </h1>
            <p class="mb-6 text-center text-xs">
                Masukkan email yang valid untuk melakukan reset password
            </p>
            <form>
                <div class="mb-6" x-data="{ show: false }">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password Baru</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" class="appearance-none border border-gray-400 outline-none rounded-lg text-sm w-full py-3 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="password" placeholder="Masukkan Password" />
                        <button @click="show = !show" type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-700">
                            <!-- You can add an eye icon here for show/hide password functionality -->
                            <i x-show="!show" class="ph ph-eye"></i>
                            <i x-show="show" class="ph ph-eye-slash"></i>
                        </button>
                    </div>
                </div>
                <div class="mb-6" x-data="{ show: false }">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Ulangi Password Baru</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" class="appearance-none border border-gray-400 outline-none rounded-lg text-sm w-full py-3 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="password" placeholder="Masukkan Password" />
                        <button @click="show = !show" type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-700">
                            <!-- You can add an eye icon here for show/hide password functionality -->
                            <i x-show="!show" class="ph ph-eye"></i>
                            <i x-show="show" class="ph ph-eye-slash"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-6">
                    <button class="w-full disabled:bg-gray-300 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline bg-purple-500 hover:bg-purple-600" type="button">
                        Kirim
                    </button>
                </div>
            </form>

        </div>
    </div>
</body>

</html>