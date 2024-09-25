@section('title', 'Detail Baki')
<x-layout>
    <x-header title="Detail Baki" subtitle="Baki">
    </x-header>

    <div x-data="goodsTraySearch()" class="container py-4 mx-auto">
        <div class="relative w-full mx-auto mb-4">
            <input
                type="text"
                id="searchEtalase"
                x-model="search"
                class="w-full p-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:border-[#79799B]"
                placeholder="Cari di etalase"
            >
            <i class="ph ph-magnifying-glass absolute left-3 top-3 text-[#2D2F30]"></i>
        </div>

        <!-- Kondisi untuk menampilkan pesan kosong jika belum ada pencarian -->
        <template x-if="!search.trim()">
             <div class="rounded-xl border-2 border-gray-200 min-h-[60vh] bg-gray-100 flex justify-center items-center">
                <div class="flex flex-col items-center gap-3">
                    <img class="max-w-sm" src="/images/empty-illustration.png" alt="empty-illustration">
                    <span class="text-xl">Belum ada Etalase yang ditampilkan</span>
                </div>
            </div>
        </template>

        <!-- Kondisi untuk menampilkan hasil pencarian -->
        <template x-if="search.trim() && filteredShowcases.length === 0">
            <div class="rounded-xl border-2 border-gray-200 min-h-[60vh] bg-gray-100 flex justify-center items-center">
                <div class="flex flex-col items-center gap-3">
                    <img class="max-w-sm" src="/images/empty-illustration.png" alt="empty-illustration">
                    <span class="text-xl">Belum ada Etalase yang ditampilkan</span>
                </div>
            </div>
        </template>

        <!-- Menampilkan showcase berdasarkan hasil pencarian -->
        <template x-if="search.trim()">
            <template x-for="showcase in filteredShowcases" :key="showcase.id">
                <div class="px-4 py-3 mb-4 bg-white border-2 rounded-xl">
                    <div class="grid gap-3 mb-3">
                        <div class="flex gap-3">
                            {{-- <span x-text="showcase.code"></span> --}}
                            <span class="font-bold text-purple-400">-</span>
                            <span x-text="'Etalase ' + showcase.name"></span>
                        </div>
                        <div class="grid grid-cols-10 gap-2">
                            <template x-if="filteredGoodtrays(showcase).length === 0">
                                <div class="col-span-10 text-center text-gray-500">
                                    Tidak ada baki untuk etalase ini
                                </div>
                            </template>

                            <template x-for="goodtray in filteredGoodtrays(showcase)" :key="goodtray.id">
                                <div class="flex items-center justify-between w-full p-3 border-l-2 border-purple-400 bg-gray-50">
                                    <span x-text="goodtray.code"></span>
                                    <a :href="routeToGoodTray(goodtray.id)">
                                        <i class="text-purple-400 ph ph-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </template>
    </div>

    @include('components.modal.success-modal')

    <script>
        function goodsTraySearch() {
            return {
                search: '',
                showcases: @json($showcases),
                goodtrays: @json($goodtrays),

                get filteredShowcases() {
                    return this.showcases.filter(showcase =>
                        showcase.name.toLowerCase().includes(this.search.toLowerCase()) ||
                        showcase.code.toLowerCase().includes(this.search.toLowerCase()) ||
                        this.filteredGoodtrays(showcase).length > 0
                    );
                },

                filteredGoodtrays(showcase) {
                    return this.goodtrays.filter(goodtray =>
                        goodtray.showcase_id === showcase.id &&
                        goodtray.code.toLowerCase().includes(this.search.toLowerCase())
                    );
                },

                routeToGoodTray(goodtrayId) {
                    return `{{ url('/goods/trays/') }}/${goodtrayId}`;
                }
            }
        }
    </script>

</x-layout>
