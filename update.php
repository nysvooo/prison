<?php
session_start();
include('db.php');

// Ensure that the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php"); // Redirect to the login page if not logged in
    exit();
}

// Get the prisoner ID from the URL (passed when clicking 'Edit')
$prisoner_id = $_GET['id'];

// Fetch the current data for the prisoner
$query = "SELECT * FROM prisoners WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $prisoner_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// If no prisoner is found
if (!$row) {
    echo "Prisoner not found!";
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $crime_committed = $_POST['crime_committed'];
    $sentence_length = $_POST['sentence_length'];
    $date_of_incarceration = $_POST['date_of_incarceration'];
    $status = $_POST['status'];  // Get the status value from the form

    // Prepare and execute the update query
    $update_query = "UPDATE prisoners SET first_name = ?, last_name = ?, crime_committed = ?, sentence_length = ?, date_of_incarceration = ?, status = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssssi", $first_name, $last_name, $crime_committed, $sentence_length, $date_of_incarceration, $status, $prisoner_id);

    if ($stmt->execute()) {
        // Redirect to dashboard after successful update
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error updating prisoner: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Prisoner</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e3f2fd; /* Blue background */
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #0d47a1; /* Dark blue */
        }
        label {
            display: block;
            margin: 10px 0 5px;
            color: #0d47a1;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #1976d2; /* Blue border */
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="date"] {
            padding: 9px; /* Adjusted for date inputs */
        }
        button {
            background-color: #1976d2; /* Blue button */
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #1565c0; /* Darker blue */
        }
        .footer {
            text-align: center;
            background-color: #1976d2;
            color: white;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        .footer p {
            margin: 0;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Edit Prisoner Details</h1>
    <form action="update.php?id=<?php echo urlencode($prisoner_id); ?>" method="POST">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($row['first_name']); ?>" required /><br>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($row['last_name']); ?>" required /><br>

        <label for="crime_committed">Crime Committed:</label>
        <input type="text" id="crime_committed" name="crime_committed" value="<?php echo htmlspecialchars($row['crime_committed']); ?>" required /><br>

        <label for="sentence_length">Sentence Length (days):</label>
        <input type="number" id="sentence_length" name="sentence_length" value="<?php echo htmlspecialchars($row['sentence_length']); ?>" required /><br>

        <label for="date_of_incarceration">Date of Incarceration:</label>
        <input type="date" id="date_of_incarceration" name="date_of_incarceration" value="<?php echo htmlspecialchars($row['date_of_incarceration']); ?>" required /><br>

        <label for="status">Status:</label>
<input type="text" id="status" name="status" list="status-options" value="<?php echo htmlspecialchars($row['status']); ?>" required />
<datalist id="status-options">
    <option value="Incarcerated">
    <option value="Out">
</datalist><br>

        <button type="submit">Update Prisoner</button>
    </form>
</div>

<!-- Footer Section -->
<div class="footer">
    <p>&copy; 2025 College of Computer Science Prison. All rights reserved.</p>
</div>

</body>
</html>
