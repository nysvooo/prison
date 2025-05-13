<?php
session_start();
include('db.php'); // Include the database connection

// Ensure that the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php"); // Redirect to the login page if not logged in
    exit();
}

// Initialize the search term variable
$search_term = '';

// Check if the search term is provided via form submission
if (isset($_POST['search_term'])) {
    $search_term = $_POST['search_term'];
}

// Fetch prisoners from the database to display in the dashboard, with search query if provided
$query = "SELECT * FROM prisoners WHERE first_name LIKE '%$search_term%' OR last_name LIKE '%$search_term%'";
$result = $conn->query($query);

// Check if the query is successful
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
    <title>Dashboard - Prison System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e3f2fd; /* Blue background */
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
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
        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #1976d2; /* Blue border */
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #1976d2; /* Blue header */
            color: white;
        }
        td {
            background-color: #f0f4f8; /* Light blue */
        }
        button {
            padding: 10px 20px;
            background-color: #1976d2; /* Blue button */
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #1565c0; /* Darker blue */
        }
        .add-btn, .admin-btn {
            margin-bottom: 20px;
            display: block;
            text-align: center;
        }
        .logout-btn {
            text-align: right;
            margin-bottom: 20px;
        }
        a {
            text-decoration: none;
        }
        .search-form {
            margin: 20px 0;
            text-align: center;
        }
        .search-form input {
            padding: 10px;
            width: 300px;
            margin-right: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
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
    <h1>College of Computer Science Prison</h1>

    <!-- Search Form -->
    <div class="search-form">
        <form method="POST" action="dashboard.php">
            <input type="text" name="search_term" placeholder="Search by First or Last Name" value="<?php echo htmlspecialchars($search_term); ?>" />
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Add Prisoner Button -->
    <div class="add-btn">
        <a href="add_prisoner.php">
            <button>Add Prisoner</button>
        </a>
    </div>

    <!-- Admin Login Button -->
    <div class="admin-btn">
        <a href="admin_login.php">
            <button>ADMIN</button>
        </a>
    </div>

    <!-- Logout Button -->
    <div class="logout-btn">
        <a href="logout.php">
            <button>Logout</button>
        </a>
    </div>

    <!-- Prisoner Table -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Crime Committed</th>
                <th>Sentence Length</th>
                <th>Date of Incarceration</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Display filtered prisoner data -->
            <?php
            while ($row = $result->fetch_assoc()) {
                // Convert sentence length to years, months, and days
                $sentence_length_in_days = $row['sentence_length'];
                $years = floor($sentence_length_in_days / 365);
                $remaining_days = $sentence_length_in_days % 365;
                $months = floor($remaining_days / 30);
                $days = $remaining_days % 30;

                $formatted_sentence = "";
                if ($years > 0) {
                    $formatted_sentence .= $years . " years ";
                }
                if ($months > 0) {
                    $formatted_sentence .= $months . " months ";
                }
                if ($days > 0) {
                    $formatted_sentence .= $days . " days";
                }

                // Display the data in the table
                echo "<tr>
                    <td>" . $row['id'] . "</td>
                    <td>" . $row['first_name'] . "</td>
                    <td>" . $row['last_name'] . "</td>
                    <td>" . $row['crime_committed'] . "</td>
                    <td>" . $formatted_sentence . "</td>
                    <td>" . $row['date_of_incarceration'] . "</td>
                    <td>" . $row['status'] . "</td>
                    <td>
                        <a href='update.php?id=" . $row['id'] . "'><button>Edit</button></a>
                        <a href='delete.php?id=" . $row['id'] . "'><button>Delete</button></a>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>

</div>

<!-- Footer Section -->
<div class="footer">
    <p>&copy; 2025 College of Computer Science Prison. All rights reserved.</p>
</div>

</body>
</html>
