<?php
include '../config/db.php';

$ground_id = intval($_GET['ground_id'] ?? 0);

if ($ground_id > 0) {
    $stmt = $conn->prepare("SELECT id, name, per_hour_charge, opening_time, closing_time FROM grounds WHERE id = ?");
    $stmt->bind_param("i", $ground_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $ground = $res->fetch_assoc();
    $stmt->close();

    if ($ground) {
        echo json_encode(['status' => 'success', 'ground' => $ground]);
        exit();
    }
}

echo json_encode(['status' => 'error', 'message' => 'Ground not found']);
