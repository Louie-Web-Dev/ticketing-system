<?php
include("database.php");
session_start();

$name_q = $_SESSION['name'];

if (!isset($_SESSION['username']) || !isset($_SESSION['name'])) {
    header("Location: login.php");
    exit();
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completed Concerns | IT Help Desk</title>
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
                        <i class="fas fa-check-circle me-2"></i> Completed Concerns
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
                                        <th><i class="fas fa-calendar-check me-1"></i> Date Accomplished</th>
                                        <th><i class="fas fa-user-tie me-1"></i> PIC</th>
                                        <th><i class="fas fa-info-circle me-1"></i> Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT id, ticket_no, con_date, category, sub_cat, description, date_accomplished, status, pic 
                                            FROM concerns 
                                            WHERE status = 'done' AND name = '" . $name_q . "'";

                                    $retval = mysqli_query($conn, $sql);

                                    if ($retval && $retval->num_rows > 0) {
                                        while ($row = $retval->fetch_assoc()) {
                                            echo "<tr>
                                                <td class='ticket-no text-center fw-bold'>" . htmlspecialchars($row["ticket_no"]) . "</td>
                                                <td class='text-center'>" . date('M d, Y', strtotime(htmlspecialchars($row["con_date"]))) . "</td>
                                                <td class='text-center'>" . htmlspecialchars($row["category"]) . "</td>
                                                <td class='text-center'>" . htmlspecialchars($row["sub_cat"]) . "</td>
                                                <td class='description-cell' title='" . htmlspecialchars($row["description"]) . "'>
                                                    <div class='description-content'>" . htmlspecialchars($row["description"]) . "</div>
                                                </td>
                                                <td class='text-center'>" . (!empty($row["date_accomplished"]) ? date('M d, Y', strtotime(htmlspecialchars($row["date_accomplished"]))) : 'N/A') . "</td>
                                                <td class='text-center'>" . htmlspecialchars($row["pic"]) . "</td>
                                                <td class='text-center'><span class='badge bg-success'>" . ucfirst(htmlspecialchars($row["status"])) . "</span></td>
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
            $('#tblConcerns').DataTable({
                responsive: true,
                language: {
                    emptyTable: "<div class='empty-state'><i class='far fa-folder-open fa-3x text-muted mb-3'></i><h5 class='text-muted'>No completed concerns found</h5></div>",
                    zeroRecords: "<div class='empty-state'><i class='fas fa-search fa-3x text-muted mb-3'></i><h5 class='text-muted'>No matching records found</h5></div>"
                },
                order: [
                    [5, 'desc']
                ], // Default sort by date accomplished descending
                columnDefs: [{
                        responsivePriority: 1,
                        targets: 0
                    }, // Ticket No
                    {
                        responsivePriority: 2,
                        targets: 7
                    }, // Status
                    {
                        responsivePriority: 3,
                        targets: 5
                    }, // Date Accomplished
                    {
                        responsivePriority: 4,
                        targets: 4
                    } // Description
                ]
            });
        });
    </script>
</body>

</html>