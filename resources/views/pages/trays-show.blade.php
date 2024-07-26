@section('title', 'Show Baki')
<x-layout>
    <div class="container py-4 mx-auto">
        <a href="/" class="flex items-center gap-4 mb-4 text-gray-500 hover:text-gray-700">
            <i class="text-2xl ph ph-caret-left"></i>
            <h1 class="text-2xl ">Etalase {{ $tray->code }}</h1>
        </a>


        <div class="grid grid-cols-1 gap-4 mb-8 md:grid-cols-3">
            <div class="grid gap-3 p-4 bg-white border rounded-lg">
                <h2 class="text-lg">Jumlah Barang di Baki</h2>
                <p class="text-4xl">{{ $countGoods }}</p>
                <p class="text-gray-500">Jumlah barang baki</p>
            </div>
            <div class="grid gap-3 p-4 bg-white border rounded-lg">
                <h2 class="text-lg">Slot Kosong Baki</h2>
                <p class="text-4xl">{{ $tray->capacity - $countGoods }}</p>
                <p class="text-gray-500">Jumlah slot kosong baki</p>
            </div>
            <div class="grid gap-3 p-4 bg-white border rounded-lg">
                <h2 class="text-lg">Berat</h2>
                <p class="text-4xl">{{ $totalWeight }} gr</p>
                <p class="text-gray-500">Jumlah total berat baki</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6">
            @foreach ($goods as $good)
                <div class="p-4 transition-shadow bg-white border cursor-pointer hover:shadow-md">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-lg font-semibold">{{ $good->name }}</p>
                        <i class="ph ph-caret-right"></i>
                    </div>
                    <div class="flex justify-between">
                        <p class="text-sm text-yellow-500">{{ $good->rate }}%</p>
                        <p class="text-sm text-green-500">{{ $good->size }}gr</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-layout>
