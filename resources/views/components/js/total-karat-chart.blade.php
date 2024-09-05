<script>
        // Inisialisasi legend Conatiner
        const getOrCreateLegendList = (chart, id) => {
            const legendContainer = document.getElementById(id);
            let listContainer = legendContainer.querySelector('ul');

            if (!listContainer) {
                listContainer = document.createElement('ul');
                listContainer.className = "flex flex-col gap-1"
                // listContainer.style.display = 'flex';
                // listContainer.style.flexDirection = 'column';
                // listContainer.style.margin = 0;
                // listContainer.style.padding = 0;

                legendContainer.appendChild(listContainer);
            }

            return listContainer;
        };

        const htmlLegendPlugin = {
            id: 'htmlLegend',
            afterUpdate(chart, args, options) {
                const ul = getOrCreateLegendList(chart, options.containerID);

                // Remove old legend items
                while (ul.firstChild) {
                    ul.firstChild.remove();
                }
                console.log("charts", chart, args, options)

                // Reuse the built-in legendItems generator
                const items = chart.options.plugins.legend.labels.generateLabels(chart);

                items.forEach((item, i) => {
                    const li = document.createElement('li');
                    li.style.alignItems = 'center';
                    li.style.cursor = 'pointer';
                    li.style.display = 'flex';
                    li.style.flexDirection = 'row';
                    li.style.marginLeft = '10px';
                    li.style.justifyContent = "justify-between"

                    li.onclick = () => {
                        const {
                            type
                        } = chart.config;
                        if (type === 'pie' || type === 'doughnut') {
                            // Pie and doughnut charts only have a single dataset and visibility is per item
                            chart.toggleDataVisibility(item.index);
                        } else {
                            chart.setDatasetVisibility(item.datasetIndex, !chart.isDatasetVisible(item.datasetIndex));
                        }
                        chart.update();
                    };

                    // Color box
                    const boxSpan = document.createElement('span');
                    boxSpan.style.background = item.fillStyle;
                    boxSpan.style.borderColor = item.strokeStyle;
                    boxSpan.style.borderWidth = item.lineWidth + 'px';
                    boxSpan.style.display = 'inline-block';
                    boxSpan.style.flexShrink = 0;
                    boxSpan.style.height = '20px';
                    boxSpan.style.marginRight = '10px';
                    boxSpan.style.width = '20px';
                    boxSpan.className = "rounded-full"

                    // Text
                    const textContainer = document.createElement('div');
                    textContainer.style.color = item.fontColor;
                    textContainer.style.margin = 0;
                    textContainer.style.padding = 0;
                    textContainer.style.textDecoration = item.hidden ? 'line-through' : '';
                    textContainer.className = "flex justify-between items-center w-full"

                    const text = document.createTextNode(item.text);

                    const gramValue = document.createTextNode(`${chart.data.datasets[0].data[i]}gr`)
                    const spanText = document.createElement('span');
                    const spanValue = document.createElement('span');

                    spanText.appendChild(text)
                    spanValue.appendChild(gramValue)

                    textContainer.appendChild(spanText);
                    textContainer.appendChild(spanValue);


                    li.appendChild(boxSpan);
                    li.appendChild(textContainer);

                    ul.appendChild(li);
                });
            }
        };
        // end inisialisasi legend container

        // Function to generate a random color
        function getRandomColor() {
            const letters = '0123456789ABCDEF';
            let color = '#';
            for (let i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        // start get-total-weight-by-type
        fetch('/get-total-weight-by-type')
            .then(response => response.json())
            .then(data => {
                const labels = data.map(item => item.type_name);
                const weights = data.map(item => {
                    const weight = parseFloat(item.total_weight);
                    return weight % 1 === 0 ? weight.toString() : weight.toFixed(3);
                });

                const ctx = document.getElementById('karatageTotalWeightChart').getContext('2d');
                const totalWeightChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Total Berat Barang',
                            data: weights,
                            backgroundColor: [
                                '#10B981', 
                                '#3B82F6', 
                                '#6366F1', 
                                '#8B5CF6', 
                                '#3730A3', 
                                '#F59E0B', 
                                '#14B8A6' 
                            ],
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            htmlLegend: {
                                containerID: 'legend-container',
                            },
                            legend: {
                                display: false,
                            }
                        }
                    },
                    plugins: [htmlLegendPlugin],
                });
        });
        // end get-total-weight-by-type

        // start get-weight-by-brand
        fetch('/get-weight-by-brand')
            .then(response => response.json())
            .then(data => {
                const labels = data.map(item => item.brand_name);
                const weights = data.map(item => {
                    const weight = parseFloat(item.total_weight);
                    return weight % 1 === 0 ? weight.toString() : weight.toFixed(3);
                });
                const backgroundColors = data.map(() => getRandomColor()); // Generate random colors

                const ctx = document.getElementById('brandWeightChart').getContext('2d');
                const brandWeightChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Total Berat Barang per Merek',
                            data: weights,
                            backgroundColor: backgroundColors,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            htmlLegend: {
                                containerID: 'legend-container-dua',
                            },
                            legend: {
                                display: false,
                            }
                        }
                    },
                    plugins: [htmlLegendPlugin],
                });
        })
        .catch(error => console.error('Error fetching data:', error));
        // end get-weight-by-brand

        // Start get-weight-by-gold-rate
        fetch('/get-weight-by-gold-rate')
            .then(response => response.json())
            .then(data => {
                const labels = data.map(item => `${item.gold_rate} (${item.type_name})`);
                const weights = data.map(item => {
                    const weight = parseFloat(item.total_weight);
                    return weight % 1 === 0 ? weight.toString() : weight.toFixed(3);
                });
                const backgroundColors = data.map(() => getRandomColor()); // Generate random colors

                const ctx = document.getElementById('goldRateChart').getContext('2d');
                const goldRateChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Total Berat Barang per Kadar Emas',
                            data: weights,
                            backgroundColor: backgroundColors,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            htmlLegend: {
                                containerID: 'legend-container-tiga',
                            },
                            legend: {
                                display: false,
                            }
                        }
                    },
                    plugins: [htmlLegendPlugin],
                });
        })
        .catch(error => console.error('Error fetching data:', error));
        // End get-weight-by-gold-rate

</script>