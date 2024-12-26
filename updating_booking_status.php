<?php
include 'db_config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $booking_id = $data['booking_id'] ?? null;
    $status = $data['status'] ?? null;

    if (!$booking_id || !$status) {
        echo json_encode(['success' => false, 'error' => 'Missing booking ID or status']);
        exit;
    }

    if (!in_array($status, ['Pending', 'Confirmed', 'Rejected'])) {
        echo json_encode(['success' => false, 'error' => 'Invalid status']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
    $stmt->bind_param('si', $status, $booking_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
