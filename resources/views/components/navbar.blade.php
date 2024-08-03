<nav class="px-[80px] py-6 bg-white flex justify-between items-center border-b">
    <a href="/" class="text-2xl font-semibold font-inter">Majusari</a>
    <div class="flex items-center justify-center gap-2 text-lg font-medium leading-7">
        <a href="/" class="px-6 py-3 rounded-md  {{ request()->is('/') ? 'bg-purple-100 text-purple-700 font-semibold' : 'bg-white hover:bg-purple-100  hover:text-purple-700' }}">
            Dashboard
        </a>
        <x-dropdown class="px-6 py-3 rounded-md flex items-center gap-1 {{ request()->is('master/*') ? 'bg-purple-100 text-purple-700 font-semibold' : 'bg-white hover:bg-purple-100  hover:text-purple-700' }}" title="Master">
            <div class="py-1" role="none">
                <a href="/master/showcases" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-item-0">Etalase</a>
                <a href="/master/types" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-item-1">Jenis Barang</a>
                <a href="/master/brands" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-item-2">Merk Barang</a>
                <a href="/master/customers" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-item-2">Pelanggan</a>
                <a href="/master/employees" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-item-2">Pegawai</a>
            </div>
        </x-dropdown>
        <x-dropdown class="px-6 py-3 rounded-md flex items-center gap-1 {{ request()->is('goods/*') ? 'bg-purple-100 text-purple-700 font-semibold' : 'bg-white hover:bg-purple-100  hover:text-purple-700' }}" title="Barang">
            <div class="py-1" role="none">
                <a href="/goods/showcases" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-item-0">Etalase</a>
                <a href="/goods/trays" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-item-1">Detail Baki</a>
                <a href="/goods/safe" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-item-2">Brankas</a>
            </div>
        </x-dropdown>
        <a href="/sales" class="px-6 py-3 rounded-md  {{ request()->is('sales') ? 'bg-purple-100 text-purple-700 font-semibold' : 'bg-white hover:bg-purple-100  hover:text-purple-700' }}">
            Penjualan
        </a>

    </div>
    <div class="flex items-center gap-6">
        <a href="/notification" class="flex items-center justify-center w-10 h-10 text-white bg-purple-100 rounded-full">
            <i class="text-2xl text-purple-500 ph ph-bell"></i>
        </a>
        @php
        $name = Auth::user()->name;
        $nameParts = explode(' ', $name); // Pisahkan nama berdasarkan spasi
        $initials = strtoupper(substr($nameParts[0], 0, 1)); // Ambil huruf awal dari nama pertama

        if (isset($nameParts[1])) {
        $initials .= strtoupper(substr($nameParts[1], 0, 1)); // Tambahkan huruf awal dari nama belakang jika ada
        }
        @endphp
        <div class="hs-dropdown relative inline-flex [--gpu-acceleration:false]">
            <button id="hs-dropdown-scale-animation" type="button" id="hs-dropdown-scale-animation" type="button" class="flex items-center gap-4 hs-dropdown-toggle" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                <div class="flex items-center justify-center w-10 h-10 text-white bg-purple-500 rounded-full">
                    {{ $initials }}
                </div>
                <div class="text-start">
                    <span class="block text-sm">{{ Auth::user()->name }}</span>
                    <span class="text-xs text-gray-500">{{ Auth::user()->email }}</span>
                </div>
                <svg class="hs-dropdown-open:rotate-180 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m6 9 6 6 6-6" />
                </svg>
            </button>
            <div class="hs-dropdown-menu w-72 hs-dropdown-open:scale-100 hs-dropdown-open:opacity-100 scale-95 opacity-0 z-10 ease-in-out transition-[transform,opacity] duration-200 min-w-60 bg-white shadow-md rounded-lg p-2 dark:bg-neutral-800 dark:border dark:border-neutral-700 dark:divide-neutral-700 hidden" role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-scale-animation">
                <a href="/profile" class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700" href="#">
                    Profile
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button type="submit" class="flex w-full items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700">
                        Logout
                    </button>
                </form>

            </div>
        </div>
    </div>
</nav>