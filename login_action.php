<?php
session_start();
require_once 'db_connectionregister.php'; // Include your database connection file

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to find the user by username
    $sql = "SELECT * FROM users WHERE username = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username); // 's' indicates the parameter is a string
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Check if the user exists and if the password matches
    if ($user) {
        // If user exists, verify the password
        if (password_verify($password, $user['password'])) {
            // Password is correct, start the session and redirect to dashboard
            $_SESSION['username'] = $user['username']; // Store username in session
            $_SESSION['user_id'] = $user['id']; // Store user ID in session
            header('Location: dashboard.php'); // Redirect to dashboard page
            exit;
        } else {
            // Password is incorrect
            $_SESSION['error_message'] = "Incorrect password!";
        }
    } else {
        // User doesn't exist
        $_SESSION['error_message'] = "No user found with that username!";
    }

    // Redirect to index.php to show the error message
    header('Location: index.php');
    exit;
}
