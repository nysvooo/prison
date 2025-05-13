<?php
session_start();

// Ensure the user is logged in as admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Database credentials
$servername = "localhost";
$username = "root";
$password = ""; // No password
$dbname = "prison_system";

// Backup directory (make sure this folder exists or is writable)
$backupDir = "D:\\DB PROJ\\backups";

// Create the backup directory if it doesn't exist
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0777, true);
}

// Create timestamped backup file name
$timestamp = date("Y-m-d_H-i-s");
$backupFile = $backupDir . "\\" . $dbname . "_" . $timestamp . ".sql";

// Path to mysqldump
$mysqldumpPath = "D:\E-books\mysql\bin\mysqldump.exe";

// Build the command
$command = "\"$mysqldumpPath\" -u $username $dbname > \"$backupFile\"";

// Execute the command
exec($command . " 2>&1", $output, $return_var);

// Display the result
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Backup Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f8fb;
            padding: 30px;
            text-align: center;
        }
        h2 {
            color: #333;
        }
        .message {
            padding: 20px;
            margin: 30px auto;
            width: 60%;
            border-radius: 5px;
            font-size: 18px;
        }
        .success {
            background-color: #d1f7d6;
            color: #2e7d32;
            border: 1px solid #81c784;
        }
        .error {
            background-color: #fce4e4;
            color: #b71c1c;
            border: 1px solid #ef9a9a;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            background-color: #1976d2;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }
        .back-link:hover {
            background-color: #125ea2;
        }
        code {
            background: #eee;
            padding: 2px 4px;
            border-radius: 4px;
        }
        pre {
            text-align: left;
            background: #eee;
            padding: 10px;
            border-radius: 4px;
            margin: 20px auto;
            width: 60%;
            overflow-x: auto;
        }
    </style>
</head>
<body>

<?php if ($return_var === 0): ?>
    <div class="message success">
        <h2>✅ Backup Successful!</h2>
        <p>Saved as: <code><?php echo htmlspecialchars($backupFile); ?></code></p>
    </div>
<?php else: ?>
    <div class="message error">
        <h2>❌ Backup Failed!</h2>
        <p>Please check the command output below:</p>
        <pre><?php echo htmlspecialchars(implode("\n", $output)); ?></pre>
    </div>
<?php endif; ?>

<a href="dashboard.php" class="back-link">← Go Back to Dashboard</a>

</body>
</html>
