<?php
include '../config/db.php';
include '../includes/auth.php';

// Only allow customers
if ($_SESSION['role'] != 'customer') {
    header("Location: ../login.php");
    exit();
}

$customer_id = $_SESSION['user_id'];

// Fetch customer bookings
$stmt = $conn->prepare("SELECT b.id, g.name AS ground_name, b.booking_date, b.start_time, b.end_time, b.total_hours, b.total_amount, b.status
                        FROM bookings b
                        JOIN grounds g ON b.ground_id = g.id
                        WHERE b.customer_id = ?
                        ORDER BY b.booking_date DESC, b.start_time DESC");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

include '../includes/header.php';
?>

<div class="container mt-4">
    <h2>My Bookings</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Ground</th>
                <th>Date</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Total Hours</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): $i=1; ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo htmlspecialchars($row['ground_name']); ?></td>
                        <td><?php echo $row['booking_date']; ?></td>
                        <td><?php echo $row['start_time']; ?></td>
                        <td><?php echo $row['end_time']; ?></td>
                        <td><?php echo $row['total_hours']; ?></td>
                        <td>Rs.<?php echo $row['total_amount']; ?></td>
                        <td><?php echo ucfirst($row['status']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="8" class="text-center">No bookings found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
