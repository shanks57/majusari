<script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('chartComponent', () => ({
                filter: 'year',
                chart: null,
                totalSaleSalesSummary: 0,

                filterLabel() {
                    switch (this.filter) {
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