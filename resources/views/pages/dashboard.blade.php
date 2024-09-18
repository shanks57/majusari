@section('title', 'Dashboard')
<x-layout>
    
    <div class="my-2">
        @if(!$hasGoldRateToday)
        <div class="p-4 text-sm text-red-800 bg-red-100 border border-red-200 rounded-lg dark:bg-red-800/10 dark:border-red-900 dark:text-red-500" role="alert" tabindex="-1" aria-labelledby="hs-soft-color-danger-label">
            <span id="hs-soft-color-danger-label" class="font-bold">Peringatan!</span> Harga Kurs Emas untuk hari ini belum diperbarui. Silakan perbarui harga kurs emas untuk hari ini.
        </div>
        @endif
    </div>
    
    <x-header title="Dashboard" subtitle="Dashboard">
    </x-header>

    <div x-data="{ activeTab: 'summary' }" class="flex p-1 mt-4 mb-6 bg-gray-100 rounded-lg w-fit">
        <button id="tab-summary" @click="activeTab = 'summary'" :class="{'bg-white': activeTab === 'summary'}"
            class="px-4 py-2 rounded tab-button"
            :class="{'active': activeTab === 'summary', 'bg-white': activeTab !== 'summary'}">Ringkasan</button>
        <button id="tab-sales" @click="activeTab = 'sales'" :class="{'bg-white': activeTab === 'sales'}"
            class="px-4 py-2 mx-2 rounded tab-button"
            :class="{'active': activeTab === 'sales', 'bg-white': activeTab !== 'sales'}">Penjualan</button>
        <button id="tab-weight" @click="activeTab = 'weight'" :class="{'bg-white': activeTab === 'weight'}"
            class="px-4 py-2 rounded tab-button"
            :class="{'active': activeTab === 'weight', 'bg-white': activeTab !== 'weight'}">Berat Barang</button>
        <button id="tab-karatage" @click="activeTab = 'karatage'" :class="{'bg-white': activeTab === 'karatage'}"
            class="px-4 py-2 rounded tab-button"
            :class="{'active': activeTab === 'karatage', 'bg-white': activeTab !== 'karatage'}">Total Karat</button>
    </div>
    <div id="tab-content-summary" class="tab-content">
        <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-2 lg:grid-cols-4">
            <div x-data="{ isOpen: false }" class="p-4 bg-white border rounded-lg">
                <h2 class="mb-4 font-semibold text-md">Etalase</h2>
                
                <!-- Trigger Button for Accordion -->
                <button @click="isOpen = !isOpen" class="flex items-center w-full gap-2">
                    <div class="text-3xl font-semibold">{{ $goods_in_showcase_stats['total_items'] }}</div>
                    <div class="px-3 py-1 text-xs text-green-500 bg-green-100 rounded-full">
                        {{ $goods_in_showcase_stats['total_weight'] }} gr
                    </div>
                    <!-- Accordion Indicator -->
                    <svg xmlns="http://www.w3.org/2000/svg" 
                        :class="{ 'rotate-180': isOpen }"
                        class="w-6 h-6 transition-transform duration-300 transform" 
                        fill="none" 
                        viewBox="0 0 24 24" 
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div class="mt-3 text-xs text-gray-400">Total barang di etalase</div>

                <!-- Accordion Content -->
                <div x-show="isOpen" x-transition class="mt-4 text-sm">
                    @foreach ($cardGoodsSummary as $summary)
                        <p class="mb-2">
                            Kadar <b>{{ $summary->rate }}%</b> : Total Berat <b>{{ number_format($summary->total_weight, 2) }}gr</b>, Total Barang <b>{{ $summary->total_items }}pcs</b>
                        </p>
                    @endforeach
                </div>
            </div>

            <div class="p-4 bg-white border rounded-lg">
                <h2 class="mb-4 font-semibold text-md">Brankas</h2>
                <div class="flex items-center gap-2">
                    <div class="text-3xl font-semibold">{{ $goods_in_safe_storage_stats['total_items'] }}</div>
                    <div class="px-3 py-1 text-xs text-gray-500 bg-gray-100 rounded-full">
                        {{ $goods_in_safe_storage_stats['total_weight'] }} gr
                    </div>
                </div>
                <div class="mt-3 text-xs text-gray-400">Total barang di brankas</div>
            </div>
            <div class="p-4 bg-white border rounded-lg">
                <h2 class="mb-4 font-semibold text-md">Customer</h2>
                <div class="flex items-center gap-2">
                    <div class="text-3xl font-semibold">{{ $customer_stats['total_items'] }}</div>
                    <div class="px-3 py-1 text-xs text-green-500 bg-green-100 rounded-full">Orang</div>
                </div>
                <div class="mt-3 text-xs text-gray-400">Total customer</div>
            </div>
            <div class="p-4 bg-white border rounded-lg">
                <h2 class="mb-4 font-semibold text-md">Penjualan</h2>
                <div class="flex items-center gap-2">
                    <div class="text-3xl font-semibold">{{ $transaction_stats['total_items_sold'] }}</div>
                    <div class="px-3 py-1 text-xs text-green-500 bg-green-100 rounded-full">
                        {{ $transaction_stats['total_weight_sold'] }} gr
                    </div>
                </div>
                <div class="mt-3 text-xs text-gray-400">Total barang terjual</div>
            </div>
        </div>
        <div class="grid grid-cols-12 gap-4 pb-4">
            <div class="col-span-7 p-4 bg-white border rounded-lg">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold">Ringkasan Penjualan</h2>
                    <a href="/sales" class="flex items-center text-sm hover:underline">
                        <span>Selengkapnya</span>
                        <i class="ph ph-caret-right"></i>
                    </a>
                </div>
                <div x-data="chartComponent" class="flex items-center justify-between mb-4">
                    <div>
                        <div class="flex items-start gap-2 mb-3 text-3xl font-semibold">
                            <span x-text="formatRupiah(totalSaleSalesSummary)"></span>
                        </div>
                        <div class="text-xs"><span class="text-gray-500">Total Penjualan (RP)</span> <span
                                x-text="filterLabel()"></span></div>
                    </div>
                    <div>
                        <div class="flex items-center space-x-2">
                            <select x-model="filter" @change="fetchChartData()"
                                class="block w-full px-4 py-3 text-sm border-gray-200 rounded-lg pe-9 focus:border-[#6634BB] focus:ring-[#6634BB] disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                <option value="year">Tahun Ini</option>
                                <option value="month">Bulan Ini</option>
                                <option value="week">Minggu Ini</option>
                            </select>
                        </div>

                    </div>
                </div>
                <canvas id="sales-chart" class="h-64 mt-4"></canvas>
            </div>
            <div class="col-span-5 px-6 pt-6 pb-4 bg-white border rounded-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="mb-1 text-xl font-bold">Kurs Emas</h2>
                        <div class="text-xs text-[#9A9A9A]">Harga Emas Hari Ini,
                            {{ Carbon\Carbon::now()->format('d M Y') }}
                        </div>
                    </div>
                    <button type="button"
                        class="bg-[#6634BB] text-[#F8F8F8] py-3 px-4 rounded-lg h-fit font-medium text-sm"
                        aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-scale-animation-modal"
                        data-hs-overlay="#hs-add-modal">Update
                        Kurs</button>
                </div>
                <div class="mt-4">
                    @php
                    $previousRate = null;
                    @endphp

                    @foreach ($goldRates->reverse() as $rate)
                    <div class="grid grid-cols-2 py-4">
                        <div class="flex flex-col gap-2">
                            <p class="text-xs text-gray-400">Harga Emas</p>
                            <span class="flex items-center gap-2">
                                @if($previousRate)
                                @if($rate->new_price > $previousRate->new_price)
                                <i class="text-xl text-green-500 ph ph-chart-line-up"></i>
                                @elseif($rate->new_price < $previousRate->new_price)
                                    <i class="text-xl text-red-500 ph ph-chart-line-down"></i>
                                    @else
                                    <i class="text-xl text-gray-500 ph ph-chart-line"></i>
                                    <!-- Ikon untuk harga tidak berubah -->
                                    @endif
                                    @else
                                    <i class="text-xl text-gray-500 ph ph-chart-line"></i>
                                    <!-- Ikon default untuk harga terlama -->
                                    @endif
                                    Rp {{ number_format($rate->new_price, 2, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <p class="text-xs text-gray-400">Tanggal & Jam</p>
                            <span>{{ $rate->created_at->format('d M Y H:i') }}</span>
                        </div>
                    </div>

                    @php
                    // Simpan harga saat ini sebagai harga sebelumnya untuk iterasi berikutnya
                    $previousRate = $rate;
                    @endphp
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div id="tab-content-sales" class="hidden tab-content">
        <!-- Empty content for now -->
        <div class="col-span-7 p-4 bg-white border rounded-lg">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold">Ringkasan Penjualan</h2>
                <a href="/sales" class="flex items-center text-sm hover:underline">
                    <span>Selengkapnya</span>
                    <i class="ph ph-caret-right"></i>
                </a>
            </div>
            <div x-data="chartDetailComponent" class="flex items-center justify-between mb-4">
                <div>
                    <div class="flex items-start gap-2 mb-3 text-3xl font-semibold">
                        <span x-text="formatRupiah(totalSales)"></span>
                    </div>
                    <div class="text-xs">
                        <span class="text-gray-500">Total Penjualan (RP)</span> <span x-text="filterLabel()"></span>
                    </div>
                </div>
                <div class="max-w-sm space-y-3">
                    <input type="date" x-model="startDate" @change="fetchChartData()"
                        class="block w-full px-4 py-3 text-sm border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                        placeholder="Start Date">

                    <input type="date" x-model="endDate" @change="fetchChartData()"
                        class="block w-full px-4 py-3 text-sm border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                        placeholder="End Date">
                </div>
            </div>
            <canvas id="sales-chart-detail" class="h-64 mt-4"></canvas>
        </div>
    </div>
    <div id="tab-content-weight" class="hidden tab-content">
        <div class="col-span-7 p-4 bg-white border rounded-lg">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold">Total Berat Barang</h2>
            </div>
            <div x-data="chartWeightComponent" class="flex items-center justify-between mb-4">
                <div>
                    <div class="flex gap-8">
                        <div>
                            <div class="flex items-center gap-2 mb-3 text-3xl font-semibold"
                                x-text="parseFloat(totalGoodsIn).toFixed(3) + ' gr'"></div>
                            <div class="flex items-center gap-3">
                                <div class="bg-[#ADD8E699]  w-8 h-3 rounded-full"></div>
                                <p class="text-xs text-gray-500">Total Barang Masuk</p>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-3 text-3xl font-semibold" x-text="parseFloat(totalGoodsOut).toFixed(3) + ' gr'">
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="bg-[#4682B499] w-8 h-3 rounded-full"></div>
                                <p class="text-xs text-gray-500">Total Barang Keluar</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <select x-model="filter" @change="fetchChartData()"
                        class="block w-full px-4 py-3 text-sm border-gray-200 rounded-lg pe-9 focus:border-[#6634BB] focus:ring-[#6634BB] disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                        <option value="year">Tahun Ini</option>
                        <option value="month">Bulan Ini</option>
                        <option value="week">Minggu Ini</option>
                    </select>
                </div>
            </div>
            <canvas id="weight-chart" class="h-64 mt-4"></canvas>
        </div>
    </div>
    <div id="tab-content-karatage" class="hidden tab-content">
        <div class="grid grid-cols-3 gap-4 ">
            <div class="p-4 bg-white border rounded-lg">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold">Total Jenis Barang</h2>
                </div>
                <canvas class="p-10" id="karatageTotalWeightChart"></canvas>
                <div id="legend-container"></div>
            </div>
            <div class="p-4 bg-white border rounded-lg">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold">Merk Barang</h2>
                </div>
                <canvas class="p-10" id="brandWeightChart"></canvas>
                <div id="legend-container-dua"></div>
            </div>
            <div class="p-4 bg-white border rounded-lg">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold">Kadar Barang</h2>
                </div>
                <canvas class="p-10" id="goldRateChart"></canvas>
                <div id="legend-container-tiga"></div>
            </div>
        </div>
    </div>

    {{-- tabs js --}}
    @include('components.js.tab-js')
    {{-- Ringkasan penjualan chart --}}
    @include('components.js.sales-summary-chart')
    {{-- Total Karat Chart --}}
    @include('components.js.total-karat-chart')
    {{-- Penjualan chart --}}
    @include('components.js.sales-chart')
    {{-- berat barang chart --}}
    @include('components.js.weight-of-goods-chart')

    @include('components.modal.dashboard.update-kurs')
    @include('components.modal.error-form-modal')
    @include('components.modal.error-modal')
    @include('components.modal.dashboard.success-modal')
</x-layout>