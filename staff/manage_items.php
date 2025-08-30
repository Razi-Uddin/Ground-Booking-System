<?php
include '../config/db.php';
include '../includes/auth.php';

if ($_SESSION['role'] != 'staff') {
    header("Location: ../login.php");
    exit();
}

$staff_id = $_SESSION['user_id'];

// Add item
if (isset($_POST['add'])) {
    $ground_id = intval($_POST['ground_id']);
    $name = trim($_POST['name']);
    $quantity = intval($_POST['quantity']);
    $status = trim($_POST['status']);

    $stmt = $conn->prepare("INSERT INTO ground_items (ground_id, name, quantity, status, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("isis", $ground_id, $name, $quantity, $status);
    $stmt->execute();
    $stmt->close();
}

// Delete item
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM ground_items WHERE id=$id");
}

// Fetch all items
$items = $conn->query("SELECT * FROM ground_items ORDER BY created_at DESC");

include '../includes/header.php';
?>
<div class="container mt-4">
    <h2>Manage Items</h2>

    <form method="POST" class="mb-3">
        <div class="row g-2">
            <div class="col-md-2">
                <input type="number" name="ground_id" class="form-control" placeholder="Ground ID" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="name" class="form-control" placeholder="Item Name" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="quantity" class="form-control" placeholder="Quantity" required>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-control" required>
                    <option value="">Select Status</option>
                    <option value="available">Available</option>
                    <option value="unavailable">Unavailable</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" name="add" class="btn btn-primary w-100">Add</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ground ID</th>
                <th>Name</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($items && $items->num_rows > 0) { 
                while ($row = $items->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['ground_id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= $row['quantity'] ?></td>
                    <td><?= $row['status'] ?></td>
                    <td><?= $row['created_at'] ?></td>
                    <td>
                        <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this item?')">Delete</a>
                    </td>
                </tr>
            <?php } 
            } else { ?>
                <tr><td colspan="7">No items found</td></tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php include '../includes/footer.php'; ?>
