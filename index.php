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
session_start(); // Start session at the top
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = trim($_POST['id']); // Collect and sanitize ID
    $password = $_POST['password'];

    try {
        // First, check if the user is an admin
        $stmt = $conn->prepare("SELECT id, password FROM admins WHERE id = ?");
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // User is an admin
            $row = $result->fetch_assoc();
            $hashedPassword = $row['password'];

            if (password_verify($password, $hashedPassword)) {
                $_SESSION['admin_id'] = $row['id']; // Set session for admin
                header("Location: booking_requests.php");
                exit;
            } else {
                echo "Invalid password.";
                exit;
            }
        }

        // If not found in admins, check faculty
        $stmt = $conn->prepare("SELECT id, password FROM faculty WHERE id = ?");
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // User is a faculty member
            $row = $result->fetch_assoc();
            $hashedPassword = $row['password'];

            if (password_verify($password, $hashedPassword)) {
                $_SESSION['faculty_id'] = $row['id']; // Set session for faculty
                header("Location: faculty_account.php");
                exit;
            } else {
                echo "Invalid password.";
                exit;
            }
        }

        // If no match found
        echo "User not found.";
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}
?>

