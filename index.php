<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <style>
        /* Basic styling for the sign-in form */
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f9;
            color: #333333;
        }

        /* Container styling */
        .sign-in-container {
            background-color: #ffffff;
            padding: 30px;
            width: 360px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            text-align: center;
        }

        h2 {
            margin-bottom: 24px;
            color: #333;
            font-size: 24px;
        }

        label {
            font-size: 14px;
            color: #555;
            display: block;
            margin-bottom: 8px;
            text-align: left;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #4fa0fd;
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #4fa0fd;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-weight: bold;
        }

        button:hover {
            background-color: #4562a0;
        }

        /* Link styling */
        .sign-up-link {
            display: block;
            margin-top: 16px;
            font-size: 14px;
            color: #4fa0fd;
            text-decoration: none;
        }

        .sign-up-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="sign-in-container">
        <h2>Log In</h2>
        <form action="index.php" method="POST"> 
            <label for="id">ID</label>
            <input type="text" id="id" name="id" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Log In</button>
        </form>
        <a href="sign_up.php" class="sign-up-link">Sign Up Now</a>
    </div>
</body>
</html>


<?php
// Include the database connection
include 'db_config.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize form data
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $password = $_POST['password'];

    try {
        // Determine if the user is admin or faculty
        if (is_numeric($id)) {
            // Check if the user exists in the faculty table
            $stmt = $conn->prepare("SELECT password FROM faculty WHERE id = ?");
            $stmt->bind_param('i', $id);
        } else {
            // Check if the user exists in the admin table (using email)
            $stmt = $conn->prepare("SELECT password FROM admins WHERE email = ?");
            $stmt->bind_param('s', $id);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashedPassword = $row['password'];

            // Verify the password
            if (password_verify($password, $hashedPassword)) {
                // Redirect based on user type
                if (is_numeric($id)) {
                    // Redirect faculty
                    header("Location: faculty_account.php");
                } else {
                    // Redirect admin
                    header("Location: booking_requests.php");
                }
                exit;
            } else {
                die("Invalid password.");
            }
        } else {
            die("User not found.");
        }
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    die("Invalid request method.");
}
?>
