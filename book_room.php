<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Room</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .main-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-top: 20px;
            width: 100%;
            max-width: 1200px;
        }

        .container {
            flex: 1;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            font-weight: bold;
        }

        form, table {
            width: 100%;
        }

        input, select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            background-color: #4fa0fd;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        table {
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: rgb(56, 154, 215);
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        button:hover {
            background-color: #4562a0;
        }
    </style>
</head>
<body>
<?php
    ob_start();
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    include 'faculty_header.php'; ?>
    <div class="main-container">
        <!-- Form Container -->
        <div class="container">
            <h2>Book a Room</h2>

            <!-- Form to input room requirements -->
            <form id="roomRequirementsForm">
                <label for="room_type">Room Type:</label>
                <select id="room_type" name="room_type" required>
                    <option value="classroom">Classroom</option>
                    <option value="computer lab">Computer Lab</option>
                    <option value="physics lab">Physics Lab</option>
                    <option value="pharmacy lab">Pharmacy Lab</option>
                    <option value="lecture gallery">Lecture Gallery</option>
                </select>

                <label for="course_name">Course ID:</label>
                <input type="text" id="course_name" name="course_name" required>

                <label for="capacity">Capacity:</label>
                <input type="number" id="capacity" name="capacity" min="1" required>

                <label for="date">Date:</label>
                <input type="date" id="date" name="date" required>

                <label for="start_time">Start Time:</label>
                <input type="time" id="start_time" name="start_time" required>

                <label for="end_time">End Time:</label>
                <input type="time" id="end_time" name="end_time" required>

                <button type="button" onclick="checkAvailability()">Check Availability</button>
            </form>
        </div>

        <!-- Available Rooms Container -->
        <div class="container">
            <h2>Available Rooms</h2>
            <table>
                <thead>
                    <tr>
                        <th>Room Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="availableRooms">
                    <!-- Rows are populated dynamically -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function checkAvailability() {
            const form = document.getElementById('roomRequirementsForm');
            const formData = new FormData(form);

            fetch('check_availability.php', {
                method: 'POST',
                body: formData,
            })
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.getElementById('availableRooms');
                    tableBody.innerHTML = ''; // Clear previous results

                    if (data.length > 0) {
                        data.forEach(room => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${room.name}</td>
                                <td><button onclick="bookRoom(${room.id})">Book</button></td>
                            `;
                            tableBody.appendChild(row);
                        });
                    } else {
                        tableBody.innerHTML = '<tr><td colspan="2">No rooms available</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to fetch available rooms.');
                });
        }

        function bookRoom(roomId) {
            const form = document.getElementById('roomRequirementsForm');
            const formData = new FormData(form);
            formData.append('room_id', roomId);

            fetch('book_room.php', {
                method: 'POST',
                body: formData,
            })
                .then(response => response.text())
                .then(data => {
                    alert(data); // Show server response
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to book the room.');
                });
        }
    </script>
</body>
</html>

<?php
include 'db_config.php';

// Check if the user is logged in
if (!isset($_SESSION['faculty_id'])) {
    echo "Unauthorized access. Please log in.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $faculty_id = $_SESSION['faculty_id'];
    $room_id = intval($_POST['room_id']);
    $course_name = htmlspecialchars($_POST['course_name']);
    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // Combine date and time for start and end
    $start_datetime = $date . ' ' . $start_time;
    $end_datetime = $date . ' ' . $end_time;

    // Check for conflicting bookings
    $conflict_query = "
        SELECT * FROM bookings 
        WHERE room_id = ? 
        AND (
            (start_time < ? AND end_time > ?)
            OR (start_time < ? AND end_time > ?)
            OR (start_time >= ? AND end_time <= ?)
        )
    ";
    $stmt = $conn->prepare($conflict_query);
    $stmt->bind_param('issssss', $room_id, $end_datetime, $start_datetime, $start_datetime, $end_datetime, $start_datetime, $end_datetime);
    $stmt->execute();
    $conflict_result = $stmt->get_result();

    if ($conflict_result->num_rows > 0) {
        echo "The selected room is already booked for the specified time.";
    } else {
        // Insert booking into the database
        $insert_query = "
            INSERT INTO bookings (room_id, faculty_id, course_name, start_time, end_time, status)
            VALUES (?, ?, ?, ?, ?, 'Pending')
        ";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param('iisss', $room_id, $faculty_id, $course_name, $start_datetime, $end_datetime);

        if ($stmt->execute()) {
            echo "Booking request submitted successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    $stmt->close();
}
$conn->close();
?>
