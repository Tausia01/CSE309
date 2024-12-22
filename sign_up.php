<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        /* Basic styling for the sign-up form */
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        /* Container styling */
        .sign-up-container {
            background-color: #ffffff;
            padding: 16px 24px;
            width: 320px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
        }

        h2 {
            margin-bottom: 16px;
            color: #333;
            font-size: 22px;
        }

        label {
            font-size: 13px;
            color: #555;
            display: block;
            margin-bottom: 4px;
            text-align: left;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        select:focus {
            border-color: #4fa0fd;
            outline: none;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4fa0fd;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 15px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-weight: bold;
        }

        button:hover {
            background-color: #4562a0;
        }

        p {
            font-size: 13px;
            margin-top: 12px;
        }

        a {
            color: #4fa0fd;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="sign-up-container">
        <h2>Sign Up</h2>
        <form action="sign_up.php" method="POST">
            <label for="id">ID</label>
            <input type="text" id="id" name="id" required>

            <label for="name">Name</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="user_type">User Type</label>
            <select id="user_type" name="user_type" required>
                <option value="faculty">Faculty</option>
                <option value="admin">Admin</option>
            </select>

            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="index.php">Log In</a></p>
    </div>
</body>
</html>

<?php
// Include the database connection
include 'db_config.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize form data
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($_POST['name']);
    $email = filter_var($_POST['email'] );
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $user_type = $_POST['user_type']; // No sanitization needed due to controlled select options

    // Check if the user already exists
    if ($user_type === 'faculty') {
        $checkQuery = "SELECT id FROM faculty WHERE id = ? OR email = ?";
    } elseif ($user_type === 'admin') {
        $checkQuery = "SELECT id FROM admins WHERE email = ?";
    } else {
        die("Invalid user type.");
    }

    $stmt = $conn->prepare($checkQuery);
    if ($user_type === 'faculty') {
        $stmt->bind_param('is', $id, $email);
    } else {
        $stmt->bind_param('s', $email);
    }
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die("User already exists with this ID or Email.");
    }

    // Insert into the respective table
    if ($user_type === 'faculty') {
        $insertQuery = "INSERT INTO faculty (id, name, email, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param('isss', $id, $name, $email, $password);
    } elseif ($user_type === 'admin') {
        $insertQuery = "INSERT INTO admins (name, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param('sss', $name, $email, $password);
    }

    if ($stmt->execute()) {
        echo "Sign-up successful!";
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    die("");
}
?>

