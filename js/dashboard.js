// Dashboard Chart Implementations
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize if the required data is available
    if (typeof dashboardData === 'undefined') {
        console.error('Dashboard data is missing');
        return;
    }

    // Weekly Trend Line Chart
    const weeklyCanvas = document.getElementById('weeklyTrendChart');
    if (weeklyCanvas) {
        const weeklyCtx = weeklyCanvas.getContext('2d');
        new Chart(weeklyCtx, {
            type: 'line',
            data: {
                labels: dashboardData.weeklyLabels,
                datasets: [
                    {
                        label: 'Present',
                        data: dashboardData.weeklyPresent,
                        borderColor: '#4CAF50',
                        backgroundColor: 'rgba(76, 175, 80, 0.1)',
                        tension: 0.4
                    },
                    {
                        label: 'Late',
                        data: dashboardData.weeklyLate,
                        borderColor: '#FF9800',
                        backgroundColor: 'rgba(255, 152, 0, 0.1)',
                        tension: 0.4
                    },
                    {
                        label: 'Absent',
                        data: dashboardData.weeklyAbsent,
                        borderColor: '#f44336',
                        backgroundColor: 'rgba(244, 67, 54, 0.1)',
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    // Status Pie Chart
    const pieCanvas = document.getElementById('statusPieChart');
    if (pieCanvas) {
        const pieCtx = pieCanvas.getContext('2d');
        new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: ['Present', 'Late', 'Absent'],
                datasets: [{
                    data: dashboardData.statusData,
                    backgroundColor: ['#4CAF50', '#FF9800', '#f44336'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
});
