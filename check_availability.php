<?php
// Database connection
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form inputs
    $roomType = $_POST['room_type'];
    $capacity = $_POST['capacity'];
    $date = $_POST['date'];
    $startTime = $_POST['start_time'];
    $endTime = $_POST['end_time'];

    // Validate time format
    if (!preg_match('/^\d{2}:\d{2}$/', $startTime) || !preg_match('/^\d{2}:\d{2}$/', $endTime)) {
        echo json_encode(['error' => 'Invalid time format']);
        exit;
    }

    // Validate start and end time logic
    if (strtotime($startTime) >= strtotime($endTime)) {
        echo json_encode(['error' => 'Start time must be earlier than end time']);
        exit;
    }

    // Combine date with times for proper comparison
    $startDateTime = $date . ' ' . $startTime;
    $endDateTime = $date . ' ' . $endTime;

    try {
        // Query to find available rooms
        $stmt = $conn->prepare("
            SELECT r.name 
            FROM rooms r
            LEFT JOIN bookings b ON r.name = b.room_id 
                AND b.start_time < ? 
                AND b.end_time > ?
                AND DATE(b.start_time) = ?
            WHERE r.type = ? 
              AND r.capacity >= ?
              AND b.id IS NULL;
        ");

        // Bind parameters
        $stmt->bind_param('ssssi', $endDateTime, $startDateTime, $date, $roomType, $capacity);

        // Execute the query
        $stmt->execute();

        // Fetch results
        $result = $stmt->get_result();
        $availableRooms = $result->fetch_all(MYSQLI_ASSOC);

        // Output JSON response
        header('Content-Type: application/json');
        echo json_encode($availableRooms);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request method']);
}
?>
