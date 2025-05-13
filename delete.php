<?php
session_start();
include('db.php'); // Include the database connection

// Ensure that the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php"); // Redirect to the login page if not logged in
    exit();
}

// Get the prisoner ID from the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch prisoner details to display for confirmation
    $query = "SELECT * FROM prisoners WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo "Prisoner not found.";
        exit();
    }

    $prisoner = $result->fetch_assoc();
}

// Handle deletion after confirmation
if (isset($_POST['confirm_delete'])) {
    // Perform the deletion
    $delete_query = "DELETE FROM prisoners WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("i", $id);
    $delete_stmt->execute();

    if ($delete_stmt->affected_rows > 0) {
        echo "Prisoner deleted successfully.";
        header("Location: dashboard.php"); // Redirect back to dashboard after deletion
        exit();
    } else {
        echo "Error deleting prisoner: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Prisoner - Prison System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center; /* Center the text */
        }
        h1 {
            margin-bottom: 20px;
        }
        p {
            margin: 20px 0;
        }
        table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 8px 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .button-container {
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        button {
            padding: 10px 20px;
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #e53935;
        }
        a {
            text-decoration: none;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border-radius: 4px;
        }
        a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Confirm Deletion</h1>

    <!-- Display prisoner details for confirmation -->
    <p>Are you sure you want to delete the following prisoner?</p>
    <table>
        <tr>
            <th>ID:</th>
            <td><?php echo $prisoner['id']; ?></td>
        </tr>
        <tr>
            <th>First Name:</th>
            <td><?php echo $prisoner['first_name']; ?></td>
        </tr>
        <tr>
            <th>Last Name:</th>
            <td><?php echo $prisoner['last_name']; ?></td>
        </tr>
        <tr>
            <th>Crime Committed:</th>
            <td><?php echo $prisoner['crime_committed']; ?></td>
        </tr>
        <tr>
            <th>Sentence Length:</th>
            <td><?php echo $prisoner['sentence_length']; ?></td>
        </tr>
    </table>

    <!-- Confirmation buttons -->
    <div class="button-container">
        <form method="POST" action="">
            <button type="submit" name="confirm_delete">Yes, Delete</button>
        </form>
        <a href="dashboard.php">Cancel</a>
    </div>
</div>

</body>
</html>
