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

    <div class="pendingContainer">
        <div class="displaypending bg-transparent">
            <h1>History Logs</h1>
        </div>

        <div style="display: flex; justify-content: right; align-items: center; margin-bottom: 10px; margin-right: 15px; padding: 0 10px;">


            <div>
                Search:
                <input type="text" id="searchInput"
                    placeholder="Search..."
                    value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
                    onkeydown="if(event.key === 'Enter') searchNow();"
                    style="padding: 4px 8px; margin-left: 5px; border: 1px solid #ccc;">

                <button onclick="searchNow()"
                    style="background-color: #418bebff; color: white; border: none; padding: 6px 10px; border-radius: 4px; font-size: 14px; cursor: pointer; margin-left: 5px;">
                    Search
                </button>

                <button onclick="clearSearch()"
                    style="background-color: #e0e0e0; color: #333; border: none; padding: 6px 10px; border-radius: 4px; font-size: 14px; cursor: pointer; margin-left: 5px;">
                    Clear
                </button>
            </div>



        </div>

        <div class="table-section">
            <table>
                <thead>
                    <tr>
                        <th>TICKET NO</th>
                        <th>NAME</th>
                        <th>DEPARTMENT</th>
                        <th>DATE FIELD</th>
                        <th>CATEGORY</th>
                        <th>SUB-CATEGORY</th>
                        <th>DESCRIPTION</th>
                        <th>DATE DONE</th>
                        <th>PIC</th>
                        <th>STATUS</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    require_once "database.php";

                    $entriesPerPage = isset($_GET['limit']) ? (int)$_GET['limit'] : 16;
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $startFrom = ($page - 1) * $entriesPerPage;

                    // Count total records
                    $searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

                    $where = "WHERE status = 'done'";
                    if (!empty($searchTerm)) {
                        $where .= " AND (
                            ticket_no LIKE '%$searchTerm%' OR
                            name LIKE '%$searchTerm%' OR
                            department LIKE '%$searchTerm%' OR
                            category LIKE '%$searchTerm%' OR
                            sub_cat LIKE '%$searchTerm%' OR
                            description LIKE '%$searchTerm%' OR
                            date_accomplished LIKE '%$searchTerm%' OR
                            pic LIKE '%$searchTerm%'
                        )";
                    }

                    // Count filtered rows
                    $countSql = "SELECT COUNT(*) AS total FROM concerns $where";
                    $countResult = mysqli_query($conn, $countSql);
                    $totalRows = mysqli_fetch_assoc($countResult)['total'];
                    $totalPages = ceil($totalRows / $entriesPerPage);

                    // Fetch filtered, paginated results
                    $sql = "SELECT * FROM concerns $where ORDER BY id DESC LIMIT $startFrom, $entriesPerPage";
                    $result = mysqli_query($conn, $sql);



                    if (mysqli_num_rows($result) > 0):
                        while ($row = mysqli_fetch_assoc($result)):
                    ?>

                            <tr>
                                <td class="ticket-no"><?php echo htmlspecialchars($row['ticket_no']); ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['department']); ?></td>
                                <td><?php echo htmlspecialchars($row['con_date']); ?></td>
                                <td><?php echo htmlspecialchars($row['category']); ?></td>
                                <td><?php echo htmlspecialchars($row['sub_cat']); ?></td>
                                <td>
                                    <div class="tooltip-container">
                                        <?= htmlspecialchars(mb_strimwidth($row['description'], 0, 30, '...')) ?>
                                        <span class="tooltip-text"><?= htmlspecialchars($row['description']) ?></span>
                                    </div>
                                </td>

                                <td><?php echo htmlspecialchars($row['date_accomplished']); ?></td>
                                <td><?php echo htmlspecialchars($row['pic']); ?></td>

                                <td><?php echo htmlspecialchars($row['status']); ?></td>


                            </tr>

                        <?php
                        endwhile;
                    else: ?>
                        <tr>
                            <td colspan="10">No pending concerns found.</td>
                        </tr>
                    <?php endif; ?>

                </tbody>
            </table>
        </div>
        <div style="text-align:center; margin-top: 15px;">
            <?php
            $visiblePages = 3;
            $startPage = max(1, $page - floor($visiblePages / 2));
            $endPage = min($totalPages, $startPage + $visiblePages - 1);

            if ($startPage > 1) {
                $startPage = max(1, $endPage - $visiblePages + 1);
            }

            // Add search query to links
            $searchQuery = isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '';

            // Prev
            if ($page > 1) {
                echo '<a href="?page=' . ($page - 1) . '&limit=' . $entriesPerPage . $searchQuery . '" style="margin:0 5px;">&laquo; Prev</a>';
            }

            // Page numbers
            for ($i = $startPage; $i <= $endPage; $i++) {
                echo '<a href="?page=' . $i . '&limit=' . $entriesPerPage . $searchQuery . '" style="margin:0 5px; padding: 6px 10px; border: 1px solid #ccc; background: ' . ($i == $page ? '#9cc5fa' : '#fff') . '; color: ' . ($i == $page ? '#000' : '#007bff') . '; text-decoration: none; border-radius: 4px;">' . $i . '</a>';
            }

            // Next
            if ($page < $totalPages) {
                echo '<a href="?page=' . ($page + 1) . '&limit=' . $entriesPerPage . $searchQuery . '" style="margin:0 5px;">Next &raquo;</a>';
            }
            ?>
        </div>


        <script>
            // desc js
            function toggleDescription(el) {
                const fullDesc = el.querySelector('.full-desc');
                if (fullDesc.style.display === 'none') {
                    fullDesc.style.display = 'inline';
                    el.firstChild.textContent = '';
                } else {
                    fullDesc.style.display = 'none';
                    const fullText = fullDesc.textContent;
                    el.firstChild.textContent = fullText.substring(0, 30) + '...';
                }
            }
        </script>

    </div>



    <script>
        function searchNow() {
            const search = document.getElementById("searchInput").value.trim();
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set("search", search);
            urlParams.set("page", 1); // Reset to first page
            window.location.search = urlParams.toString();
        }

        function clearSearch() {
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.delete("search");
            urlParams.set("page", 1);
            window.location.search = urlParams.toString();
        }
    </script>

    <style>
        body {
            background-color: rgb(221, 221, 221);
        }

        .pendingContainer {
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
        }

        .add-container {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            gap: 20px;
        }

        .displaypending h1 {
            color: black;
            font-family: 'Merriweather Sans', sans-serif;
            font-size: 25px;
            font-weight: bold;
            text-align: center;
        }

        /* table css */

        .table-section {
            width: 99%;
            overflow-x: auto;
            margin: 0 auto;
        }

        .table-section table {
            width: 100%;
            border-collapse: collapse;
            border-left: none;
            border-right: none;
            font-family: 'Merriweather Sans', sans-serif;

        }

        .table-section th,
        .table-section td {
            padding: 10px;
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            border-left: none;
            border-right: none;
            text-align: left;
            text-align: center;

        }

        .table-section thead th {
            font-size: 14px;
            padding: 20px 10px;
            position: sticky;
            top: 0;
            z-index: 2000;
            /* above */
        }

        .table-section tbody td {
            font-size: 12px;
            padding: 10px 12px;
            font-weight: bold;
        }

        .table-section tbody td.ticket-no {
            color: red;
        }


        .table-section th {
            background-color: #2e2e2eff;
            color: white;
        }

        .table-section tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table-section tbody tr:nth-child(odd) {
            background-color: #ffffffff;
        }

        /* description hover */

        .tooltip-container {
            position: relative;
            display: inline-block;
            cursor: pointer;
            color: #007BFF;
        }

        .tooltip-container .tooltip-text {
            visibility: hidden;
            background-color: #333;
            color: #fff;
            text-align: left;
            border-radius: 6px;
            padding: 10px;
            position: absolute;
            z-index: 1000;
            width: 300px;
            bottom: 125%;

            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            transition: opacity 0.2s;
            font-weight: normal;
            font-size: 12px;
        }

        .tooltip-container:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
            z-index: 3000;
        }

        @media screen and (max-width: 1555px) and (min-width: 320px) {
            .pendingContainer {
                width: 98%;
            }
        }

        @media screen and (max-width: 1950px) and (min-width: 1610px) {
            .pendingContainer {
                min-width: 85.4%;
            }
        }
    </style>


</body>

</html>