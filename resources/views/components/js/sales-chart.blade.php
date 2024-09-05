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