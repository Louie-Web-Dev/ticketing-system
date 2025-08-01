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
    <title>Toyota IT Ticketing System</title>
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
            <h1>CONCERN COUNT</h1>

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
                                <th>On-Hold</th>
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

                        $sql = "SELECT c.pic, COUNT(*) AS concern_count
                        FROM concerns c
                        JOIN user u ON c.pic = u.name
                        WHERE c.pic IS NOT NULL 
                        AND TRIM(c.pic) != '' 
                        AND u.pos = 'admin'
                        GROUP BY c.pic 
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

        <div class="second-section">
            <form id="filterForm" class="filter-form2">

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

                <button type="button" class="btn-filter" id="filterBtn">Filter</button>
                <button type="button" class="btn-clear" id="clearBtn">Clear</button>


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

                    document.getElementById("filterBtn").addEventListener("click", function() {
                        const form = document.getElementById("filterForm");
                        const formData = new FormData(form);
                        const query = new URLSearchParams(formData).toString();

                        fetch("get_chart_data.php?" + query)
                            .then(response => response.text())
                            .then(html => {
                                const container = document.getElementById("chart-container");
                                container.innerHTML = html;

                                const scripts = container.querySelectorAll("script");
                                scripts.forEach(script => {
                                    const newScript = document.createElement("script");
                                    newScript.textContent = script.textContent;
                                    document.body.appendChild(newScript);
                                    document.body.removeChild(newScript);
                                });
                            })
                            .catch(error => {
                                console.error("Error loading chart data:", error);
                            });
                    });
                    document.getElementById("clearBtn").addEventListener("click", function() {
                        const form = document.getElementById("filterForm");

                        // Reset values to default
                        form.from_date.value = new Date(new Date().getFullYear(), 0, 1).toISOString().split("T")[0]; // Jan 1st
                        form.to_date.value = new Date(new Date().getFullYear(), 11, 31).toISOString().split("T")[0]; // Dec 31st
                        form.category.value = '';
                        updateSubCategories2(); // reset sub_cat options
                        form.sub_cat.value = '';

                        // Trigger chart refresh with default values
                        document.getElementById("filterBtn").click();
                    });



                });
                window.addEventListener("load", () => {
                    document.getElementById("filterBtn").click();
                });
            </script>


            <div id="chart-container">
                <!-- Chart content will be loaded here -->
            </div>

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
            grid-template-columns: repeat(3, auto);
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

        .filter-form2 .btn-clear {
            background-color: #e0e0e0;
            color: #333;
            border: none;
            padding: 6px 10px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .filter-form2 .btn-clear:hover {
            background-color: #c5c5c5;
        }

        @media screen and (max-width: 1555px) and (min-width: 320px) {
            .dashboardContainer {
                width: 98%;
            }
        }

        @media screen and (max-width: 1950px) and (min-width: 1610px) {
            .dashboardContainer {
                min-width: 85.4%;
            }

            /* .fb-page {
                min-width: 100%;
            }

            .fbPlugins h1 {
                width: 93.7%;
            } */
        }

        /* @media print {
            body {
                text-align: center;
            }

            table {
                text-align: center;
            }
        } */
    </style>



</body>

</html>