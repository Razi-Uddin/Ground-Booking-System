<?php
include 'config/db.php';
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = md5($_POST['password']);
    $phone = trim($_POST['phone']);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, phone) VALUES (?, ?, ?, 'customer', ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $phone);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Registration successful! <a href='login.php'>Login here</a></div>";
    } else {
        echo "<div class='alert alert-danger'>Error: Email may already exist.</div>";
    }
}
?>

<div class="container mt-4">
    <h2>Register as Customer</h2>
    <form method="POST">
        <div class="mb-3">
            <label>Full Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control">
        </div>
        <button class="btn btn-success">Register</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
