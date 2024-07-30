<div id="hs-kelola-baki-{{ $etalase->id }}"
    class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none"
    role="dialog" tabindex="-1" aria-labelledby="hs-kelola-baki-{{ $etalase->id }}-label">
    <div
        class="hs-overlay-animation-target hs-overlay-open:scale-100 hs-overlay-open:opacity-100 scale-95 opacity-0 ease-in-out transition-all duration-200 sm:max-w-6xl sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
        <div
            class="flex flex-col w-full bg-white border-[0.5px] shadow-sm pointer-events-auto rounded-xl border-[#D9D9D9] p-6 gap-4">
            <div class="flex items-center justify-between pb-4 border-b">
                <h3 id="hs-kelola-baki-{{ $etalase->id }}-label" class="text-xl font-semibold text-[#344054]">
                    Kelola Baki
                </h3>
                <button type="button" class="text-red-500" aria-label="Close"
                    data-hs-overlay="#hs-kelola-baki-{{ $etalase->id }}">
                    <i class="text-2xl ph ph-x-circle"></i>
                </button>
            </div>
            <div class="">

                <div class="grid gap-3 mb-3">
                    <div class="flex gap-3">
                        <span class="font-bold text-purple-400 text-xl">--</span>
                        <span class="font-medium text-xl text-[#344054]">Etalase {{ $etalase->name }}</span>
                    </div>
                    <div class="grid grid-cols-10 gap-2">
                        @foreach ($etalase->trays as $tray)
                        <div
                            class="w-full flex justify-between items-center px-3.5 py-2.5 border-l-2 border-purple-400 bg-gray-50 rounded">
                            <span>{{ $tray->code }}</span>
                            <a href="{{ route('find-goods-tray', $tray->id) }}">
                                <i class="ph ph-arrow-circle-right text-purple-400 text-xl"></i>
                            </a>
                        </div>
                        @endforeach
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
