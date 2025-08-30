<?php
// Database connection settings
$host = "localhost";  // Change if different
$user = "root";       // Your MySQL username
$pass = "";           // Your MySQL password
$dbname = "ground_booking"; // Database name

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
