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

        /* Full-width navbar */
        #navbar {
            width: 100%;
        }

        /* Main container styling */
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

        /* Table styling */
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

        /* Status label styling */
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
    <?php include 'faculty_header.php'; ?>

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
                <!-- Example rows (these would be dynamically generated based on user's bookings) -->
                <tr>
                    <td>DMK1023</td>
                    <td>CS101 - Introduction to Programming</td>
                    <td>2024-11-10</td>
                    <td>10:00 AM</td>
                    <td>12:00 PM</td>
                    <td><span class="status status-confirmed">Confirmed</span></td>
                </tr>


            </tbody>
        </table>
    </div>

</body>
</html>
