<?php
require_once "database.php";

$year = $_GET['year'] ?? '';
$category = $_GET['category'] ?? '';
$sub_cat = $_GET['sub_cat'] ?? '';

$whereConditions = [];

if (!empty($year)) {
    $whereConditions[] = "YEAR(con_date) = " . intval($year);
}
if (!empty($category)) {
    $whereConditions[] = "category = '" . mysqli_real_escape_string($conn, $category) . "'";
}
if (!empty($sub_cat)) {
    $whereConditions[] = "sub_cat = '" . mysqli_real_escape_string($conn, $sub_cat) . "'";
}

$sql = "SELECT 
    MONTH(con_date) AS month_num,
    MONTHNAME(con_date) AS month_name,
    SUM(pending_count) AS pending_count,
    SUM(on_hold_count) AS on_hold_count,
    SUM(solved_count) AS solved_count
FROM (
    SELECT con_date, category, sub_cat, 
        1 AS pending_count, 0 AS on_hold_count, 0 AS solved_count
    FROM concerns
    WHERE status = 'pending'" .
    (!empty($whereConditions) ? ' AND ' . implode(' AND ', $whereConditions) : '') . "
    UNION ALL
    SELECT con_date, category, sub_cat, 
        0 AS pending_count, 1 AS on_hold_count, 0 AS solved_count
    FROM concerns
    WHERE status = 'on-hold'" .
    (!empty($whereConditions) ? ' AND ' . implode(' AND ', $whereConditions) : '') . "
    UNION ALL
    SELECT con_date, category, sub_cat, 
        0 AS pending_count, 0 AS on_hold_count, 1 AS solved_count
    FROM concerns
    WHERE status = 'done'" .
    (!empty($whereConditions) ? ' AND ' . implode(' AND ', $whereConditions) : '') . "
) AS sub
GROUP BY MONTH(con_date), MONTHNAME(con_date)
ORDER BY month_num ASC";


$result = mysqli_query($conn, $sql);

$months = [
    1 => "January",
    2 => "February",
    3 => "March",
    4 => "April",
    5 => "May",
    6 => "June",
    7 => "July",
    8 => "August",
    9 => "September",
    10 => "October",
    11 => "November",
    12 => "December"
];

$data = array_fill(1, 12, ['pending' => 0, 'on_hold' => 0, 'solved' => 0]);

while ($row = mysqli_fetch_assoc($result)) {
    $monthNum = (int)$row['month_num'];
    $data[$monthNum] = [
        'pending' => (int)$row['pending_count'],
        'on_hold' => (int)$row['on_hold_count'],
        'solved' => (int)$row['solved_count']
    ];
}

$total_pending = 0;
$total_on_hold = 0;
$total_solved = 0;

echo '<table><thead><tr><th>Month</th><th>Pending</th><th>On-Hold</th><th>Solved</th></tr></thead><tbody>';
foreach ($months as $num => $name) {
    $pending = $data[$num]['pending'];
    $on_hold = $data[$num]['on_hold'];
    $solved = $data[$num]['solved'];

    $total_pending += $pending;
    $total_on_hold += $on_hold;
    $total_solved += $solved;

    echo "<tr><td>" . htmlspecialchars($name) . "</td><td>$pending</td><td>$on_hold</td><td>$solved</td></tr>";
}
echo "<tr style='font-weight:bold'><td>Total</td><td>$total_pending</td><td>$total_on_hold</td><td>$total_solved</td></tr>";
echo '</tbody></table>';
