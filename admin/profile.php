<?php
include '../config/db.php';
include '../includes/auth.php';
if ($_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
$admin_id = $_SESSION['user_id'];

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("UPDATE users SET name=?, email=?, phone=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $email, $phone, $admin_id);
    $stmt->execute();
}

$user = $conn->query("SELECT * FROM users WHERE id=$admin_id")->fetch_assoc();
?>
<?php include '../includes/header.php'; ?>
<div class="d-flex">
    <?php include '../includes/sidebar.php'; ?>
    <div class="container-fluid p-4">
        <h2>Profile</h2>
        <form method="POST">
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo $user['name']; ?>" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="mb-3">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control" value="<?php echo $user['phone']; ?>">
            </div>
            <button type="submit" name="update" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
