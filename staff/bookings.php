<?php
include '../config/db.php';
include '../includes/auth.php';

if ($_SESSION['role'] != 'staff') {
    header("Location: ../login.php");
    exit();
}

// ================== AJAX for Available Times ==================
if (isset($_GET['ajax']) && $_GET['ajax'] == 'times') {
    $ground_id = intval($_GET['ground_id']);
    $date = $_GET['date'];

    // Get ground opening and closing time
    $stmt = $conn->prepare("SELECT opening_time, closing_time FROM grounds WHERE id = ?");
    $stmt->bind_param("i", $ground_id);
    $stmt->execute();
    $stmt->bind_result($open_time, $close_time);
    $stmt->fetch();
    $stmt->close();

    $slots = [];
    $start_ts = strtotime($date . ' ' . $open_time);
    $end_ts = strtotime($date . ' ' . $close_time);

    // Handle closing time after midnight
    if ($end_ts <= $start_ts) {
        $end_ts = strtotime($date . ' +1 day ' . $close_time);
    }

    // Generate hourly slots
    while ($start_ts < $end_ts) {
        $slots[] = date("H:i", $start_ts);
        $start_ts = strtotime("+1 hour", $start_ts);
    }

    // Get booked slots
    $booked = [];
    $stmt = $conn->prepare("SELECT start_time, end_time FROM bookings 
                            WHERE ground_id = ? AND booking_date = ? AND status = 'confirmed'");
    $stmt->bind_param("is", $ground_id, $date);
    $stmt->execute();
    $stmt->bind_result($b_start, $b_end);
    while ($stmt->fetch()) {
        $b_start_ts = strtotime($date . ' ' . $b_start);
        $b_end_ts = strtotime($date . ' ' . $b_end);
        if ($b_end_ts <= $b_start_ts) $b_end_ts = strtotime($date . ' +1 day ' . $b_end);
        while ($b_start_ts < $b_end_ts) {
            $booked[] = date("H:i", $b_start_ts);
            $b_start_ts = strtotime("+1 hour", $b_start_ts);
        }
    }
    $stmt->close();

    // Available slots
    $available = array_values(array_diff($slots, $booked));
    sort($available);
    echo json_encode($available);
    exit();
}

// ================== Staff and Grounds Info ==================
$staff_id = $_SESSION['user_id'];
$walkin_customer_id = 1; // Fixed walk-in customer

$grounds = [];
$res = $conn->query("SELECT id, name, per_hour_charge FROM grounds WHERE status = 1");
while ($row = $res->fetch_assoc()) {
    $grounds[] = $row;
}

$message = "";

// ================== Booking Submission ==================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ground_id'])) {
    $ground_id = $_POST['ground_id'];
    $customer_name = trim($_POST['customer_name']);
    $booking_date = $_POST['booking_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $stmt = $conn->prepare("SELECT per_hour_charge FROM grounds WHERE id = ?");
    $stmt->bind_param("i", $ground_id);
    $stmt->execute();
    $stmt->bind_result($per_hour_charge);
    $stmt->fetch();
    $stmt->close();

    $start = strtotime($booking_date . ' ' . $start_time);
    $end = strtotime($booking_date . ' ' . $end_time);
    if ($end <= $start) $end = strtotime($booking_date . ' +1 day ' . $end_time);

    if ($end <= $start) {
        $message = "<div class='alert alert-danger'>End time must be after start time.</div>";
    } else {
        $total_hours = round(($end - $start) / 3600, 2);
        $total_amount = $total_hours * $per_hour_charge;

        $stmt = $conn->prepare("INSERT INTO bookings 
            (ground_id, customer_name, customer_id, staff_id, booking_date, start_time, end_time, total_hours, total_amount, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'confirmed')");
        $stmt->bind_param("isiisssdd", 
            $ground_id, $customer_name, $walkin_customer_id, $staff_id, 
            $booking_date, $start_time, $end_time, $total_hours, $total_amount
        );

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>Walk-in booking created successfully!</div>";
        } else {
            $message = "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
        }
        $stmt->close();
    }
}

include '../includes/header.php';
?>

<div class="container mt-4">
    <h2>Walk-in Ground Booking</h2>
    <?php echo $message; ?>
    <form method="POST">
        <div class="mb-3">
            <label>Select Ground</label>
            <select name="ground_id" id="ground_id" class="form-control" required>
                <option value="">-- Select Ground --</option>
                <?php foreach ($grounds as $g): ?>
                    <option value="<?php echo $g['id']; ?>">
                        <?php echo htmlspecialchars($g['name']); ?> (Rs.<?php echo $g['per_hour_charge']; ?>/hr)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Customer Name</label>
            <input type="text" name="customer_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Booking Date</label>
            <input type="date" name="booking_date" id="booking_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Start Time</label>
            <select name="start_time" id="start_time" class="form-control" required></select>
        </div>
        <div class="mb-3">
            <label>End Time</label>
            <select name="end_time" id="end_time" class="form-control" required></select>
        </div>
        <button type="submit" class="btn btn-primary">Book Ground</button>
    </form>
</div>

<script>
let availableTimes = [];

document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('ground_id').addEventListener('change', loadTimes);
    document.getElementById('booking_date').addEventListener('change', loadTimes);
    document.getElementById('start_time').addEventListener('change', filterEndTimes);
});

function loadTimes() {
    let groundId = document.getElementById('ground_id').value;
    let date = document.getElementById('booking_date').value;
    if (groundId && date) {
        fetch(`bookings.php?ajax=times&ground_id=${groundId}&date=${date}`)
            .then(res => res.json())
            .then(times => {
                availableTimes = times;
                let startSel = document.getElementById('start_time');
                let endSel = document.getElementById('end_time');
                startSel.innerHTML = '<option value="">-- Select Start Time --</option>';
                endSel.innerHTML = '<option value="">-- Select End Time --</option>';
                times.forEach(t => {
                    startSel.add(new Option(t, t));
                });
            });
    }
}

function filterEndTimes() {
    let startTime = document.getElementById('start_time').value;
    let endSel = document.getElementById('end_time');
    endSel.innerHTML = '<option value="">-- Select End Time --</option>';
    availableTimes.forEach(t => {
        if (t > startTime) {
            endSel.add(new Option(t, t));
        }
    });
}
</script>

<?php include '../includes/footer.php'; ?>
