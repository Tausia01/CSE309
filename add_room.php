<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Room</title>
    <style>
        /* Reset body to ensure full-width navbar */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0; /* Remove margin */
            padding: 0; /* Remove padding */
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
        }

        /* Navbar styling */
        #navbar {
            width: 100%; /* Make navbar full width */
        }

        h3 {
            color: #333;
            margin-top: 13px;
        }

        /* Centered form styling */
        form {
            background-color: #ffffff;
            padding: 10px;
            border-radius: 8px;
            width: 60%;
            margin-top: 10px;
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            margin-bottom: 2px;
            color: #555;
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 8px;
            margin: 6px 0 14px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            padding: 8px;
            background-color: #4fa0fd;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        input[type="submit"]:hover {
            background-color: #4562a0;
        }
    </style>
</head>
<body>
    <!-- Include Navbar -->
    <?php include 'admin_header.php'; ?>

    <!-- Page heading -->
    <h3>Add New Room</h3>

    <?php
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Include database connection
        include 'db_config.php';

        // Collect form data
        $room_name = htmlspecialchars($_POST['room_name']);
        $room_type = htmlspecialchars($_POST['room_type']);
        $capacity = intval($_POST['capacity']);
        $building = htmlspecialchars($_POST['building']);

        // SQL to insert data into the Rooms table
        $sql = "INSERT INTO Rooms (room_name, room_type, capacity, building) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ssis", $room_name, $room_type, $capacity, $building); // Bind parameters
            if ($stmt->execute()) {
                echo "<p style='color: green;'>Room '$room_name' of type '$room_type' with capacity $capacity in '$building' has been added successfully!</p>";
            } else {
                echo "<p style='color: red;'>Error: Could not add the room. " . $stmt->error . "</p>";
            }
            $stmt->close(); // Close the statement
        } else {
            echo "<p style='color: red;'>Error preparing statement: " . $conn->error . "</p>";
        }

        $conn->close(); // Close the database connection
    }
    ?>

    <!-- Form content -->
    <form action="" method="POST">
        <label for="room_name">Room Name:</label>
        <input type="text" id="room_name" name="room_name" required><br>

        <label for="room_type">Room Type:</label>
        <select id="room_type" name="room_type" required>
            <option value="Classroom">Classroom</option>
            <option value="Computer Lab">Computer Lab</option>
            <option value="Physics Lab">Physics Lab</option>
            <option value="Pharmacy Lab">Pharmacy Lab</option>
            <option value="Lecture Gallery">Lecture Gallery</option>
            <option value="Multipurpose Hall">Multipurpose Hall</option>
            <option value="Auditorium">Auditorium</option>
        </select><br>

        <label for="capacity">Capacity:</label>
        <input type="number" id="capacity" name="capacity" min="1" required><br>

        <label for="building">Building:</label>
        <select id="building" name="building" required>
            <option value="Main Building">Main Building</option>
            <option value="DMK">DMK</option>
            <option value="Jubilee">Jubilee</option>
        </select><br><br>

        <input type="submit" value="Add Room">
    </form>

</body>
</html>
