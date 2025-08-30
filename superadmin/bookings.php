<?php
include '../config/db.php';
include '../includes/auth.php';
if ($_SESSION['role'] != 'superadmin') {
    header("Location: ../login.php");
    exit();
}
?>
<?php include '../includes/header.php'; ?>
<div class="d-flex">
    <?php include '../includes/sidebar.php'; ?>
    <div class="container-fluid p-4">
        <h2>All Bookings</h2>
        <table class="table table-bordered">
            <thead>
                <tr><th>ID</th><th>Ground</th><th>Customer</th><th>Date</th><th>Start</th><th>End</th><th>Status</th></tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT b.*, g.name AS ground_name, u.name AS customer_name 
                        FROM bookings b
                        JOIN grounds g ON b.ground_id = g.id
                        JOIN users u ON b.customer_id = u.id
                        ORDER BY b.booking_date DESC";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['ground_name']}</td>
                        <td>{$row['customer_name']}</td>
                        <td>{$row['booking_date']}</td>
                        <td>{$row['start_time']}</td>
                        <td>{$row['end_time']}</td>
                        <td>{$row['status']}</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
