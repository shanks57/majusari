@section('title', 'Dashboard')
<x-layout>
    <x-header title="Dashboard">
    </x-header>
    <div x-data="{ activeTab: 'summary' }" class="flex mb-6 mt-4 bg-gray-100 w-fit p-1 rounded-lg">
        <button id="tab-summary" @click="activeTab = 'summary'" :class="{'bg-white': activeTab === 'summary'}"
            class="tab-button py-2 px-4 rounded"
            :class="{'active': activeTab === 'summary', 'bg-white': activeTab !== 'summary'}">Ringkasan</button>
        <button id="tab-sales" @click="activeTab = 'sales'" :class="{'bg-white': activeTab === 'sales'}"
            class="tab-button py-2 px-4 rounded mx-2"
            :class="{'active': activeTab === 'sales', 'bg-white': activeTab !== 'sales'}">Penjualan</button>
        <button id="tab-weight" @click="activeTab = 'weight'" :class="{'bg-white': activeTab === 'weight'}"
            class="tab-button py-2 px-4 rounded"
            :class="{'active': activeTab === 'weight', 'bg-white': activeTab !== 'weight'}">Berat Barang</button>
    </div>
    <div id="tab-content-summary" class="tab-content">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-4 border rounded-lg">
                <h2 class="text-md font-semibold mb-4">Etalase</h2>
                <div class="flex gap-2 items-center">
                    <div class="text-3xl font-semibold">{{ $goods_in_showcase_stats['total_items'] }}</div>
                    <div class="text-green-500 py-1 px-3 rounded-full text-xs bg-green-100">
                        {{ $goods_in_showcase_stats['total_weight'] }} gr
                    </div>
                </div>
                <div class="mt-3 text-xs text-gray-400">Total barang di etalase</div>
            </div>
            <div class="bg-white p-4 border rounded-lg">
                <h2 class="text-md font-semibold mb-4">Brankas</h2>
                <div class="flex gap-2 items-center">
                    <div class="text-3xl font-semibold">{{ $goods_in_safe_storage_stats['total_items'] }}</div>
                    <div class="text-gray-500 py-1 px-3 rounded-full text-xs bg-gray-100">
                        {{ $goods_in_safe_storage_stats['total_weight'] }} gr
                    </div>
                </div>
                <div class="mt-3 text-xs text-gray-400">Total barang di brankas</div>
            </div>
            <div class="bg-white p-4 border rounded-lg">
                <h2 class="text-md font-semibold mb-4">Customer</h2>
                <div class="flex gap-2 items-center">
                    <div class="text-3xl font-semibold">{{ $customer_stats['total_items'] }}</div>
                    <div class="text-green-500 py-1 px-3 rounded-full text-xs bg-green-100">Orang</div>
                </div>
                <div class="mt-3 text-xs text-gray-400">Total customer</div>
            </div>
            <div class="bg-white p-4 border rounded-lg">
                <h2 class="text-md font-semibold mb-4">Penjualan</h2>
                <div class="flex gap-2 items-center">
                    <div class="text-3xl font-semibold">{{ $transaction_stats['total_items_sold'] }}</div>
                    <div class="text-green-500 py-1 px-3 rounded-full text-xs bg-green-100">
                        {{ $transaction_stats['total_weight_sold'] }} gr
                    </div>
                </div>
                <div class="mt-3 text-xs text-gray-400">Total barang terjual</div>
            </div>
        </div>
        <div class="grid grid-cols-12 gap-4 pb-4">
            <div class="bg-white p-4 border rounded-lg col-span-7">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Ringkasan Penjualan</h2>
                    <div class="relative">
                        <button class="bg-gray-200 text-gray-700 py-2 px-4 rounded">Tahun Ini</button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded border hidden">
                            <!-- Dropdown menu here -->
                        </div>
                    </div>
                </div>
                <div class="text-3xl font-bold">Rp 60.000.000 <span class="text-green-500 text-lg">+23.2%</span></div>
                <div>Total Penjualan (RP) Tahun Ini</div>
                <canvas id="sales-chart" class="h-64 mt-4"></canvas>
            </div>
            <div class="bg-white pb-4 px-6 pt-6 border rounded-xl col-span-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold mb-1">Kurs Emas</h2>
                        <div class="text-xs text-[#9A9A9A]">Harga Emas Hari Ini,
                            {{ Carbon\Carbon::now()->format('d M Y') }}</div>
                    </div>
                    <button type="button" class="bg-[#6634BB] text-[#F8F8F8] py-3 px-4 rounded-lg h-fit font-medium text-sm"
                    aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-scale-animation-modal" data-hs-overlay="#hs-add-modal">Update
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
                            <span class="gap-2 flex items-center">
                                @if($previousRate)
                                @if($rate->new_price > $previousRate->new_price)
                                <i class="ph ph-chart-line-up text-green-500 text-xl"></i>
                                @elseif($rate->new_price < $previousRate->new_price)
                                    <i class="ph ph-chart-line-down text-red-500 text-xl"></i>
                                    @else
                                    <i class="ph ph-chart-line text-gray-500 text-xl"></i>
                                    <!-- Ikon untuk harga tidak berubah -->
                                    @endif
                                    @else
                                    <i class="ph ph-chart-line text-gray-500 text-xl"></i>
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
    <div id="tab-content-sales" class="tab-content hidden">
        <!-- Empty content for now -->
    </div>
    <div id="tab-content-weight" class="tab-content hidden">
        <!-- Empty content for now -->
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll('.tab-button');
            const contents = document.querySelectorAll('.tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    tabs.forEach(t => t.classList.remove('active', 'bg-gray-300'));
                    contents.forEach(c => c.classList.add('hidden'));

                    tab.classList.add('active', 'bg-gray-300');
                    const contentId = `tab-content-${tab.id.split('-')[1]}`;
                    document.getElementById(contentId).classList.remove('hidden');
                });
            });

            const ctx = document.getElementById('sales-chart').getContext('2d');
            const salesData = [60, 70, 100, 80, 70, 50, 75, 60, 30, 50, 60, 100];
            const backgroundColors = salesData.map((value, index) => {
                return index === 6 ? '#E9D2F7' : '#E6E6E6'; // Purple for July, gray for others
            });

            const salesChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT',
                        'NOV', 'DEC'
                    ],
                    datasets: [{
                        label: 'Sales',
                        data: salesData,
                        backgroundColor: backgroundColors,
                        borderColor: backgroundColors,
                        borderWidth: 0,
                        borderRadius: 4
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: false // This removes the grid lines
                            }
                        },
                        x: {
                            grid: {
                                display: false // This removes the grid lines
                            }
                        }
                    }
                }
            });
        });

    </script>
@include('components.modal.dashboard.update-kurs')
@include('components.modal.error-modal')
@include('components.modal.dashboard.success-modal')
</x-layout>
