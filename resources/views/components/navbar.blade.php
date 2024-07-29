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
            <i class="ph ph-bell text-purple-500 text-2xl"></i>
        </a>
        <a class="flex items-center gap-4" href="/profile">
            <div class="flex items-center justify-center w-10 h-10 text-white bg-purple-500 rounded-full">
                AW
            </div>
            <div>
                <span class="block text-sm">Andika Wijaya</span>
                <span class="text-xs text-gray-500">andikawijaya@majusari.com</span>
            </div>
        </a>
    </div>
</nav>