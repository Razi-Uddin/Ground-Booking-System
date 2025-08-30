<?php
include '../config/db.php';
include '../includes/auth.php';

if ($_SESSION['role'] != 'staff') {
    header("Location: ../login.php");
    exit();
}

$staff_id = $_SESSION['user_id'];

// If username is not set in session, fetch from DB
if (!isset($_SESSION['name'])) {
    $sql = "SELECT name FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Database error: " . $conn->error);
    }

    $stmt->bind_param("i", $staff_id);
    $stmt->execute();
    $stmt->bind_result($username);
    $stmt->fetch();
    $stmt->close();

    $_SESSION['name'] = $username ?: 'Staff';
}

include '../includes/header.php';
?>
<div class="container mt-4">
    <h2>Staff Dashboard</h2>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</p>

    <div class="row">
        <div class="col-md-3">
            <a href="bookings.php" class="btn btn-primary w-100 mb-2">Book Ground (Walk-in)</a>
        </div>
        <div class="col-md-3">
            <a href="manage_items.php" class="btn btn-secondary w-100 mb-2">Manage Items</a>
        </div>
        <div class="col-md-3">
            <a href="view_bookings.php" class="btn btn-info w-100 mb-2">View My Bookings</a>
        </div>
        <div class="col-md-3">
            <a href="../logout.php" class="btn btn-danger w-100 mb-2">Logout</a>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
