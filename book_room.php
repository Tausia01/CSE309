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
            height: 100vh;
        }

        /* Full-width navbar */
        #navbar {
            width: 100%;
        }

        /* Main container styling */
        .container {
            width: 100%;
            max-width: 1000px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }

        /* Heading styles */
        h1, h2 {
            text-align: center;
            color: #333;
            font-weight: bold;
        }

        /* Form and table sections */
        .form-section, .table-section {
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        /* Grid layout for the form */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        /* Label and input styling */
        label {
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
            display: block;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        input[type="time"],
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        /* Styling for the Enter button */
        .enter-button {
            grid-column: span 2;
            text-align: center;
        }

        input[type="submit"],
        button.book-button {
            padding: 10px;
            background-color: #389AD7;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover,
        button.book-button:hover {
            background-color: #3080B5;
        }

        /* Table styling for available rooms */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th {
            background-color: #f0f0f0;
            color: #333;
            padding: 10px;
            font-weight: bold;
        }

        td {
            padding: 10px;
            text-align: center;
        }

        tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <!-- Include Navbar -->
    <?php include 'faculty_header.php'; ?>

    <div class="container">
        <!-- Form Section (Left Column) -->
        <div class="form-section">
            <h2>Room Requirements</h2>
            <form class="form-grid" action="javascript:checkAvailability();" method="POST">
                <div>
                    <label for="room_type">Room Type:</label>
                    <select id="room_type" name="room_type" required>
                        <option value="classroom">Classroom</option>
                        <option value="computer lab">Computer Lab</option>
                        <option value="physics lab">Physics Lab</option>
                        <option value="pharmacy lab">Pharmacy Lab</option>
                        <option value="lecture gallery">Lecture Gallery</option>
                        <option value="multipurpose hall">Multipurpose Hall</option>
                        <option value="auditorium">Auditorium</option>
                    </select>
                </div>

                <div>
                    <label for="course">Course Name:</label>
                    <input type="text" id="course" name="course" required>
                </div>

                <div>
                    <label for="capacity">Capacity:</label>
                    <input type="number" id="capacity" name="capacity" min="1" required>
                </div>

                <div>
                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date" required>
                </div>

                <div>
                    <label for="start_time">Start Time:</label>
                    <input type="time" id="start_time" name="start_time" required>
                </div>

                <div>
                    <label for="end_time">End Time:</label>
                    <input type="time" id="end_time" name="end_time" required>
                </div>

                <div class="enter-button">
                    <input type="submit" value="Enter">
                </div>
            </form>
        </div>

        <!-- Available Rooms Table Section (Right Column) -->
        <div class="table-section">
            <h2>Book Room</h2>
            <table>
                <thead>
                    <tr>
                        <th>Room Name</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- dynamic data generation -->
   
                </tbody>
            </table>
            <button class="book-button" onclick="bookRoom()">Book</button>
        </div>
    </div>

    <!-- JavaScript to load navbar content -->
    <script>

        let selectedRoom = null;

        function selectRoom(roomName) {
            selectedRoom = roomName;
        }

        function bookRoom() {
            if (selectedRoom) {
                alert(`Booking ${selectedRoom}`);
                // Perform booking or redirect here
            } else {
                alert("Please select a room to book.");
            }
        }

        function checkAvailability() {
    // Collect form data
    const formData = new FormData(document.querySelector('.form-grid'));

    // Send AJAX request to the backend
    fetch('check_availability.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const tableBody = document.querySelector('.table-section tbody');
        tableBody.innerHTML = ''; // Clear previous rows

        if (data.length > 0) {
            data.forEach(room => {
                const row = document.createElement('tr');
                row.onclick = () => selectRoom(room.name);
                row.innerHTML = `<td>${room.name}</td>`;
                tableBody.appendChild(row);
            });
        } else {
            const row = document.createElement('tr');
            row.innerHTML = `<td colspan="1">No rooms available</td>`;
            tableBody.appendChild(row);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to fetch available rooms');
    });
}


    </script>
    
</body>
</html>
