<?php
// Database configuration
$servername = "localhost";  // Database server (localhost if running locally)
$username = "root";         // MySQL username (default for XAMPP is "root")
$password = "";             // MySQL password (default for XAMPP is an empty string)
$database = "ClassroomDB";  // Name of your database

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
