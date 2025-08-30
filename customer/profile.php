<?php
include '../config/db.php';
include '../includes/auth.php';

// Only allow customers
if ($_SESSION['role'] != 'customer') {
    header("Location: ../login.php");
    exit();
}

$customer_id = $_SESSION['user_id'];
$message = "";

// Fetch current profile
$stmt = $conn->prepare("SELECT name, email, phone FROM users WHERE id = ?");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$stmt->bind_result($name, $email, $phone);
$stmt->fetch();
$stmt->close();

// Handle update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name_new = trim($_POST['name']);
    $email_new = trim($_POST['email']);
    $phone_new = trim($_POST['phone']);
    $password_new = trim($_POST['password']);

    if (!empty($password_new)) {
        $password_hash = password_hash($password_new, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET name=?, email=?, phone=?, password=? WHERE id=?");
        $stmt->bind_param("ssssi", $name_new, $email_new, $phone_new, $password_hash, $customer_id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET name=?, email=?, phone=? WHERE id=?");
        $stmt->bind_param("sssi", $name_new, $email_new, $phone_new, $customer_id);
    }

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>Profile updated successfully.</div>";
        $name = $name_new;
        $email = $email_new;
        $phone = $phone_new;
    } else {
        $message = "<div class='alert alert-danger'>Error: ".$stmt->error."</div>";
    }
    $stmt->close();
}

include '../includes/header.php';
?>

<div class="container mt-4">
    <h2>My Profile</h2>
    <?php echo $message; ?>
    <form method="POST">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($name); ?>" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>
        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($phone); ?>" required>
        </div>
        <div class="mb-3">
            <label>New Password <small>(leave blank to keep current)</small></label>
            <input type="password" name="password" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
