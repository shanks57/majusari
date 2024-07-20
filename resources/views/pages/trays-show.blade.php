<x-layout>


    <div class="container mx-auto py-4">
        <a href="/" class="text-gray-500 gap-4 flex items-center hover:text-gray-700 mb-4">
            <i class="ph ph-caret-left text-2xl"></i>
            <h1 class="text-2xl ">Etalase {{ $tray->code }}</h1>
        </a>


        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-white p-4 rounded-lg border grid gap-3">
                <h2 class="text-lg">Jumlah Barang di Baki</h2>
                <p class="text-4xl">{{ $tray->count() }}</p>
                <p class="text-gray-500">Jumlah barang baki</p>
            </div>
            <div class="bg-white p-4 rounded-lg border grid gap-3">
                <h2 class="text-lg">Slot Kosong Baki</h2>
                <p class="text-4xl">{{ $tray->where('capacity', '0')->count() }}</p>
                <p class="text-gray-500">Jumlah slot kosong baki</p>
            </div>
            <div class="bg-white p-4 rounded-lg border grid gap-3">
                <h2 class="text-lg">Berat</h2>
                <p class="text-4xl">{{ $tray->sum('weight') }}gr</p>
                <p class="text-gray-500">Jumlah total berat baki</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6">
            @foreach ($goods as $good)
                <div class="bg-white border p-4 cursor-pointer hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-center mb-3">
                        <p class="text-lg font-semibold">{{ $good->code }}</p>
                        <i class="ph ph-caret-right"></i>
                    </div>
                    <div class="flex justify-between">
                        <p class="text-sm text-yellow-500">{{ $tray->capacity ? '70%' : 'Baki Kosong' }}</p>
                        <p class="text-sm text-green-500">{{ $tray->weight }}gr</p>
                    </div>
                    @if (!$tray->capacity)
                        <button class="mt-2 px-4 py-2 bg-purple-100 text-purple-700 rounded-full">Tambah</button>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</x-layout>
