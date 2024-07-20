<x-layout>
    <x-header title="Detail Baki">
    </x-header>

    <div class="container py-4 mx-auto">
        <div class="mb-4">
            <input type="text" id="searchTrays"
                class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:border-[#79799B]"
                placeholder="Cari baki yang akan ditampilkan">

        </div>

        @if ($goodtrays->isEmpty())
            <div class="rounded-xl border-2 border-gray-200 min-h-[60vh] bg-gray-100 flex justify-center items-center">
                <div class="flex flex-col gap-3 items-center">
                    <img class="max-w-sm" src="/images/empty-illustration.png" alt="empty-illustration">
                    <span class="text-xl">Belum ada baki yang ditampilkan</span>
                </div>
            </div>
        @else
            <div class="bg-white border-2 py-3 px-4 rounded-xl">

                <div class="grid gap-3 mb-3">
                    <div class="flex gap-3">
                        <span class="font-bold text-purple-400">-</span>
                        <span>Etalase KL</span>
                    </div>
                    <div class="grid grid-cols-10 gap-2">
                        @foreach ($goodtrays as $goodtray)
                            <div
                                class="w-full flex justify-between items-center p-3 border-l-2 border-purple-400 bg-gray-50">
                                <span>{{ $goodtray->code }}</span>
                                <a href="{{ route('find-goods-tray', $goodtray->id) }}">
                                    <i class="ph ph-arrow-circle-right text-purple-400"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>

                </div>

            </div>
        @endif
    </div>

</x-layout>
