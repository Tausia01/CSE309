<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remove Room</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }
        #navbar {
            width: 100%;
        }
        h1 {
            text-align: center;
            color: rgb(56, 154, 215);
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background-color: white;
            font-size: 16px;
        }
        th, td {
            padding: 12px;
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
        .remove-btn {
            padding: 8px 12px;
            background-color: #FF4C4C;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
        .remove-btn:hover {
            background-color: #d43c3c;
        }
    </style>
</head>
<body>
    <div id="navbar"></div>
    <h1>Remove Room</h1>

    <table>
        <thead>
            <tr>
                <th>Room Name</th>
                <th>Type</th>
                <th>Capacity</th>
                <th>Building</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include 'db_config.php'; // Include your database connection file

            // Handle deletion if a room is removed
            if (isset($_POST['delete_room_id'])) {
                $room_id = intval($_POST['delete_room_id']);
                $delete_query = $conn->prepare("DELETE FROM Rooms WHERE room_id = ?");
                $delete_query->bind_param("i", $room_id);
                $delete_query->execute();
                if ($delete_query->affected_rows > 0) {
                    echo "<script>alert('Room deleted successfully');</script>";
                } else {
                    echo "<script>alert('Failed to delete the room');</script>";
                }
                $delete_query->close();
            }

            // Fetch all rooms from the database
            $query = "SELECT * FROM Rooms";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['room_name']) . "</td>
                            <td>" . htmlspecialchars($row['room_type']) . "</td>
                            <td>" . htmlspecialchars($row['capacity']) . "</td>
                            <td>" . htmlspecialchars($row['building']) . "</td>
                            <td>
                                <form method='POST' style='margin:0;'>
                                    <input type='hidden' name='delete_room_id' value='" . htmlspecialchars($row['room_id']) . "'>
                                    <button type='submit' class='remove-btn'>Remove</button>
                                </form>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No rooms found</td></tr>";
            }

            $result->close();
            $conn->close();
            ?>
        </tbody>
    </table>

    <script>
        fetch('admin_header.html')
            .then(response => response.text())
            .then(data => {
                document.getElementById('navbar').innerHTML = data;
            });
    </script>
</body>
</html>
