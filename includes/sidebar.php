<div class="d-flex flex-column p-3 bg-dark text-white" style="width: 250px; height: 100vh;">
    <h4 class="mb-4">Menu</h4>
    <ul class="nav nav-pills flex-column mb-auto">
        <?php if ($_SESSION['role'] == 'superadmin'): ?>
            <li><a href="dashboard.php" class="nav-link text-white">🏠 Dashboard</a></li>
            <li><a href="admins.php" class="nav-link text-white">👑 Manage Admins</a></li>
            <li><a href="bookings.php" class="nav-link text-white">📅 All Bookings</a></li>
            <li><a href="reports.php" class="nav-link text-white">📊 Reports</a></li>
        <?php elseif ($_SESSION['role'] == 'admin'): ?>
            <li><a href="dashboard.php" class="nav-link text-white">🏠 Dashboard</a></li>
            <li><a href="grounds.php" class="nav-link text-white">⚽ Manage Grounds</a></li>
            <li><a href="staff.php" class="nav-link text-white">👥 Manage Staff</a></li>
            <li><a href="bookings.php" class="nav-link text-white">📅 Bookings</a></li>
        <?php elseif ($_SESSION['role'] == 'staff'): ?>
            <li><a href="dashboard.php" class="nav-link text-white">🏠 Dashboard</a></li>
            <li><a href="manage_items.php" class="nav-link text-white">🛠 Manage Ground Items</a></li>
            <li><a href="view_bookings.php" class="nav-link text-white">📅 View Bookings</a></li>
        <?php endif; ?>
        <li><a href="logout.php" class="nav-link text-white">🚪 Logout</a></li>
    </ul>
</div>
