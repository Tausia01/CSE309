

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classroom Booking Requests</title>
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

        h1 {
            color: #333;
            margin: 20px 0;
        }

        table {
            width: 80%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: rgb(56, 154, 215);
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        select, .confirm-button {
            padding: 5px;
            font-size: 14px;
            cursor: pointer;
        }

        .confirm-button {
            background-color: rgb(56, 154, 215);
            color: white;
            border: none;
        }

        .confirm-button:hover {
            background-color: #1f78b4;
        }
    </style>
</head>
<body>
    <!-- Include Navbar -->
    <?php 
    ob_start();
    session_start();
    include 'admin_header.php'; ?>

    <!-- Page title -->
    <h1>Classroom Booking Requests</h1>

    <!-- Booking table -->
    <table>
        <thead>
            <tr>
                <th>Classroom Number</th>
                <th>Faculty ID</th>
                <th>Faculty Name</th>
                <th>Date</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Status</th>
                <th>Confirm</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include 'db_config.php';

            $query = "
                SELECT 
                    b.id AS booking_id,
                    r.name AS classroom,
                    b.faculty_id,
                    f.name AS faculty_name,
                    DATE_FORMAT(b.start_time, '%Y-%m-%d') AS booking_date,
                    DATE_FORMAT(b.start_time, '%H:%i') AS start_time,
                    DATE_FORMAT(b.end_time, '%H:%i') AS end_time,
                    b.status
                FROM bookings b
                JOIN rooms r ON b.room_id = r.id
                JOIN faculty f ON b.faculty_id = f.id
                ORDER BY b.start_time ASC
            ";

            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['classroom']) . "</td>
                            <td>" . htmlspecialchars($row['faculty_id']) . "</td>
                            <td>" . htmlspecialchars($row['faculty_name']) . "</td>
                            <td>" . htmlspecialchars($row['booking_date']) . "</td>
                            <td>" . htmlspecialchars($row['start_time']) . "</td>
                            <td>" . htmlspecialchars($row['end_time']) . "</td>
                            <td>
                                <select id='status_" . $row['booking_id'] . "'>
                                    <option value='Pending'" . ($row['status'] === 'Pending' ? ' selected' : '') . ">Pending</option>
                                    <option value='Confirmed'" . ($row['status'] === 'Confirmed' ? ' selected' : '') . ">Confirm</option>
                                    <option value='Rejected'" . ($row['status'] === 'Rejected' ? ' selected' : '') . ">Reject</option>
                                </select>
                            </td>
                            <td><button class='confirm-button' onclick='updateStatus(" . $row['booking_id'] . ")'>Update</button></td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No booking requests found.</td></tr>";
            }

            $conn->close();
            ?>
        </tbody>
    </table>

    <script>
function updateStatus(bookingId) {
    const status = document.getElementById(`status_${bookingId}`).value;
    console.log('Sending data:', JSON.stringify({ booking_id: bookingId, status: status }));

    fetch('updating_booking_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ booking_id: bookingId, status: status }),
    })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Server response:', data);
            if (data.success) {
                alert('Status updated successfully.');
                location.reload(); // Reload the page to reflect changes
            } else {
                alert('Failed to update status: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('An error occurred while updating the status.');
        });
}

    </script>
</body>
</html>
