<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: rgb(56, 154, 215);
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }

        .status {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 4px;
            display: inline-block;
        }

        .status-confirmed {
            background-color: #4caf50;
            color: white;
        }

        .status-pending {
            background-color: #ff9800;
            color: white;
        }

        .status-rejected {
            background-color: #ec2517;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Include Navbar -->
    <?php 
    session_start();
    include 'faculty_header.php'; ?>

    <div class="container">
        <h1>My Bookings</h1>
        <table>
            <thead>
                <tr>
                    <th>Classroom No</th>
                    <th>Course Name</th>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'db_config.php';

                // Check if the user is logged in
                if (!isset($_SESSION['faculty_id'])) {
                    echo "<tr><td colspan='6'>Unauthorized access. Please log in.</td></tr>";
                    exit;
                }

                $faculty_id = $_SESSION['faculty_id'];

                // Fetch the faculty's bookings from the database
                $query = "
                    SELECT 
                        r.name AS classroom,
                        b.course_name,
                        DATE_FORMAT(b.start_time, '%Y-%m-%d') AS booking_date,
                        DATE_FORMAT(b.start_time, '%h:%i %p') AS start_time,
                        DATE_FORMAT(b.end_time, '%h:%i %p') AS end_time,
                        b.status
                    FROM bookings b
                    JOIN rooms r ON b.room_id = r.id
                    WHERE b.faculty_id = ?
                    ORDER BY b.start_time ASC
                ";

                $stmt = $conn->prepare($query);
                $stmt->bind_param('i', $faculty_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['classroom']) . "</td>
                                <td>" . htmlspecialchars($row['course_name'] ?? 'N/A') . "</td>
                                <td>" . htmlspecialchars($row['booking_date']) . "</td>
                                <td>" . htmlspecialchars($row['start_time']) . "</td>
                                <td>" . htmlspecialchars($row['end_time']) . "</td>
                                <td><span class='status status-" . strtolower($row['status']) . "'>" . htmlspecialchars($row['status']) . "</span></td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No bookings found</td></tr>";
                }

                $stmt->close();
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
