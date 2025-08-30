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
        <h2>Customers</h2>
        <table class="table table-bordered">
            <thead>
                <tr><th>ID</th><th>Name</th><th>Email</th></tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT DISTINCT u.id, u.name, u.email 
                        FROM bookings b
                        JOIN users u ON b.customer_id = u.id
                        JOIN grounds g ON b.ground_id = g.id
                        WHERE g.admin_id=$admin_id";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
