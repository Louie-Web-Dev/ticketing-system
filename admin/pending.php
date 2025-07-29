<?php
session_start();
if (!isset($_SESSION["name"]) || $_SESSION["pos"] !== "admin") {
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

    <div class="pendingContainer">
        <div class="displaypending bg-transparent">
            <h1>Pending Concerns</h1>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; margin-right: 15px; padding: 0 10px;">
            <div>
                Show
                <select id="entriesCount" onchange="filterTable()" style="padding: 4px 8px; margin: 0 5px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                Entries
            </div>

            <div>
                Search:
                <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search..." style="padding: 4px 8px; margin-left: 5px; border: 1px solid #ccc;">
                <button onclick="clearSearch()" style=" background-color: #e0e0e0; color: #333; border: none; padding: 6px 10px; border-radius: 4px; font-size: 14px; cursor: pointer; transition: background-color 0.2s ease;">
                    Clear</button>
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
                        <th>TYPE</th>
                        <th>STATUS</th>
                        <th>ACTION</th>
                    </tr>
                </thead>

                <tbody id="pendingTableBody">

                </tbody>
            </table>
        </div>

        <script>
            // table js
            function loadPendingConcerns() {
                $.ajax({
                    url: "get_pending.php",
                    type: "GET",
                    success: function(data) {
                        $("#pendingTableBody").html(data);
                        filterTable();
                    },
                    error: function() {
                        $("#pendingTableBody").html("<tr><td colspan='10'>Failed to load data.</td></tr>");
                    }
                });
            }
            setInterval(loadPendingConcerns, 5000);

            $(document).ready(function() {
                loadPendingConcerns();
            });
        </script>

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
    <!-- status bootstrap modal -->

    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Confirm Status Update</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure this concern is already <strong>Done</strong>?
                </div>
                <div class="modal-footer">
                    <form method="POST" action="update_status.php">
                        <input type="hidden" name="id" id="modal_concern_id">
                        <input type="hidden" name="status" value="done">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Proceed</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="onHoldModal" tabindex="-1" aria-labelledby="onHoldModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="onHoldModalLabel">Confirm Status Update</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to put this concern <strong>On-Hold</strong>?
                </div>
                <div class="modal-footer">
                    <form method="POST" action="update_status.php">
                        <input type="hidden" name="id" id="onhold_concern_id">
                        <input type="hidden" name="status" value="on-hold">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning text-white">Proceed</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // modal js
        function openStatusModal(id) {
            document.getElementById('modal_concern_id').value = id;
            var modal = new bootstrap.Modal(document.getElementById('statusModal'));
            modal.show();
        }

        function openOnHoldModal(id) {
            document.getElementById('onhold_concern_id').value = id;
            var modal = new bootstrap.Modal(document.getElementById('onHoldModal'));
            modal.show();
        }
    </script>


    <script>
        // search js
        function filterTable() {
            const input = document.getElementById("searchInput").value.toLowerCase();
            const table = document.querySelector(".table-section table");
            const rows = table.querySelectorAll("tbody tr");
            const entries = parseInt(document.getElementById("entriesCount").value);

            let shown = 0;

            rows.forEach((row, index) => {
                const text = row.textContent.toLowerCase();
                const match = text.includes(input);

                if (match && shown < entries) {
                    row.style.display = "";
                    shown++;
                } else {
                    row.style.display = "none";
                }
            });
        }

        function clearSearch() {
            document.getElementById("searchInput").value = "";
            filterTable();
        }

        window.onload = filterTable;
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
            /* show above the cell */
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