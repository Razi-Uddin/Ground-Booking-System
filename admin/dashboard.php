<?php
include '../config/db.php';
include '../includes/auth.php';
if ($_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
$admin_id = $_SESSION['user_id'];
?>
<?php include '../includes/header.php'; ?>
<div class="d-flex">
    <?php include '../includes/sidebar.php'; ?>
    <div class="container-fluid p-4">
        <h2>Admin Dashboard</h2>
        <div class="row">
            <div class="col-md-3">
                <div class="card text-bg-primary mb-3">
                    <div class="card-body">
                        <h5>Total Grounds</h5>
                        <p class="fs-4">
                            <?php
                            $count = $conn->query("SELECT COUNT(*) AS total FROM grounds WHERE admin_id=$admin_id")->fetch_assoc();
                            echo $count['total'];
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-bg-success mb-3">
                    <div class="card-body">
                        <h5>Total Bookings</h5>
                        <p class="fs-4">
                            <?php
                            $count = $conn->query("SELECT COUNT(*) AS total FROM bookings WHERE ground_id IN (SELECT id FROM grounds WHERE admin_id=$admin_id)")->fetch_assoc();
                            echo $count['total'];
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
