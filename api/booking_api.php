<?php
session_start();
include '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$action = $_GET['action'] ?? '';

if ($action === 'times') {
    // Return available times for a ground and date
    $ground_id = intval($_GET['ground_id']);
    $date = $_GET['date'];

    $stmt = $conn->prepare("SELECT opening_time, closing_time FROM grounds WHERE id = ?");
    $stmt->bind_param("i", $ground_id);
    $stmt->execute();
    $stmt->bind_result($open_time, $close_time);
    $stmt->fetch();
    $stmt->close();

    // Generate hourly slots
    $slots = [];
    $current = strtotime($open_time);
    $end = strtotime($close_time);
    while ($current < $end) {
        $slots[] = date("H:i", $current);
        $current = strtotime("+1 hour", $current);
    }

    // Get booked slots
    $booked = [];
    $stmt = $conn->prepare("SELECT start_time, end_time FROM bookings WHERE ground_id=? AND booking_date=? AND status='confirmed'");
    $stmt->bind_param("is", $ground_id, $date);
    $stmt->execute();
    $stmt->bind_result($b_start, $b_end);
    while ($stmt->fetch()) {
        $start_ts = strtotime($b_start);
        $end_ts = strtotime($b_end);
        while ($start_ts < $end_ts) {
            $booked[] = date("H:i", $start_ts);
            $start_ts = strtotime("+1 hour", $start_ts);
        }
    }
    $stmt->close();

    $available = array_values(array_diff($slots, $booked));
    sort($available);

    echo json_encode(['status' => 'success', 'times' => $available]);
    exit();
}

if ($action === 'book') {
    // Handle booking submission
    if ($_SESSION['role'] !== 'customer') {
        echo json_encode(['status' => 'error', 'message' => 'Only customers can book']);
        exit();
    }

    $customer_id = $_SESSION['user_id'];
    $ground_id = intval($_POST['ground_id']);
    $booking_date = $_POST['booking_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $ground = $conn->query("SELECT per_hour_charge FROM grounds WHERE id=$ground_id")->fetch_assoc();
    $per_hour_charge = $ground['per_hour_charge'];

    $start = strtotime($start_time);
    $end = strtotime($end_time);
    if ($end <= $start) {
        echo json_encode(['status' => 'error', 'message' => 'End time must be after start time']);
        exit();
    }

    $total_hours = round(($end - $start) / 3600, 2);
    $total_amount = $total_hours * $per_hour_charge;

    $stmt = $conn->prepare("INSERT INTO bookings (ground_id, customer_id, booking_date, start_time, end_time, total_hours, total_amount, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'confirmed')");
    $stmt->bind_param("iisssdd", $ground_id, $customer_id, $booking_date, $start_time, $end_time, $total_hours, $total_amount);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Booking successfully created']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error booking ground: ' . $stmt->error]);
    }
    $stmt->close();
    exit();
}

echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
