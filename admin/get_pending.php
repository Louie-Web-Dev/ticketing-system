<?php
require_once "database.php";

$sql = "SELECT * FROM concerns WHERE status IN ('Pending', 'on-hold') ORDER BY id DESC";
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

            <td><?php echo htmlspecialchars($row['concern_type']); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
            <td>
                <?php if ($row['status'] !== 'on-hold'): ?>
                    <a href="javascript:void(0);" onclick="openOnHoldModal(<?php echo $row['id']; ?>)">
                        <i class="fa-solid fa-thumbtack" style="color: orange; font-size: 28px;"></i>
                    </a>
                <?php endif; ?>

                <a href="javascript:void(0);" onclick="openStatusModal(<?php echo $row['id']; ?>)">
                    <i class="fa-solid fa-square-check" style="color: green; font-size: 28px;"></i>
                </a>
            </td>

        </tr>
    <?php
    endwhile;
else:
    ?>
    <tr>
        <td colspan="10">No pending concerns found.</td>
    </tr>
<?php endif; ?>