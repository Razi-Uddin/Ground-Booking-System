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
        <h2>Reports & Analytics</h2>

        <?php
        $total_earnings = $conn->query("SELECT SUM(total_amount) AS total FROM bookings WHERE status='completed'")->fetch_assoc()['total'];
        $total_bookings = $conn->query("SELECT COUNT(*) AS total FROM bookings")->fetch_assoc()['total'];
        ?>

        <div class="row">
            <div class="col-md-4">
                <div class="card text-bg-success mb-3">
                    <div class="card-body">
                        <h5>Total Earnings</h5>
                        <p class="fs-4">$<?php echo number_format($total_earnings, 2); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-bg-primary mb-3">
                    <div class="card-body">
                        <h5>Total Bookings</h5>
                        <p class="fs-4"><?php echo $total_bookings; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
