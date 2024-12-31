<?php
include 'db_config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roomType = $_POST['room_type'];
    $course_name = $_POST['course_name'];
    $capacity = $_POST['capacity'];
    $date = $_POST['date'];
    $startTime = $_POST['start_time'];
    $endTime = $_POST['end_time'];

    $startDateTime = $date . ' ' . $startTime;
    $endDateTime = $date . ' ' . $endTime;

    $stmt = $conn->prepare("
        SELECT r.id, r.name
        FROM rooms r
        WHERE r.type = ? AND r.capacity >= ?
        AND NOT EXISTS (
            SELECT 1 FROM bookings b
            WHERE b.room_id = r.id
            AND (
                (b.start_time < ? AND b.end_time > ?)
                OR (b.start_time >= ? AND b.end_time <= ?)
            )
        )
    ");
    $stmt->bind_param('sissss', $roomType, $capacity, $endDateTime, $startDateTime, $startDateTime, $endDateTime);
    $stmt->execute();
    $result = $stmt->get_result();
    $availableRooms = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode($availableRooms);
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>
