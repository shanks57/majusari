<script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('chartWeightComponent', () => ({
                filter: 'year',
                chart: '',
                totalGoodsIn: '',
                totalGoodsOut: '',
                goodsInData: [],
                goodsOutData: [],
                labels: [],

                init() {
                    this.fetchChartData();
                },

                async fetchChartData() {
                    const response = await fetch(`/get-weight-chart-data?filter=${this.filter}`);
                    const data = await response.json();
                    this.goodsInData = data.goodsInData;
                    this.goodsOutData = data.goodsOutData;
                    this.labels = data.labels;
                    this.totalGoodsIn = data.totalGoodsIn;
                    this.totalGoodsOut = data.totalGoodsOut;
                    this.updateChart();
                },
                updateChart() {
                    const ctx = document.getElementById('weight-chart').getContext('2d');

                    if (this.chart) {
                        this.chart.destroy();
                    }

                    this.chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: this.labels,
                            datasets: [{
                                    label: 'Total Barang Masuk',
                                    data: this.goodsInData,
                                    backgroundColor: 'rgba(173, 216, 230, 0.6)', // lightblue
                                    borderRadius: 4
                                },
                                {
                                    label: 'Total Barang Keluar',
                                    data: this.goodsOutData,
                                    backgroundColor: 'rgba(70, 130, 180, 0.6)', // steelblue
                                    borderRadius: 4
                                }
                            ]
                        },
                        options: {
                            plugins: {
                                legend: {
                                    display: false
                                },
                            },
                            scales: {
                                x: {
                                    grid: {
                                        display: false
                                    }
                                },
                                y: {
                                    grid: {
                                        display: false
                                    },
                                    beginAtZero: true
                                }
                            },
                            elements: {
                                bar: {
                                    borderRadius: 4
                                }
                            }
                        }
                    });
                }
            }));
        });
    </script>