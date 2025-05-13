<?php
session_start();
include('db.php'); // Include the database connection

// Ensure the user is logged in as admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php"); // Redirect to login page if not logged in
    exit();
}

// Fetch users from the database
$query = "SELECT id, username, password FROM users";  // Fetch username and encrypted password
$result = $conn->query($query);

// Check for query success
if ($result === false) {
    echo "Error fetching data: " . $conn->error;
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px 15px;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        button {
            padding: 10px 20px;
            background-color: #007bff; /* Blue color */
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }
        .logout-btn {
            text-align: right;
            margin-top: 20px;
        }
        a {
            text-decoration: none;
            color: white;
        }
        .footer {
            text-align: center;
            background-color: #333;
            color: white;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        .backup-btn {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Admin Dashboard</h1>

    <!-- Backup Button -->
    <div class="backup-btn">
        <button onclick="window.location.href='run_backup.php';">Run Backup</button>
    </div>

    <!-- Users Table -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Encrypted Password</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>" . $row['id'] . "</td>
                    <td>" . $row['username'] . "</td>
                    <td>" . htmlspecialchars($row['password']) . "</td>
                </tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Logout Button -->
    <div class="logout-btn">
        <a href="admin_logout.php">
            <button>Logout</button>
        </a>
    </div>
</div>

<!-- Footer Section -->
<div class="footer">
    <p>&copy; 2025 Your Company. All rights reserved.</p>
</div>

</body>
</html>
