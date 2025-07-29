<?php
include("database.php");
session_start();

$user = $_SESSION['username'];
$name_q = $_SESSION['name'];

$page = $_SERVER['PHP_SELF'];
$sec = "10";

$date_acc = date("Y-m-d");

if (!isset($_SESSION['username']) || !isset($_SESSION['name'])) {
    header("Location: /TSP-system/ticketing-system/");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Concerns | IT Help Desk</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="user_pending.css">

</head>

<body>
    <?php include 'user_nav.php'; ?>
    <div class="Container">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-clock me-2"></i> Pending Concerns
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tblConcerns" class="table table-hover align-middle" style="width:100%">
                                <thead class="table-dark">
                                    <tr>
                                        <th><i class="fas fa-ticket-alt me-1"></i> Ticket No</th>
                                        <th><i class="far fa-calendar me-1"></i> Date Filed</th>
                                        <th><i class="fas fa-tag me-1"></i> Category</th>
                                        <th><i class="fas fa-tags me-1"></i> Sub-Category</th>
                                        <th><i class="fas fa-align-left me-1"></i> Description</th>
                                        <th><i class="fas fa-user-tie me-1"></i> Person In-Charge</th>
                                        <th><i class="fas fa-info-circle me-1"></i> Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT id, ticket_no, con_date, category, sub_cat, description, status, pic 
                                            FROM concerns 
                                            WHERE status = 'pending' AND name = '" . $name_q . "'";

                                    $retval = mysqli_query($conn, $sql);

                                    if ($retval && $retval->num_rows > 0) {
                                        while ($row = $retval->fetch_assoc()) {
                                            echo "<tr>
                                                <td class='ticket-no text-center fw-bold'>" . '#' . htmlspecialchars($row["ticket_no"]) . "</td>
                                                <td class='text-center'>" . date('M d, Y', strtotime(htmlspecialchars($row["con_date"]))) . "</td>
                                                <td class='text-center'>" . htmlspecialchars($row["category"]) . "</td>
                                                <td class='text-center'>" . htmlspecialchars($row["sub_cat"]) . "</td>
                                                <td class='description-cell' title='" . htmlspecialchars($row["description"]) . "'>
                                                    <div class='description-content'>" . htmlspecialchars($row["description"]) . "</div>
                                                </td>
                                                <td class='text-center'>" . htmlspecialchars($row["pic"]) . "</td>
                                                <td class='text-center'><span class='badge bg-warning text-dark'>" . ucfirst(htmlspecialchars($row["status"])) . "</span></td>
                                            </tr>";
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <small class="text-muted">Last updated: <?php echo date('F j, Y, g:i a'); ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery, Bootstrap JS, and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {

            $('#tblConcerns tbody tr').each(function() {
                if ($(this).find('td').length !== 7) console.log('Row with incorrect columns:', this);
            });

            $('#tblConcerns').DataTable({
                responsive: true,
                language: {
                    emptyTable: "<div class='empty-state'><i class='far fa-check-circle fa-3x text-muted mb-3'></i><h5 class='text-muted'>No pending concerns found</h5></div>",
                    zeroRecords: "<div class='empty-state'><i class='fas fa-search fa-3x text-muted mb-3'></i><h5 class='text-muted'>No matching records found</h5></div>"
                },
                order: [
                    [1, 'desc']
                ],
                columnDefs: [{
                        responsivePriority: 1,
                        targets: 0
                    },
                    {
                        responsivePriority: 2,
                        targets: 6
                    },
                    {
                        responsivePriority: 3,
                        targets: 1
                    },
                    {
                        responsivePriority: 4,
                        targets: 4
                    }
                ]
            });
        });
    </script>
    <style>
        .Container {
            display: flex;
            flex-direction: column;
            background-color: white;
            width: 85.5%;
            height: 91%;
            position: fixed;
            right: 10px;
            margin-top: 83px;
            border: 1px solid black;
            border-radius: 15px;
            padding-bottom: 20px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
            overflow-y: auto;
        }

        .description-content {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            transition: all 0.3s ease;
        }

        th i {
            color: white;
        }

        td.description-cell:hover .description-content {
            white-space: normal;
            background-color: #f8f9fa;
            padding: 4px;
            border-radius: 4px;
            z-index: 2;
            position: relative;
        }

        :root {
            --primary-blue: #2163ce;
            --secondary-blue: #1a56b4;
            --light-gray: #f8f9fa;
            --dark-gray: #343a40;
        }

        .row {
            width: 1500px;
        }

        body {
            background-color: rgb(221, 221, 221);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .card {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }

        .card-header {
            background-color: var(--primary-blue);
            color: white;
            font-weight: 600;
            padding: 1.2rem;
            border-radius: 0.5rem 0.5rem 0 0 !important;
        }

        .card-footer {
            padding: 0.75rem 1.5rem;
            background-color: rgba(0, 0, 0, 0.03);
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        .table thead th {
            background-color: var(--dark-gray);
            color: white;
            font-weight: 500;
            vertical-align: middle;
            padding: 0.75rem;
        }

        .table tbody td {
            padding: 0.75rem;
            vertical-align: middle;
        }

        .ticket-no {
            color: var(--primary-blue);
            font-weight: bold;
        }

        .description-cell {
            max-width: 250px;
        }

        .description-content {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .badge {
            font-weight: 500;
            padding: 0.5em 0.75em;
        }

        .empty-state {
            text-align: center;
            padding: 2rem 0;
        }

        .empty-state i {
            opacity: 0.6;
        }

        @media (max-width: 992px) {
            .Container {
                width: 83%;
            }

            .description-cell {
                max-width: 150px;
            }
        }

        @media (max-width: 768px) {
            .Container {
                width: 95%;
                height: auto;
                position: static;
                margin: 83px auto 20px;
            }

            .table-responsive {
                overflow-x: auto;
            }

            .description-cell {
                max-width: 120px;
            }
        }
    </style>
</body>

</html>