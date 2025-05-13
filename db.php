<?php
$host = "localhost";
$user = "root"; // your MySQL username
$password = ""; // your MySQL password
$dbname = "prison_system";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


