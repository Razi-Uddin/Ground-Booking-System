<?php
include '../config/db.php';
include '../includes/auth.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$admin_id = $_SESSION['user_id'];

// Ensure assets/images/grounds folder exists
$upload_dir = __DIR__ . '/../assets/images/grounds/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Add ground
if (isset($_POST['add'])) {
    $name       = trim($_POST['name']);
    $location   = trim($_POST['location']);
    $charges    = floatval($_POST['charges']);
    $open_time  = $_POST['open_time'];
    $close_time = $_POST['close_time'];
    $status     = isset($_POST['status']) ? intval($_POST['status']) : 1; // default active
    $image      = '';

    // Handle file upload
    if (!empty($_FILES['image']['name'])) {
        $image = time() . '_' . basename($_FILES['image']['name']);
        $target_path = $upload_dir . $image;
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            die("Error: Failed to upload image.");
        }
    }

    // Save relative path
    $relative_path = "assets/images/grounds/" . $image;

    // Insert into DB
    $stmt = $conn->prepare("
        INSERT INTO grounds 
        (admin_id, name, location, per_hour_charge, opening_time, closing_time, image, status, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }
    $stmt->bind_param("issdsssi", $admin_id, $name, $location, $charges, $open_time, $close_time, $relative_path, $status);
    $stmt->execute();
    $stmt->close();
}

// Delete ground
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM grounds WHERE id=$id AND admin_id=$admin_id");
}

include '../includes/header.php';
?>
<div class="d-flex">
    <?php include '../includes/sidebar.php'; ?>
    <div class="container-fluid p-4">
        <h2>Manage Grounds</h2>

        <form method="POST" enctype="multipart/form-data" class="mb-4">
            <div class="row g-2">
                <div class="col">
                    <input type="text" name="name" class="form-control" placeholder="Ground Name" required>
                </div>
                <div class="col">
                    <input type="text" name="location" class="form-control" placeholder="Location" required>
                </div>
                <div class="col">
                    <input type="number" step="0.01" name="charges" class="form-control" placeholder="Per Hour Charge" required>
                </div>
                <div class="col">
                    <input type="time" name="open_time" class="form-control" required>
                </div>
                <div class="col">
                    <input type="time" name="close_time" class="form-control" required>
                </div>
                <div class="col">
                    <input type="file" name="image" class="form-control">
                </div>
                <div class="col">
                    <select name="status" class="form-control">
                        <option value="1" selected>Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="col">
                    <button type="submit" name="add" class="btn btn-primary">Add Ground</button>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Charges</th>
                    <th>Timing</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM grounds WHERE admin_id=$admin_id ORDER BY created_at DESC");
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        $img_path = !empty($row['image']) ? "../" . $row['image'] : "https://via.placeholder.com/60";
                        $status_text = $row['status'] ? "<span class='badge bg-success'>Active</span>" : "<span class='badge bg-secondary'>Inactive</span>";
                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td><img src='{$img_path}' width='60'></td>
                            <td>{$row['name']}</td>
                            <td>{$row['location']}</td>
                            <td>{$row['per_hour_charge']}</td>
                            <td>{$row['opening_time']} - {$row['closing_time']}</td>
                            <td>{$status_text}</td>
                            <td>{$row['created_at']}</td>
                            <td><a href='?delete={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Delete this ground?\")'>Delete</a></td>
                        </tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
