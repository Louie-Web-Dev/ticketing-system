<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toyota</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: rgb(221, 221, 221);
            position: relative;
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
            overflow-y: auto;
        }

        /* Add this to ensure modals appear above everything */
        .modal {
            z-index: 1060 !important;
        }

        /* Table styles remain the same */
        .table-section {
            width: 99%;
            overflow-x: auto;
            margin: 0 auto;
            position: static !important;
        }

        .table-section table {
            width: 100%;
            border-collapse: collapse;
            font-family: 'Merriweather Sans', sans-serif;
        }

        .table-section th,
        .table-section td {
            padding: 10px;
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            text-align: center;
        }

        .table-section thead th {
            font-size: 14px;
            padding: 20px 10px;
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #000000ff;
            color: white;
        }

        .table-section tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table-section tbody tr:nth-child(odd) {
            background-color: #ffffffff;
        }
    </style>
</head>

<body>
    <?php include 'nav.php'; ?>

    <div class="pendingContainer">
        <div class="displaypending bg-transparent">
            <h1>Users</h1>
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
                        <th>ID</th>
                        <th>FULL NAME</th>
                        <th>USERNAME</th>
                        <th>DEPARTMENT</th>
                        <th>POSITION</th>
                        <th>DATE CREATED</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require_once "database.php";

                    $entriesPerPage = isset($_GET['limit']) ? (int)$_GET['limit'] : 16;
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $startFrom = ($page - 1) * $entriesPerPage;

                    $searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
                    $where = "1";

                    if (!empty($searchTerm)) {
                        $where .= " AND (
                            name LIKE '%$searchTerm%' OR
                            username LIKE '%$searchTerm%' OR
                            department LIKE '%$searchTerm%' OR
                            pos LIKE '%$searchTerm%'
                        )";
                    }

                    $countSql = "SELECT COUNT(*) AS total FROM user WHERE $where";
                    $countResult = mysqli_query($conn, $countSql);
                    $totalRows = mysqli_fetch_assoc($countResult)['total'];
                    $totalPages = ceil($totalRows / $entriesPerPage);

                    $sql = "SELECT * FROM user WHERE $where ORDER BY id DESC LIMIT $startFrom, $entriesPerPage";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0):
                        while ($row = mysqli_fetch_assoc($result)):
                    ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']) ?></td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['username']) ?></td>
                                <td><?= htmlspecialchars($row['department']) ?></td>
                                <td><?= htmlspecialchars($row['pos'] ?? 'Regular User') ?></td>
                                <td><?= htmlspecialchars($row['created_at'] ?? 'N/A') ?></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary edit-user"
                                        data-id="<?= $row['id'] ?>"
                                        data-name="<?= htmlspecialchars($row['name']) ?>"
                                        data-username="<?= htmlspecialchars($row['username']) ?>"
                                        data-department="<?= htmlspecialchars($row['department']) ?>"
                                        data-pos="<?= htmlspecialchars($row['pos']) ?>">
                                        Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-user"
                                        data-id="<?= $row['id'] ?>"
                                        data-name="<?= htmlspecialchars($row['name']) ?>">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        <?php
                        endwhile;
                    else:
                        ?>
                        <tr>
                            <td colspan="7">No users found.</td>
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
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="update_user.php" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="user_id" id="editUserId">
                        <div class="mb-3">
                            <label for="editName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" id="editUsername" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="editDepartment" class="form-label">Department</label>
                            <input type="text" class="form-control" id="editDepartment" name="department">
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="editIsAdmin" name="is_admin" value="1">
                            <label class="form-check-label" for="editIsAdmin">Admin User</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete User Modal -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="delete_user.php" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteUserModalLabel">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete user <strong id="deleteUserName"></strong>?
                        <input type="hidden" name="user_id" id="deleteUserId">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Edit User Modal Handler
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('edit-user')) {
                    const button = e.target;
                    const userId = button.getAttribute('data-id');
                    const userName = button.getAttribute('data-name');
                    const username = button.getAttribute('data-username');
                    const department = button.getAttribute('data-department');
                    const isAdmin = button.getAttribute('data-pos') === 'admin';

                    document.getElementById('editUserId').value = userId;
                    document.getElementById('editName').value = userName;
                    document.getElementById('editUsername').value = username;
                    document.getElementById('editDepartment').value = department;
                    document.getElementById('editIsAdmin').checked = isAdmin;

                    document.getElementById('editUserModalLabel').textContent = `Edit User: ${userName}`;

                    const editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
                    editModal.show();
                }

                if (e.target.classList.contains('delete-user')) {
                    const button = e.target;
                    const userId = button.getAttribute('data-id');
                    const userName = button.getAttribute('data-name');

                    document.getElementById('deleteUserId').value = userId;
                    document.getElementById('deleteUserName').textContent = userName;

                    const deleteModal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
                    deleteModal.show();
                }
            });
        });

        function searchNow() {
            const search = document.getElementById("searchInput").value.trim();
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set("search", search);
            urlParams.set("page", 1);
            window.location.search = urlParams.toString();
        }

        function clearSearch() {
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.delete("search");
            urlParams.set("page", 1);
            window.location.search = urlParams.toString();
        }
    </script>
</body>

</html>