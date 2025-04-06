<?php
$servername = "localhost";
$username = "root";  // Change this to your MySQL username
$password = "Bean7150";      // Change this to your MySQL password
$dbname = "students";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
