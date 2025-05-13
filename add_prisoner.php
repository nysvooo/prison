<?php
session_start();
include('db.php'); // Include the correct database connection

// Ensure that the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php"); // Redirect to the login page if not logged in
    exit();
}

// Check if the form is submitted to add new prisoner data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $crime_committed = $_POST['crime_committed'];  // No encryption here
    $sentence_length = $_POST['sentence_length'];
    $date_of_incarceration = $_POST['date_of_incarceration'];
    $status = $_POST['status'];

    // Insert prisoner details into the database without encrypting the crime_committed
    $sql = "INSERT INTO prisoners (first_name, last_name, crime_committed, sentence_length, date_of_incarceration, status) 
            VALUES ('$first_name', '$last_name', '$crime_committed', '$sentence_length', '$date_of_incarceration', '$status')";

    if ($conn->query($sql) === TRUE) {
        // Redirect to dashboard after successful insertion
        header("Location: dashboard.php");
        exit();
    } else {
        $error_message = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Prisoner</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
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
            margin: 10px 0;
            color: green;
        }

        .error-message {
            text-align: center;
            margin: 10px 0;
            color: red;
        }

        a {
            text-decoration: none;
            color: #007bff;
            display: block;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Add New Prisoner</h2>

    <!-- Error Message if any -->
    <?php if (isset($error_message)): ?>
        <div class="error-message"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <form action="add_prisoner.php" method="POST">
        <label for="first_name">First Name</label>
        <input type="text" id="first_name" name="first_name" placeholder="Enter First Name" required>

        <label for="last_name">Last Name</label>
        <input type="text" id="last_name" name="last_name" placeholder="Enter Last Name" required>

        <label for="crime_committed">Crime Committed</label>
        <input type="text" id="crime_committed" name="crime_committed" placeholder="Enter Crime Committed" required>

        <label for="sentence_length">Sentence Length in Days</label>
        <input type="text" id="sentence_length" name="sentence_length" placeholder="Enter Sentence Length" required>

        <label for="date_of_incarceration">Date of Incarceration</label>
        <input type="date" id="date_of_incarceration" name="date_of_incarceration" required>

        <label for="status">Status</label>
        <select name="status" id="status" required>
            <option value="Incarcerated">Incarcerated</option>
            <option value="Out">Out</option>
        </select>

        <button type="submit">Add Prisoner</button>
    </form>

    <a href="dashboard.php">Back to Dashboard</a>
</div>

</body>
</html>
