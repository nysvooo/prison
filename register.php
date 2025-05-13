<?php
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include('db_connectionregister.php'); // Include database connection

    $username = $_POST['username'];
    $password_plain = $_POST['password'];

    $number_count = preg_match_all('/[0-9]/', $password_plain);

    // SERVER-SIDE VALIDATION
    if (strlen($password_plain) < 8) {
        $error_message = "Password must be at least 8 characters long.";
    } elseif ($number_count < 3) {
        $error_message = "Password must contain at least 3 numbers.";
    } else {
        $password = password_hash($password_plain, PASSWORD_DEFAULT); // Hash the password

        // Insert the username and hashed password into the database
        $query = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $username, $password);

        if ($stmt->execute()) {
            $success_message = "User registered successfully!";
        } else {
            $error_message = "Error: " . $stmt->error;
        }
    }
}
?>

<!-- HTML Part -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .register-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 300px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            text-align: center;
            margin-bottom: 15px;
            color: green;
        }
        .error {
            text-align: center;
            margin-bottom: 15px;
            color: red;
        }
        .return-link {
            text-align: center;
            margin-top: 20px;
        }
        .return-link a {
            text-decoration: none;
            color: #007bff;
        }
    </style>
</head>
<body>

<div class="register-container">
    <h2>Register</h2>

    <?php if (isset($success_message)): ?>
        <div class="message"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
        <div class="error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <form action="register.php" method="POST" onsubmit="return validatePassword()">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <span id="password-error" style="color: red; font-size: 14px;"></span>

        <button type="submit">Register</button>
    </form>

    <!-- Link to return to the login page -->
    <div class="return-link">
        <a href="index.php">Return to Login</a>
    </div>
</div>

<script>
function validatePassword() {
    const password = document.getElementById("password").value;
    const error = document.getElementById("password-error");

    const numberMatches = password.match(/[0-9]/g) || [];
    const hasEnoughNumbers = numberMatches.length >= 3;
    const isLongEnough = password.length >= 8;

    if (!isLongEnough) {
        error.textContent = "Password must be at least 8 characters long.";
        return false;
    } else if (!hasEnoughNumbers) {
        error.textContent = "Password must contain at least 3 numbers.";
        return false;
    }

    error.textContent = "";
    return true;
}
</script>

</body>
</html>
