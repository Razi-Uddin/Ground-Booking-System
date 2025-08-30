<?php
include '../config/db.php';
include '../includes/auth.php';
if ($_SESSION['role'] != 'superadmin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['save'])) {
    foreach ($_POST as $key => $value) {
        if ($key != 'save') {
            $stmt = $conn->prepare("INSERT INTO system_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value=?");
            $stmt->bind_param("sss", $key, $value, $value);
            $stmt->execute();
        }
    }
    $msg = "Settings saved successfully!";
}

$settings = [];
$res = $conn->query("SELECT * FROM system_settings");
while ($row = $res->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}
?>
<?php include '../includes/header.php'; ?>
<div class="d-flex">
    <?php include '../includes/sidebar.php'; ?>
    <div class="container-fluid p-4">
        <h2>System Settings</h2>
        <?php if (!empty($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>
        <form method="POST">
            <div class="mb-3">
                <label>Website Name</label>
                <input type="text" name="website_name" class="form-control" value="<?php echo $settings['website_name'] ?? ''; ?>">
            </div>
            <div class="mb-3">
                <label>Contact Email</label>
                <input type="email" name="contact_email" class="form-control" value="<?php echo $settings['contact_email'] ?? ''; ?>">
            </div>
            <div class="mb-3">
                <label>Contact Phone</label>
                <input type="text" name="contact_phone" class="form-control" value="<?php echo $settings['contact_phone'] ?? ''; ?>">
            </div>
            <button type="submit" name="save" class="btn btn-primary">Save Settings</button>
        </form>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
