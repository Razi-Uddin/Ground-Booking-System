<?php
include '../config/db.php';
include '../includes/auth.php';

if ($_SESSION['role'] != 'staff') {
    header("Location: ../login.php");
    exit();
}

$staff_id = $_SESSION['user_id'];

$bookings = $conn->query("
    SELECT b.*, g.name AS ground_name
    FROM bookings b
    JOIN grounds g ON b.ground_id = g.id
    WHERE b.staff_id = $staff_id
    ORDER BY b.start_time DESC
");

include '../includes/header.php';
?>
<div class="container mt-4">
    <h2>My Bookings</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ground</th>
                <th>Customer</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $bookings->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['ground_name']) ?></td>
                    <td><?= htmlspecialchars($row['customer_name']) ?></td>
                    <td><?= $row['start_time'] ?></td>
                    <td><?= $row['end_time'] ?></td>
                    <td><?= ucfirst($row['status']) ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php include '../includes/footer.php'; ?>
