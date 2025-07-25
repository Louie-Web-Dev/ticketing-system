<?php
require_once "database.php";

$fromDate = $_GET['from_date'] ?? date('Y-01-01');
$toDate = $_GET['to_date'] ?? date('Y-12-31');
$category = $_GET['category'] ?? '';
$subCat = $_GET['sub_cat'] ?? '';

// LINE CHART
$conditions = ["con_date BETWEEN '$fromDate' AND '$toDate'"];
if (!empty($category)) {
    $safeCategory = $conn->real_escape_string($category);
    $conditions[] = "category = '$safeCategory'";
}
if (!empty($subCat)) {
    $safeSubCat = $conn->real_escape_string($subCat);
    $conditions[] = "sub_cat = '$safeSubCat'";
}
$whereClause = implode(' AND ', $conditions);

$monthlyData = [];
$start = new DateTime($fromDate);
$end = new DateTime($toDate);
$end->modify('first day of next month');
while ($start < $end) {
    $monthKey = $start->format('Y-m');
    $monthlyData[$monthKey] = 0;
    $start->modify('+1 month');
}

$sql = "SELECT DATE_FORMAT(con_date, '%Y-%m') AS month, COUNT(*) AS total_quantity 
        FROM concerns
        WHERE $whereClause
        GROUP BY month 
        ORDER BY month ASC";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $monthlyData[$row['month']] = $row['total_quantity'];
}
$lineLabels = [];
$lineCounts = [];
foreach ($monthlyData as $key => $val) {
    $lineLabels[] = date("M Y", strtotime($key . "-01"));
    $lineCounts[] = $val;
}

// BAR CHART
$barQuery = "SELECT department, COUNT(*) AS total_used 
             FROM concerns
             WHERE $whereClause
             GROUP BY department 
             ORDER BY department";
$barResult = $conn->query($barQuery);
$barLabels = [];
$barCounts = [];
while ($row = $barResult->fetch_assoc()) {
    $barLabels[] = $row['department'];
    $barCounts[] = $row['total_used'];
}

// PIE CHART
$pieFilter = "category != ''";
if (!empty($category)) {
    $pieFilter .= " AND category = '" . $conn->real_escape_string($category) . "'";
}
if (!empty($fromDate) && !empty($toDate)) {
    $pieFilter .= " AND con_date BETWEEN '$fromDate' AND '$toDate'";
}
$pieQuery = "SELECT sub_cat FROM concerns WHERE $pieFilter";
$pieResult = $conn->query($pieQuery);
$subCatCounts = [];
while ($row = $pieResult->fetch_assoc()) {
    $subCatList = explode(',', $row['sub_cat']);
    foreach ($subCatList as $item) {
        $cleanItem = trim($item);
        if (!empty($cleanItem)) {
            if (!isset($subCatCounts[$cleanItem])) {
                $subCatCounts[$cleanItem] = 0;
            }
            $subCatCounts[$cleanItem]++;
        }
    }
}
$pieLabels = array_keys($subCatCounts);
$pieData = array_values($subCatCounts);
$categoryTitle = $category ? " for $category" : "";
?>

<!-- Chart Containers -->
<div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-start;">
    <!-- Left Side: Line & Bar Charts -->
    <div style="width: 50%; box-sizing: border-box;">
        <div style="padding: 20px;">
            <canvas id="lineChart" style="width: 100%; height: 400px;"></canvas>
        </div>
        <div style="padding: 20px;">
            <canvas id="barChart" style="width: 100%; height: 400px;"></canvas>
        </div>
    </div>

    <!-- Right Side: Pie Chart -->
    <div style="width: 50%; padding: 20px; box-sizing: border-box;">
        <canvas id="pieChart" style="width: 100%; height: 250px;"></canvas>
    </div>
</div>


<!-- Chart.js Script -->
<script>
    // LINE CHART
    new Chart(document.getElementById('lineChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: <?php echo json_encode($lineLabels); ?>,
            datasets: [{
                label: 'Concerns Over Time',
                data: <?php echo json_encode($lineCounts); ?>,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(91, 171, 224, 0.2)',
                borderWidth: 2,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Line Chart<?php echo $categoryTitle; ?>',
                    font: {
                        size: 18
                    }
                }
            }
        }
    });

    // BAR CHART
    new Chart(document.getElementById('barChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($barLabels); ?>,
            datasets: [{
                label: 'Concerns per Department',
                data: <?php echo json_encode($barCounts); ?>,
                backgroundColor: 'rgba(255, 159, 64, 0.7)',
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Bar Chart<?php echo $categoryTitle; ?>',
                    font: {
                        size: 18
                    }
                },
                legend: {
                    display: false
                }
            }
        }
    });

    // PIE CHART
    new Chart(document.getElementById('pieChart').getContext('2d'), {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($pieLabels); ?>,
            datasets: [{
                data: <?php echo json_encode($pieData); ?>,
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                    '#9966FF', '#FF9F40', '#C9CBCF', '#76C7C0',
                    '#009688', '#E91E63', '#3F51B5', '#CDDC39'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Sub-category Breakdown<?php echo $categoryTitle; ?>',
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