<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Account</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: block;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        #navbar {
            width: 100%;
        }

        .container {
            width: 100%;
            max-width: 600px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 30px 200px;
        }

        h1 {
            text-align: center;
            color: rgb(56, 154, 215);
        }

        /* Grid Layout for the form */
        form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
        }

        input, select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            padding: 10px 15px;
            background-color: rgb(56, 154, 215);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: rgb(40, 120, 175);
        }

        .saved-info {
            margin-top: 30px;
            background-color: #f9f9f9;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .saved-info p {
            margin: 5px 0;
            font-size: 16px;
            color: #333;
        }

    </style>
</head>
<body>

    <!-- Include Navbar -->
    <?php include 'faculty_header.php'; ?>
    <div class="container">
        <h1>Faculty Information</h1>

        <!-- Account Form -->
        <form id="account-form">
            <div class="form-group">
                <label for="faculty-id">Faculty ID:</label>
                <input type="text" id="faculty-id" name="faculty-id" required>
            </div>

            <div class="form-group">
                <label for="full-name">Full Name:</label>
                <input type="text" id="full-name" name="full-name" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="department">Department:</label>
                <select id="department" name="department" required>
                    <option value="computer-science">Computer Science</option>
                    <option value="physics">Physics</option>
                    <option value="mathematics">Mathematics</option>
                    <option value="chemistry">Chemistry</option>
                    <option value="biology">Biology</option>
                </select>
            </div>

            <div class="form-group">
                <label for="designation">Designation:</label>
                <select id="designation" name="designation" required>
                    <option value="professor">Professor</option>
                    <option value="assistant-professor">Assistant Professor</option>
                    <option value="lecturer">Lecturer</option>
                </select>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="text" id="phone" name="phone" required>
            </div>

            <div class="form-group">
                <label for="office-room">Office Room Number:</label>
                <input type="text" id="office-room" name="office-room" required>
            </div>

            <button type="button" onclick="saveData()">Save</button>
        </form>

        <!-- Display saved info -->
        <div id="saved-info" class="saved-info" style="display: none;">
            <h2>Saved Information</h2>
            <p><strong>Faculty ID:</strong> <span id="display-faculty-id"></span></p>
            <p><strong>Full Name:</strong> <span id="display-full-name"></span></p>
            <p><strong>Email:</strong> <span id="display-email"></span></p>
            <p><strong>Department:</strong> <span id="display-department"></span></p>
            <p><strong>Designation:</strong> <span id="display-designation"></span></p>
            <p><strong>Phone Number:</strong> <span id="display-phone"></span></p>
            <p><strong>Office Room Number:</strong> <span id="display-office-room"></span></p>
        </div>
    </div>

</body>
</html>
