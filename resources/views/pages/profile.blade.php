<x-layout>
    <x-header title="Profile">
    </x-header>

    <div x-data="{ isEdit: true }" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 mt-5">
        <div class="rounded-xl border p-3 bg-white flex flex-col gap-3 justify-center items-center py-5">
            <div class="rounded-full w-28 h-28 bg-purple-500 flex justify-center items-center text-4xl text-white">
                AW
            </div>
            <div class="rounded px-2 py-1 border text-sm text-gray-600">
                Superadmin
            </div>
            <div class="bg-gray-50 border px-6 py-2 w-full">
                <label class="block mb-1" for="name">Nama</label>
                <input x-bind:disabled="isEdit" type="text" class="disabled:bg-transparent w-full disabled:border-none" value="Saputra Budi Utama">
            </div>
            <div class="bg-gray-50 border px-6 py-2 w-full">
                <label class="block mb-1" for="email">Email</label>
                <input x-bind:disabled="isEdit" type="text" class="disabled:bg-transparent w-full disabled:border-none" value="andikawijaya@gmail.com">
            </div>
            <div class="bg-gray-50 border px-6 py-2 w-full">
                <label class="block mb-1" for="phone">No Telefon</label>
                <input x-bind:disabled="isEdit" type="text" class="disabled:bg-transparent w-full disabled:border-none" value="085131235356">
            </div>
            <div class="flex justify-center gap-4">
                <button x-show="isEdit" @click="isEdit = !isEdit" class="bg-purple-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                    Ubah Profil
                    <i class="ph ph-user"></i>
                </button>
                <button x-show="!isEdit" @click="isEdit = !isEdit" class="bg-purple-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                    Simpan Perubahan
                    <i class="ph ph-user"></i>
                </button>
                <button class="border px-4 py-2 rounded-lg flex items-center gap-2">
                    Ganti Password
                    <i class="ph ph-keyhole"></i>
                </button>

            </div>
        </div>


    </div>
</x-layout>