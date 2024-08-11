@section('title', 'Dashboard')
<x-layout>
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
    </div>
    <div id="tab-content-summary" class="tab-content">
        <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-2 lg:grid-cols-4">
            <div class="p-4 bg-white border rounded-lg">
                <h2 class="mb-4 font-semibold text-md">Etalase</h2>
                <div class="flex items-center gap-2">
                    <div class="text-3xl font-semibold">{{ $goods_in_showcase_stats['total_items'] }}</div>
                    <div class="px-3 py-1 text-xs text-green-500 bg-green-100 rounded-full">
                        {{ $goods_in_showcase_stats['total_weight'] }} gr
                    </div>
                </div>
                <div class="mt-3 text-xs text-gray-400">Total barang di etalase</div>
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
                <h2 class="text-xl font-semibold">Penjualan</h2>
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
            <div class="flex items-center justify-between mb-4">
                <div class="flex gap-8">
                    <div>
                        <div class="flex items-center gap-2 mb-3 text-3xl font-semibold">16926.33 gr <span
                                class="px-2 py-1 text-xs text-green-500 bg-green-100 rounded-full">
                                <i class="ph ph-trend-up"></i>+23.2%</span></div>
                        <div class="flex items-center gap-3">
                            <div class="bg-[#ADD8E699]  w-8 h-3 rounded-full"></div>
                            <p class="text-xs text-gray-500">Total Barang Masuk</p>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-3 text-3xl font-semibold">12145.11 gr <span
                                class="px-2 py-1 text-xs text-red-500 bg-red-100 rounded-full">
                                <i class="ph ph-trend-down"></i>+23.2%</span></div>
                        <div class="flex items-center gap-3">
                            <div class="bg-[#4682B499] w-8 h-3 rounded-full"></div>
                            <p class="text-xs text-gray-500">Total Barang Keluar</p>
                        </div>
                    </div>
                </div>
                <div x-data="{ open: false, selectedOption: 'Tahun Ini' }" class="relative inline-block text-left">
                    <div>
                        <button @click="open = !open" type="button"
                            class="inline-flex items-center justify-center w-full gap-1 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500">
                            <i class="ph ph-calendar-blank"></i>
                            <span x-text="selectedOption"></span>
                            <i class="ph ph-caret-down"></i>
                        </button>
                    </div>

                    <div x-show="open" @click.away="open = false"
                        class="absolute right-0 w-56 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
                        <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                            <span @click="selectedOption = 'Tahun Ini'; open = false"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                role="menuitem">Tahun Ini</span>
                            <span @click="selectedOption = 'Bulan Ini'; open = false"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                role="menuitem">Bulan Ini</span>
                            <span @click="selectedOption = 'Minggu Ini'; open = false"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                role="menuitem">Minggu Ini</span>
                            <span @click="selectedOption = 'Hari Ini'; open = false"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                role="menuitem">Hari Ini</span>
                        </div>
                    </div>
                </div>
            </div>
            <canvas id="weight-chart" class="h-64 mt-4"></canvas>
        </div>

    </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
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


            const weightChartElement = document.getElementById('weight-chart').getContext('2d');
            const goodsInData = @json($goodsInValues);
            const goodsOutData = @json($goodsOutValues);
            const totalGoodsIn = @json($totalGoodsIn);
            const totalGoodsOut = @json($totalGoodsOut);
            const filter = @json($filter); // Filter yang diterima dari server

            // Menentukan label berdasarkan filter
            let labels = [];
            if (filter === 'tahun-ini') {
                labels = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];
            } else if (filter === 'bulan-ini') {
                const startDate = new Date();
                const endDate = new Date(startDate.getFullYear(), startDate.getMonth() + 1, 0);
                let date = new Date(startDate.getFullYear(), startDate.getMonth(), 1);
                while (date <= endDate) {
                    labels.push(date.toISOString().split('T')[0]); // Menggunakan format YYYY-MM-DD
                    date.setDate(date.getDate() + 1);
                }
            } else if (filter === 'minggu-ini') {
                const startDate = new Date(new Date().setDate(new Date().getDate() - new Date().getDay()));
                const endDate = new Date(startDate);
                endDate.setDate(endDate.getDate() + 6);
                let date = startDate;
                while (date <= endDate) {
                    labels.push(date.toISOString().split('T')[0]); // Menggunakan format YYYY-MM-DD
                    date.setDate(date.getDate() + 1);
                }
            } else if (filter === 'hari-ini') {
                labels = ['00:00', '01:00', '02:00', '03:00', '04:00', '05:00', '06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'];
            }
            const myChart = new Chart(weightChartElement, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Total Barang Masuk',
                            data: goodsInData,
                            backgroundColor: 'rgba(173, 216, 230, 0.6)', // lightblue
                            borderRadius: 4,
                            datalabels: {
                                color: '#fff',
                                align: 'top',
                                anchor: 'end',
                                formatter: (value, context) => {
                                    console.log('Context:', context);
                                    const monthIndex = context.dataIndex;
                                    const totalGoodsInMonth = totalGoodsIn[monthIndex];
                                    console.log('Total Goods In Month:', totalGoodsInMonth);
                                    return `Total Barang Masuk : ${totalGoodsInMonth} barang`;
                                }
                            }

                        },
                        {
                            label: 'Total Barang Keluar',
                            data: goodsOutData,
                            backgroundColor: 'rgba(70, 130, 180, 0.6)', // steelblue
                            borderRadius: 4,
                            datalabels: {
                                color: '#fff',
                                align: 'top',
                                formatter: (value, context) => {
                                    const monthIndex = context.dataIndex;
                                    const totalOut = goodsOutData[monthIndex];
                                    const totalGoodsOutMonth = totalGoodsOut[monthIndex];
                                    return `Keluar: Rp ${totalOut} Jt\nTotal: ${totalGoodsOutMonth} items`;
                                }
                            }
                        }
                    ]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false // Hide the legend
                        },
                         tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const datasetLabel = context.dataset.label || '';
                                    const dataIndex = context.dataIndex;
                                    const value = context.raw;

                                    let additionalInfo = '';
                                    if (context.dataset.label === 'Total Barang Masuk') {
                                        additionalInfo = `Jumlah Masuk: ${totalGoodsIn[dataIndex]} Barang`;
                                    } else if (context.dataset.label === 'Total Barang Keluar') {
                                        additionalInfo = `Jumlah Keluar: ${totalGoodsOut[dataIndex]} Barang`;
                                    }

                                    return `${datasetLabel}: Rp ${value} Jt\n${additionalInfo}`;
                                }
                            }
                            }
                        },

                    scales: {
                        x: {
                            grid: {
                                display: false,
                            }
                        },
                        y: {
                            grid: {
                                display: false,
                            },
                            beginAtZero: true,
                        }
                    },
                    elements: {
                        bar: {
                            borderRadius: 4,
                        }
                    }
                }
            });

        });
    </script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('chartComponent', () => ({
                filter: 'year',
                chart: null,
                totalSaleSalesSummary: 0,

               filterLabel() {
                    switch(this.filter) {
                        case 'year':
                            return 'Tahun Ini';
                        case 'month':
                            return 'Bulan Ini';
                        case 'week':
                            return 'Minggu Ini';
                        default:
                            return '';
                    }
                },

                formatRupiah(value) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(value);
                },

                init() {
                    this.fetchChartData();
                },

                async fetchChartData() {
                    try {
                        const response = await fetch(`/chart-data?filter=${this.filter}`);
                        const result = await response.json();
                        this.totalSaleSalesSummary = result.totalSaleSalesSummary || 0;
                        
                        this.updateChart(result.labels, result.data);
                    } catch (error) {
                        console.error('Error fetching chart data:', error);
                    }
                },

                updateChart(labels, data) {
                    if (!Array.isArray(data)) {
                        console.error('Data is not an array:', data);
                        data = [];
                    }
                    
                    const ctx = document.getElementById('sales-chart').getContext('2d');
                    
                    if (this.chart) {
                        this.chart.destroy();
                    }
                    
                    this.chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Penjualan (Rp)',
                                data: data,
                                backgroundColor: '#E9D2F7',
                                borderColor: '#E9D2F7',
                                borderWidth: 0,
                                borderRadius: 4
                            }]
                        },
                        options: {
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        display: false
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                }
            }));
        })
    </script>
    <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('chartDetailComponent', () => ({
                    startDate: `${new Date().getFullYear()}-01-01`,
                    endDate: `${new Date().getFullYear()}-12-31`,
                    chart: null,
                    totalSales: 0,

                    formatDate(date) {
                        // Format tanggal menggunakan Carbon di backend, lalu parsing hasil ke sini jika memungkinkan.
                        // Untuk menggunakan Carbon di JavaScript, kita akan menggantinya dengan JavaScript native formatting
                        return new Date(date).toLocaleDateString('id-ID', {
                            day: 'numeric',
                            month: 'short',
                            year: 'numeric'
                        });
                    },

                    filterLabel() {
                        return 'Rentang Tanggal ' + this.formatDate(this.startDate) + ' - ' + this.formatDate(this.endDate);
                    },

                    formatRupiah(value) {
                        return new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0
                        }).format(value);
                    },

                    init() {
                        this.fetchChartData();
                    },

                    async fetchChartData() {
                        try {
                            const response = await fetch(`/detail-sale-summary?start=${this.startDate}&end=${this.endDate}`);
                            if (!response.ok) {
                                throw new Error(`HTTP error! Status: ${response.status}`);
                            }
                            const result = await response.json();
                            this.totalSales = result.totalSales || 0;
                            
                            this.updateChart(result.labels, result.data);
                        } catch (error) {
                            console.error('Error fetching chart data:', error);
                        }
                    },

                    updateChart(labels, data) {
                        if (!Array.isArray(data)) {
                            console.error('Data is not an array:', data);
                            data = [];
                        }
                        
                        const ctx = document.getElementById('sales-chart-detail').getContext('2d');
                        
                        if (this.chart) {
                            this.chart.destroy();
                        }
                        
                        this.chart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Penjualan (Rp)',
                                    data: data,
                                    backgroundColor: '#E9D2F7',
                                    borderColor: '#E9D2F7',
                                    borderWidth: 0,
                                    borderRadius: 4
                                }]
                            },
                            options: {
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        grid: {
                                            display: false
                                        }
                                    },
                                    x: {
                                        grid: {
                                            display: false
                                        }
                                    }
                                }
                            }
                        });
                    }
                }));
            })
    </script>
    @include('components.modal.dashboard.update-kurs')
    @include('components.modal.error-form-modal')
    @include('components.modal.error-modal')
    @include('components.modal.dashboard.success-modal')
</x-layout>
