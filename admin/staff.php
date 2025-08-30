<?php
include '../config/db.php';
include '../includes/auth.php';
if ($_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
$admin_id = $_SESSION['user_id'];

// Add staff
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, parent_admin_id) VALUES (?, ?, ?, 'staff', ?)");
    $stmt->bind_param("sssi", $name, $email, $password, $admin_id);
    $stmt->execute();
}

// Delete staff
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id=$id AND parent_admin_id=$admin_id");
}
?>
<?php include '../includes/header.php'; ?>
<div class="d-flex">
    <?php include '../includes/sidebar.php'; ?>
    <div class="container-fluid p-4">
        <h2>Manage Staff</h2>
        <form method="POST" class="mb-4">
            <div class="row g-2">
                <div class="col"><input type="text" name="name" class="form-control" placeholder="Name" required></div>
                <div class="col"><input type="email" name="email" class="form-control" placeholder="Email" required></div>
                <div class="col"><input type="password" name="password" class="form-control" placeholder="Password" required></div>
                <div class="col"><button type="submit" name="add" class="btn btn-primary">Add Staff</button></div>
            </div>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM users WHERE role='staff' AND parent_admin_id=$admin_id");

                if (!$result) {
                    die("Query Failed: " . $conn->error);
                }

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['email']}</td>
                    <td><a href='?delete={$row['id']}' class='btn btn-danger btn-sm'>Delete</a></td>
                    </tr>";
                }

                ?>
            </tbody>
        </table>
    </div>
</div>
<?php include '../includes/footer.php'; ?>