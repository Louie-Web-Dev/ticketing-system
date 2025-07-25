<?php
session_start();
if (!isset($_SESSION["user"]) || $_SESSION["pos"] !== "admin") {
    header("Location: /TSP-System/ticketing-system/");
    exit();
}

require_once "database.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toyota</title>
    <?php include 'nav.php'; ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
    <div class="dashboardContainer">
        <div class="displayCount bg-transparent">
            <h1>DISPLAY COUNT</h1>

            <div id="dashboardContent"></div>
            <script>
                function updateDashboard() {
                    $.ajax({
                        url: 'get_count.php',
                        type: 'GET',
                        success: function(data) {

                            $('#dashboardContent').html(data);
                        },
                        error: function(error) {
                            console.error('Error fetching data:', error);
                        }
                    });
                }
                setInterval(updateDashboard, 1000);
                updateDashboard();
            </script>

        </div>

        <hr style="margin:10px">

        <div class="first-section">

            <div class="overview-section">
                <form method="GET" class="filter-form">
                    <label for="year">Year:</label>
                    <select name="year" id="year">
                        <option value="">All</option>
                        <?php
                        $currentYear = date("Y");
                        for ($y = $currentYear; $y >= 2018; $y--) {
                            $selected = (
                                (isset($_GET['year']) && $_GET['year'] == $y) ||
                                (!isset($_GET['year']) && $y == $currentYear)
                            ) ? 'selected' : '';
                            echo "<option value='$y' $selected>$y</option>";
                        }
                        ?>
                    </select>


                    <label for="category">Category:</label>
                    <select name="category" id="category">
                        <option value="">All</option>
                        <option value="PC Software" <?= (isset($_GET['category']) && $_GET['category'] == 'PC Software') ? 'selected' : '' ?>>PC Software</option>
                        <option value="PC Hardware" <?= (isset($_GET['category']) && $_GET['category'] == 'PC Hardware') ? 'selected' : '' ?>>PC Hardware</option>
                        <option value="Internet Connection" <?= (isset($_GET['category']) && $_GET['category'] == 'Internet Connection') ? 'selected' : '' ?>>Internet Connection</option>
                        <option value="Printer" <?= (isset($_GET['category']) && $_GET['category'] == 'Printer') ? 'selected' : '' ?>>Printer</option>
                        <option value="SAP" <?= (isset($_GET['category']) && $_GET['category'] == 'SAP') ? 'selected' : '' ?>>SAP</option>
                        <option value="Others" <?= (isset($_GET['category']) && $_GET['category'] == 'Others') ? 'selected' : '' ?>>Others</option>
                    </select>


                    <label for="sub_cat">Sub-category:</label>
                    <select name="sub_cat" id="sub_cat">
                        <option value="">All</option>
                    </select>


                    <button type="submit">Filter</button>
                </form>

                <div id="result-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Pending</th>
                                <th>Solved</th>
                            </tr>
                        </thead>
                        <tbody>


                        </tbody>

                    </table>

                </div>

                <script>
                    document.querySelector(".filter-form").addEventListener("submit", function(e) {
                        e.preventDefault();

                        const form = e.target;
                        const formData = new FormData(form);
                        const queryString = new URLSearchParams(formData).toString();

                        fetch("get_table.php?" + queryString)
                            .then(response => response.text())
                            .then(html => {
                                document.getElementById("result-table").innerHTML = html;
                            })
                            .catch(error => {
                                console.error("AJAX Error:", error);
                            });
                    });

                    document.addEventListener("DOMContentLoaded", () => {
                        document.querySelector(".filter-form").dispatchEvent(new Event("submit"));
                    });
                </script>


                <script>
                    const subCategories = {
                        "PC Software": ["System", "Operating System", "MS Office", "Shared Folders"],
                        "PC Hardware": ["Mouse", "Monitor", "Keyboard", "UPS", "Hard Drive", "Flash Drive", "PC Format"],
                        "Internet Connection": ["Wifi", "LAN"],
                        "Printer": ["Print", "Photocopy", "Scan"],
                        "SAP": ["Lock/Unlock of Account", "Change Password", "Addition of access roles", "Others"],
                        "Others": ["TV Con", "Assistance on projector setup", "Recolation of PC", "Others"]
                    };

                    function updateSubCategories() {
                        const categorySelect = document.getElementById("category");
                        const subCatSelect = document.getElementById("sub_cat");
                        const selectedSubCat = "<?php echo $_GET['sub_cat'] ?? ''; ?>";

                        const selectedCategory = categorySelect.value;
                        const options = subCategories[selectedCategory] || [];

                        subCatSelect.innerHTML = '<option value="">All</option>';

                        options.forEach(sub => {
                            const option = document.createElement("option");
                            option.value = sub;
                            option.textContent = sub;
                            if (sub === selectedSubCat) {
                                option.selected = true;
                            }
                            subCatSelect.appendChild(option);
                        });
                    }

                    document.addEventListener("DOMContentLoaded", () => {
                        updateSubCategories();

                        const urlParams = new URLSearchParams(window.location.search);
                        const hasFilter = urlParams.has("year") || urlParams.has("category") || urlParams.has("sub_cat");

                        if (!hasFilter) {
                            document.querySelector(".filter-form").dispatchEvent(new Event("submit"));
                        }

                        document.getElementById("category").addEventListener("change", updateSubCategories);
                    });
                </script>

            </div>


            <div class="ranking-section">
                <h1><i class="fa-solid fa-ranking-star"></i>Leaderboards</h1>
                <table>
                    <thead>
                        <tr>
                            <th>Person In-Charge (PIC)</th>
                            <th>Number of Concerns Solved</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require_once "database.php";

                        $sql = "SELECT pic, COUNT(*) AS concern_count 
                            FROM concerns 
                            WHERE pic IS NOT NULL AND TRIM(pic) != '' 
                            GROUP BY pic 
                            ORDER BY concern_count DESC 
                            LIMIT 10";

                        $result = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($result) > 0):
                            while ($row = mysqli_fetch_assoc($result)):
                        ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['pic']); ?></td>
                                    <td><?php echo htmlspecialchars($row['concern_count']); ?></td>
                                </tr>
                            <?php endwhile;
                        else: ?>
                            <tr>
                                <td colspan="10">No records found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>

        <hr style="margin-top: 20px; margin-bottom: 50px;">


        <form method="GET" class="filter-form2">
            <label for="from_date">From: </label>
            <input type="date" name="from_date" value="<?php echo htmlspecialchars($_GET['from_date'] ?? date('Y-01-01')); ?>">
            <input type="date" name="to_date" value="<?php echo htmlspecialchars($_GET['to_date'] ?? date('Y-12-31')); ?>">
            <label for="category">Category:</label>
            <select name="category" id="category">
                <option value="">All</option>
                <option value="PC Software" <?= (isset($_GET['category']) && $_GET['category'] == 'PC Software') ? 'selected' : '' ?>>PC Software</option>
                <option value="PC Hardware" <?= (isset($_GET['category']) && $_GET['category'] == 'PC Hardware') ? 'selected' : '' ?>>PC Hardware</option>
                <option value="Internet Connection" <?= (isset($_GET['category']) && $_GET['category'] == 'Internet Connection') ? 'selected' : '' ?>>Internet Connection</option>
                <option value="Printer" <?= (isset($_GET['category']) && $_GET['category'] == 'Printer') ? 'selected' : '' ?>>Printer</option>
                <option value="SAP" <?= (isset($_GET['category']) && $_GET['category'] == 'SAP') ? 'selected' : '' ?>>SAP</option>
                <option value="Others" <?= (isset($_GET['category']) && $_GET['category'] == 'Others') ? 'selected' : '' ?>>Others</option>
            </select>


            <label for="sub_cat">Sub-category:</label>
            <select name="sub_cat" id="sub_cat">
                <option value="">All</option>
            </select>

            <button type="submit" class="btn-filter">Filter</button>

            <a href="dashboard.php" class="btn-reset">Reset</a>

        </form>

        <script>
            const subCategories2 = {
                "PC Software": ["System", "Operating System", "MS Office", "Shared Folders"],
                "PC Hardware": ["Mouse", "Monitor", "Keyboard", "UPS", "Hard Drive", "Flash Drive", "PC Format"],
                "Internet Connection": ["Wifi", "LAN"],
                "Printer": ["Print", "Photocopy", "Scan"],
                "SAP": ["Lock/Unlock of Account", "Change Password", "Addition of access roles", "Others"],
                "Others": ["TV Con", "Assistance on projector setup", "Recolation of PC", "Others"]
            };

            function updateSubCategories2() {
                const categorySelect = document.querySelector(".filter-form2 #category");
                const subCatSelect = document.querySelector(".filter-form2 #sub_cat");
                const selectedSubCat = "<?php echo $_GET['sub_cat'] ?? ''; ?>";

                const selectedCategory = categorySelect.value;
                const options = subCategories2[selectedCategory] || [];

                subCatSelect.innerHTML = '<option value="">All</option>';

                options.forEach(sub => {
                    const option = document.createElement("option");
                    option.value = sub;
                    option.textContent = sub;
                    if (sub === selectedSubCat) {
                        option.selected = true;
                    }
                    subCatSelect.appendChild(option);
                });
            }

            document.addEventListener("DOMContentLoaded", () => {
                updateSubCategories2();

                const categorySelect = document.querySelector(".filter-form2 #category");
                categorySelect.addEventListener("change", updateSubCategories2);
            });
        </script>


        <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-start;">

            <?php
            require_once "database.php";


            $fromDate = !empty($_GET['from_date']) ? $_GET['from_date'] : date('Y-01-01');
            $toDate = !empty($_GET['to_date']) ? $_GET['to_date'] : date('Y-12-31');
            $category = $_GET['category'] ?? '';
            $subCat = $_GET['sub_cat'] ?? '';


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

            $allMonths = [];
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

            $months = [];
            foreach (array_keys($monthlyData) as $monthKey) {
                $months[] = date("M Y", strtotime($monthKey . "-01"));
            }
            $quantities = array_values($monthlyData);

            $barQuery = "SELECT department, COUNT(*) AS total_used 
                            FROM concerns
                            WHERE $whereClause
                            GROUP BY department 
                            ORDER BY department";

            $barResult = $conn->query($barQuery);
            $departments = [];
            $usedQuantities = [];

            while ($row = $barResult->fetch_assoc()) {
                $departments[] = $row['department'];
                $usedQuantities[] = $row['total_used'];
            }


            $pieQuery = "SELECT sub_cat, COUNT(*) AS total_quantity 
                            FROM concerns
                            WHERE $whereClause
                            GROUP BY sub_cat";

            $pieResult = $conn->query($pieQuery);
            $tonerLabels = [];
            $tonerQuantities = [];

            while ($row = $pieResult->fetch_assoc()) {
                $tonerLabels[] = $row['sub_cat'];
                $tonerQuantities[] = $row['total_quantity'];
            }
            ?>




            <div style="width: 50%; box-sizing: border-box;">
                <div style="padding: 20px;">
                    <canvas id="concernChart" style="width: 100%; height: 400px;"></canvas>
                </div>

                <script>
                    const ctx = document.getElementById('concernChart').getContext('2d');

                    const tonerChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: <?php echo json_encode($months); ?>,
                            datasets: [{
                                label: 'Concern',
                                data: <?php echo json_encode($quantities); ?>,
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
                </script>



                <!--bar graph -->

                <?php
                $barQuery = "SELECT department, COUNT(*) AS total_used 
                FROM concerns
                WHERE con_date BETWEEN '$fromDate' AND '$toDate'
                GROUP BY department 
                ORDER BY department";


                $barResult = $conn->query($barQuery);

                $departments = [];
                $usedQuantities = [];

                while ($row = $barResult->fetch_assoc()) {
                    $departments[] = $row['department'];
                    $usedQuantities[] = $row['total_used'];
                }
                ?>

                <div style="padding: 20px; box-sizing: border-box;">
                    <canvas id="departmentBarChart" style="width: 800px; height: 400px;"></canvas>
                </div>



                <script>
                    const barCtx = document.getElementById('departmentBarChart').getContext('2d');

                    const departmentBarChart = new Chart(barCtx, {
                        type: 'bar',
                        data: {
                            labels: <?php echo json_encode($departments); ?>,
                            datasets: [{
                                label: 'Total Used',
                                data: <?php echo json_encode($usedQuantities); ?>,
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
                </script>
            </div>


            <!-- pie chart -->

            <?php
            $categoryFilterOnly = "category != ''";
            if (!empty($category)) {
                $categoryFilterOnly .= " AND category = '" . $conn->real_escape_string($category) . "'";
            }
            if (!empty($fromDate) && !empty($toDate)) {
                $categoryFilterOnly .= " AND con_date BETWEEN '$fromDate' AND '$toDate'";
            }

            $pieQuery = "SELECT sub_cat FROM concerns WHERE $categoryFilterOnly";

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

            $tonerLabels = array_keys($subCatCounts);
            $tonerQuantities = array_values($subCatCounts);
            ?>


            <div style="width: 50%; padding: 20px; box-sizing: border-box;">
                <canvas id="tonerPieChart" style="width: 100%; height: 100px;"></canvas>
            </div>
            <?php
            $categoryTitle = $category ? " for $category" : "";
            ?>

            <script>
                const pieCtx = document.getElementById('tonerPieChart').getContext('2d');

                const tonerPieChart = new Chart(pieCtx, {
                    type: 'pie',
                    data: {
                        labels: <?php echo json_encode($tonerLabels); ?>,
                        datasets: [{
                            data: <?php echo json_encode($tonerQuantities); ?>,
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
        </div>

    </div>




    <style>
        body {
            background-color: rgb(221, 221, 221);

        }

        .dashboardContainer {
            display: flex;
            flex-direction: column;
            background-color: white;
            width: 85.5%;
            height: 91%;
            position: fixed;
            right: 10px;
            margin-top: 83px;
            border: 1px black solid;
            border-radius: 15px;
            padding-bottom: 20px;

            overflow-y: auto;
            /* Enable vertical scroll */
            overflow-x: hidden;
        }

        .displayCount h1 {
            color: black;
            font-family: 'Mulish', sans-serif;
            font-size: 25px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 40px;
        }

        .numbersContainer {
            background-color: #e7e7e7;
            width: 70%;
            height: fit-content;
            margin: 10px auto;
            text-align: center;
            font-family: 'Mulish', sans-serif;
            border-radius: 15px;
            font-weight: bold;
            display: grid;
            grid-template-columns: repeat(2, auto);
            z-index: 1;
        }

        .numbersContainer label {
            background-color: transparent;
            color: black;
            margin: 10px 5%;
        }

        .numbersContainer span {
            background-color: #e7e7e7;
            margin-top: -30px;
            color: black;
            font-size: 70px;
        }



        /* <---first-section--> */
        .first-section {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            gap: 20px;
        }

        .overview-section {
            width: 70%;
            overflow-x: auto;
        }

        .overview-section table {
            width: 100%;
            border-collapse: collapse;
            border-left: none;
            border-right: none;
        }

        .overview-section th,
        .overview-section td {
            padding: 10px;
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            border-left: none;
            border-right: none;
            text-align: left;
        }

        .overview-section th {
            background-color: #9cc5faff;
            color: black;
        }

        .overview-section tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .overview-section tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        /* <!-- <---ranking-section--> --> */

        .ranking-section {
            width: 30%;
            overflow-x: auto;
        }

        .ranking-section h1 {
            text-align: center;
            color: black;
            font-family: 'Mulish', sans-serif;
            font-size: 25px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .ranking-section table {
            width: 100%;
            border-collapse: collapse;
        }

        .ranking-section th,
        .ranking-section td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }

        .ranking-section th {
            background-color: #9cc5faff;
            color: black;
        }

        .filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px 20px;
            margin-bottom: 20px;
            align-items: center;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .filter-form label {
            font-weight: bold;
            margin-right: 5px;
        }

        .filter-form select,
        .filter-form button {
            padding: 6px 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            min-width: 150px;
            font-size: 14px;
        }

        .filter-form button {
            background-color: #418beb;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .filter-form button:hover {
            background-color: #306bb8;
        }



        .filter-form2 {
            display: flex;
            flex-wrap: wrap;
            gap: 10px 20px;
            margin-bottom: 20px;
            align-items: center;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-left: 30px;
            margin-right: 30px;
        }

        .filter-form2 label {
            font-weight: bold;
            margin-right: 5px;
        }

        .filter-form2 select,
        .filter-form2 input,
        .filter-form2 button {
            padding: 6px 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            min-width: 150px;
            font-size: 14px;
        }

        .filter-form2 button {
            background-color: #418beb;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .filter-form2 button:hover {
            background-color: #306bb8;
        }
    </style>



</body>

</html>