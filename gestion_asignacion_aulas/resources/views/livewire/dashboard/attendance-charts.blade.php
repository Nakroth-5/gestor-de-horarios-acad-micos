<div>
    <div class="space-y-6">
        <!-- Fila 1: Barras y Donut -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Gráfico de Barras: Top 10 Docentes -->
            <x-container-second-div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                    Top 10 Docentes por Asistencia
                </h3>
                <div class="relative h-96">
                    <canvas id="barChart"></canvas>
                </div>
            </x-container-second-div>

            <!-- Gráfico Donut: Distribución de Estados -->
            <x-container-second-div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                    Distribución de Estados
                </h3>
                <div class="relative h-96 flex items-center justify-center">
                    <canvas id="donutChart"></canvas>
                </div>
            </x-container-second-div>
        </div>

        <!-- Fila 2: Gráfico de Líneas solo -->
        <x-container-second-div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                Tendencia de Asistencia Semanal
            </h3>
            <div class="relative h-96">
                <canvas id="lineChart"></canvas>
            </div>
        </x-container-second-div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Configuración de colores para dark mode
        const isDarkMode = document.documentElement.classList.contains('dark');
        const textColor = isDarkMode ? '#e5e7eb' : '#374151';
        const gridColor = isDarkMode ? 'rgba(75, 85, 99, 0.2)' : 'rgba(229, 231, 235, 0.5)';

        // Datos desde PHP
        const topDocentes = @json($topDocentes);
        const distribucionEstados = @json($distribucionEstados);
        const tendenciaSemanal = @json($tendenciaSemanal);

        // Gráfico de Barras Horizontales con Gradientes
        const barCtx = document.getElementById('barChart').getContext('2d');

        // Crear gradientes para cada barra
        const barGradients = topDocentes.data.map((value, index) => {
            const gradient = barCtx.createLinearGradient(0, 0, 500, 0);
            if (value >= 90) {
                gradient.addColorStop(0, '#10b981');
                gradient.addColorStop(1, '#34d399');
            } else if (value >= 75) {
                gradient.addColorStop(0, '#f59e0b');
                gradient.addColorStop(1, '#fbbf24');
            } else {
                gradient.addColorStop(0, '#ef4444');
                gradient.addColorStop(1, '#f87171');
            }
            return gradient;
        });

        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: topDocentes.labels,
                datasets: [{
                    label: 'Asistencia',
                    data: topDocentes.data,
                    backgroundColor: barGradients,
                    borderWidth: 0,
                    borderRadius: 8,
                    borderSkipped: false,
                    barThickness: 28
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1500,
                    easing: 'easeInOutQuart'
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true,
                        backgroundColor: isDarkMode ? 'rgba(31, 41, 55, 0.95)' : 'rgba(255, 255, 255, 0.95)',
                        titleColor: textColor,
                        bodyColor: textColor,
                        borderColor: isDarkMode ? '#4b5563' : '#e5e7eb',
                        borderWidth: 2,
                        padding: 12,
                        displayColors: true,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.parsed.x.toFixed(1) + '% de asistencia';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            color: textColor,
                            font: {
                                size: 12,
                                weight: '500'
                            },
                            callback: function(value) {
                                return value + '%';
                            }
                        },
                        grid: {
                            color: gridColor,
                            drawBorder: false
                        }
                    },
                    y: {
                        ticks: {
                            color: textColor,
                            font: {
                                size: 12,
                                weight: '600'
                            },
                            padding: 8
                        },
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    }
                }
            }
        });

        // Gráfico Donut con Gradientes y Sombras
        const donutCtx = document.getElementById('donutChart').getContext('2d');

        // Crear gradientes para el donut
        const donutGradients = distribucionEstados.colors.map((color, index) => {
            const gradient = donutCtx.createRadialGradient(250, 250, 50, 250, 250, 200);
            if (color === '#10b981') {
                gradient.addColorStop(0, '#34d399');
                gradient.addColorStop(1, '#10b981');
            } else if (color === '#fbbf24') {
                gradient.addColorStop(0, '#fcd34d');
                gradient.addColorStop(1, '#f59e0b');
            } else {
                gradient.addColorStop(0, '#f87171');
                gradient.addColorStop(1, '#dc2626');
            }
            return gradient;
        });

        new Chart(donutCtx, {
            type: 'doughnut',
            data: {
                labels: distribucionEstados.labels,
                datasets: [{
                    data: distribucionEstados.data,
                    backgroundColor: donutGradients,
                    borderWidth: 4,
                    borderColor: isDarkMode ? '#1f2937' : '#ffffff',
                    hoverOffset: 15,
                    hoverBorderWidth: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1500,
                    easing: 'easeInOutQuart'
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: textColor,
                            padding: 20,
                            font: {
                                size: 14,
                                weight: '600'
                            },
                            usePointStyle: true,
                            pointStyle: 'circle',
                            boxWidth: 12,
                            boxHeight: 12
                        }
                    },
                    tooltip: {
                        enabled: true,
                        backgroundColor: isDarkMode ? 'rgba(31, 41, 55, 0.95)' : 'rgba(255, 255, 255, 0.95)',
                        titleColor: textColor,
                        bodyColor: textColor,
                        borderColor: isDarkMode ? '#4b5563' : '#e5e7eb',
                        borderWidth: 2,
                        padding: 16,
                        displayColors: true,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            label: function(context) {
                                const total = distribucionEstados.total;
                                const value = context.parsed;
                                const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                return ' ' + context.label + ': ' + value + ' registros (' + percentage + '%)';
                            }
                        }
                    }
                },
                cutout: '70%',
                radius: '90%'
            },
            plugins: [{
                id: 'centerText',
                beforeDraw: function(chart) {
                    const width = chart.width;
                    const height = chart.height;
                    const ctx = chart.ctx;
                    ctx.restore();

                    // Número grande
                    const fontSize = Math.min(width, height) / 6;
                    ctx.font = 'bold ' + fontSize + 'px Inter, sans-serif';
                    ctx.textBaseline = 'middle';
                    ctx.fillStyle = textColor;

                    const text = distribucionEstados.total.toString();
                    const textX = Math.round((width - ctx.measureText(text).width) / 2);
                    const textY = height / 2 - 10;

                    ctx.fillText(text, textX, textY);

                    // Texto "Total"
                    ctx.font = 'normal ' + (fontSize / 3) + 'px Inter, sans-serif';
                    ctx.fillStyle = isDarkMode ? '#9ca3af' : '#6b7280';
                    const subText = 'Total Registros';
                    const subTextX = Math.round((width - ctx.measureText(subText).width) / 2);
                    ctx.fillText(subText, subTextX, textY + fontSize / 1.5);
                    ctx.save();
                }
            }]
        });

        // Gráfico de Líneas con Gradientes y Área
        const lineCtx = document.getElementById('lineChart').getContext('2d');

        // Gradiente para Asistencias (verde)
        const gradientAsistencias = lineCtx.createLinearGradient(0, 0, 0, 400);
        gradientAsistencias.addColorStop(0, 'rgba(16, 185, 129, 0.4)');
        gradientAsistencias.addColorStop(0.5, 'rgba(16, 185, 129, 0.2)');
        gradientAsistencias.addColorStop(1, 'rgba(16, 185, 129, 0)');

        // Gradiente para Retrasos (amarillo)
        const gradientRetrasos = lineCtx.createLinearGradient(0, 0, 0, 400);
        gradientRetrasos.addColorStop(0, 'rgba(251, 191, 36, 0.4)');
        gradientRetrasos.addColorStop(0.5, 'rgba(251, 191, 36, 0.2)');
        gradientRetrasos.addColorStop(1, 'rgba(251, 191, 36, 0)');

        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: tendenciaSemanal.labels,
                datasets: [
                    {
                        label: 'Asistencias',
                        data: tendenciaSemanal.asistencias,
                        borderColor: '#10b981',
                        backgroundColor: gradientAsistencias,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 6,
                        pointHoverRadius: 10,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointHoverBackgroundColor: '#ffffff',
                        pointHoverBorderColor: '#10b981',
                        pointHoverBorderWidth: 3
                    },
                    {
                        label: 'Retrasos',
                        data: tendenciaSemanal.retrasos,
                        borderColor: '#f59e0b',
                        backgroundColor: gradientRetrasos,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 6,
                        pointHoverRadius: 10,
                        pointBackgroundColor: '#f59e0b',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointHoverBackgroundColor: '#ffffff',
                        pointHoverBorderColor: '#f59e0b',
                        pointHoverBorderWidth: 3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1500,
                    easing: 'easeInOutQuart'
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            color: textColor,
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: {
                                size: 14,
                                weight: '600'
                            }
                        }
                    },
                    tooltip: {
                        enabled: true,
                        mode: 'index',
                        intersect: false,
                        backgroundColor: isDarkMode ? 'rgba(31, 41, 55, 0.95)' : 'rgba(255, 255, 255, 0.95)',
                        titleColor: textColor,
                        bodyColor: textColor,
                        borderColor: isDarkMode ? '#4b5563' : '#e5e7eb',
                        borderWidth: 2,
                        padding: 16,
                        displayColors: true,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.dataset.label + ': ' + context.parsed.y.toFixed(1) + '%';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: textColor,
                            font: {
                                size: 12,
                                weight: '600'
                            },
                            padding: 8
                        },
                        grid: {
                            color: gridColor,
                            drawBorder: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            color: textColor,
                            font: {
                                size: 12,
                                weight: '500'
                            },
                            callback: function(value) {
                                return value + '%';
                            }
                        },
                        grid: {
                            color: gridColor,
                            drawBorder: false
                        }
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false
                }
            }
        });
    });
</script>
@endpush
