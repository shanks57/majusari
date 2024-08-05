@section('title', 'Profile')
<x-layout>
    <x-header title="Profile" subtitle="Profile">
    </x-header>

    @php
    $name = Auth::user()->name;
    $nameParts = explode(' ', $name); // Pisahkan nama berdasarkan spasi
    $initials = strtoupper(substr($nameParts[0], 0, 1)); // Ambil huruf awal dari nama pertama

    if (isset($nameParts[1])) {
    $initials .= strtoupper(substr($nameParts[1], 0, 1)); // Tambahkan huruf awal dari nama belakang jika ada
    }
    @endphp
    <div class="grid grid-cols-1 mt-5 md:grid-cols-2 lg:grid-cols-3">
        <div
            class="flex flex-col items-center justify-center gap-3 p-3 py-5 bg-white border border-[#EAECF0] rounded-xl">
            <div class="flex items-center justify-center text-4xl text-white bg-[#7F56D9] rounded-full w-28 h-28">
                {{ $initials }}
            </div>
            <div class="px-2 py-1 text-sm text-gray-600 border rounded">
                {{ Auth::user()->getRoleNames()->implode(', ') }}
            </div>
            <div class="w-full px-8 py-2.5 border bg-gray-50 border-[#EAECF0]">
                <label class="block mb-1 text-[#151617] text-sm">Nama</label>
                <span class="text-base font-medium text-black">{{ Auth::user()->name }}</span>
            </div>
            <div class="w-full px-8 py-2.5 border bg-gray-50 border-[#EAECF0]">
                <label class="block mb-1 text-[#151617] text-sm">Email</label>
                <span class="text-base font-medium text-black">{{ Auth::user()->email }}</span>
            </div>
            <div class="w-full px-8 py-2.5 border bg-gray-50 border-[#EAECF0]">
                <label class="block mb-1 text-[#151617] text-sm">Nomer Telepon</label>
                <span class="text-base font-medium text-black">{{ Auth::user()->phone }}</span>
            </div>
            <div class="flex justify-center gap-4">
                <button class="flex items-center gap-2 px-4 py-2 text-white bg-[#6634BB] rounded-lg text-sm font-medium"
                    aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-update-profile-modal"
                    data-hs-overlay="#hs-update-profile-modal">
                    Ubah Profil
                    <i class="ph ph-user"></i>
                </button>
                <button class="flex items-center gap-2 px-4 py-2 text-sm font-medium border rounded-lg"
                    aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-update-password-modal"
                    data-hs-overlay="#hs-update-password-modal">
                    Ganti Password
                    <i class="ph ph-keyhole"></i>
                </button>
            </div>
        </div>
    </div>
</x-layout>

@include('components.modal.profile.update-password')
@include('components.modal.profile.update-profile')
@include('components.modal.error-modal')
@include('components.modal.success-modal')
