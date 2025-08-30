<?php
include '../config/db.php';
include '../includes/auth.php';
if ($_SESSION['role'] != 'superadmin') {
    header("Location: ../login.php");
    exit();
}

// Add Admin
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, phone) VALUES (?, ?, ?, 'admin', ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $phone);
    $stmt->execute();
}

// Delete Admin
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id=$id AND role='admin'");
}
?>
<?php include '../includes/header.php'; ?>
<div class="d-flex">
    <?php include '../includes/sidebar.php'; ?>
    <div class="container-fluid p-4">
        <h2>Manage Admins</h2>
        <form method="POST" class="mb-4">
            <div class="row g-2">
                <div class="col"><input type="text" name="name" class="form-control" placeholder="Name" required></div>
                <div class="col"><input type="email" name="email" class="form-control" placeholder="Email" required></div>
                <div class="col"><input type="password" name="password" class="form-control" placeholder="Password" required></div>
                <div class="col"><input type="text" name="phone" class="form-control" placeholder="Phone"></div>
                <div class="col"><button type="submit" name="add" class="btn btn-primary">Add Admin</button></div>
            </div>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Action</th></tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM users WHERE role='admin'");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['phone']}</td>
                        <td><a href='?delete={$row['id']}' class='btn btn-danger btn-sm'>Delete</a></td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
