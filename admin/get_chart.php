<?php
require_once "database.php";

$fromDate = !empty($_GET['from_date']) ? $_GET['from_date'] : date('Y-01-01');
$toDate = !empty($_GET['to_date']) ? $_GET['to_date'] : date('Y-12-31');
$category = $_GET['category'] ?? '';
$subCat = $_GET['sub_cat'] ?? '';

$filter = "con_date BETWEEN '$fromDate' AND '$toDate'";

if (!empty($category)) {
    $filter .= " AND category = '" . $conn->real_escape_string($category) . "'";
}

if (!empty($subCat)) {
    $filter .= " AND sub_cat = '" . $conn->real_escape_string($subCat) . "'";
}

// ================== LINE CHART DATA ====================
$monthlyData = [];
$start = new DateTime($fromDate);
$end = new DateTime($toDate);
$end->modify('first day of next month');

while ($start < $end) {
    $monthKey = $start->format('Y-m');
    $monthlyData[$monthKey] = 0;
    $start->modify('+1 month');
}

$sql = "SELECT DATE_FORMAT(con_date, '%Y-%m') AS month, COUNT(*) AS total 
        FROM concerns 
        WHERE $filter 
        GROUP BY month 
        ORDER BY month ASC";

$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $monthlyData[$row['month']] = $row['total'];
}
$months = array_map(fn($k) => date("M Y", strtotime($k . "-01")), array_keys($monthlyData));
$quantities = array_values($monthlyData);

// ================== BAR CHART DATA ====================
$barLabels = [];
$barData = [];

$barQuery = "SELECT department, COUNT(*) AS total 
             FROM concerns 
             WHERE $filter 
             GROUP BY department";

$barResult = $conn->query($barQuery);
while ($row = $barResult->fetch_assoc()) {
    $barLabels[] = $row['department'];
    $barData[] = $row['total'];
}

// ================== PIE CHART DATA ====================
$pieLabels = [];
$pieData = [];

$pieQuery = "SELECT sub_cat, COUNT(*) AS total 
             FROM concerns 
             WHERE con_date BETWEEN '$fromDate' AND '$toDate'
             GROUP BY sub_cat";

$pieResult = $conn->query($pieQuery);
while ($row = $pieResult->fetch_assoc()) {
    $pieLabels[] = $row['sub_cat'];
    $pieData[] = $row['total'];
}
?>

<!-- Output Chart HTML to inject into #chart-container -->
<div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-start;">
    <!-- Line Chart -->
    <div style="width: 50%; box-sizing: border-box;">
        <canvas id="concernChart" style="width: 100%; height: 400px;"></canvas>
    </div>

    <!-- Bar Chart -->
    <div style="width: 50%; box-sizing: border-box;">
        <canvas id="departmentBarChart" style="width: 100%; height: 400px;"></canvas>
    </div>

    <!-- Pie Chart -->
    <div style="width: 100%; padding: 20px; box-sizing: border-box;">
        <canvas id="tonerPieChart" style="width: 100%; height: 500px;"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const concernCtx = document.getElementById('concernChart').getContext('2d');
    new Chart(concernCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($months) ?>,
            datasets: [{
                label: 'Concern',
                data: <?= json_encode($quantities) ?>,
                borderColor: 'rgba(32, 141, 230, 1)',
                backgroundColor: 'rgba(98, 210, 224, 0.38)',
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Concern Rate',
                    font: {
                        size: 20
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'number of concerns'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Month'
                    }
                }
            }
        }
    });

    const barCtx = document.getElementById('departmentBarChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($barLabels) ?>,
            datasets: [{
                label: 'Total Used',
                data: <?= json_encode($barData) ?>,
                backgroundColor: 'rgba(240, 127, 127, 0.38)',
                borderColor: 'rgba(240, 127, 127, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Concern per Department',
                    font: {
                        size: 18
                    }
                },
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'number of concerns'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Department'
                    }
                }
            }
        }
    });

    const pieCtx = document.getElementById('tonerPieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: <?= json_encode($pieLabels) ?>,
            datasets: [{
                data: <?= json_encode($pieData) ?>,
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                    '#9966FF', '#FF9F40', '#C9CBCF', '#76C7C0'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Sub-category Concerns',
                    font: {
                        size: 18
                    }
                },
                legend: {
                    position: 'right'
                }
            }
        }
    });
</script>