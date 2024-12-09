<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classroom Booking Requests</title>
    <style>
        /* Ensure that body takes up the entire viewport width */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0; /* Remove any default margin */
            padding: 0; /* Remove padding to let navbar span full width */
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            color: #333;
            margin: 20px 0;
        }
        
        /* Navbar styles */
        #navbar {
            width: 100%; /* Make sure navbar spans the full width */
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
            background-color: rgb(56, 154, 215);
        }
    </style>
</head>
<body>
    <!-- Include Navbar -->
    <?php include 'admin_header.php'; ?>

    <!-- Page title -->
    <h1>Classroom Booking Requests</h1>

    <!-- Booking table -->
    <table>
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
        <tr>
            <td>BC4007</td>
            <td>22140</td>
            <td>Dr. Readul Islam</td>
            <td>2024-11-05</td>
            <td>09:00</td>
            <td>11:00</td>
            <td>
                <select name="status1">
                    <option value="accept">Accept</option>
                    <option value="reject">Reject</option>
                </select>
            </td>
            <td><button class="confirm-button">Confirm</button></td>
        </tr>
        <tr>
            <td>MK5006</td>
            <td>21631</td>
            <td>Mr. Abu Syed</td>
            <td>2024-11-06</td>
            <td>13:00</td>
            <td>15:00</td>
            <td>
                <select name="status2">
                    <option value="accept">Accept</option>
                    <option value="reject">Reject</option>
                </select>
            </td>
            <td><button class="confirm-button">Confirm</button></td>
        </tr>
        <!-- Additional rows as needed -->
    </table>


</body>
</html>
