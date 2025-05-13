<?php
include 'db.php';

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];

    $sql = "UPDATE prisoners SET name='$name', email='$email' WHERE id=$id"; // 'prisoners' table

    if ($conn->query($sql) === TRUE) {
        echo "Prisoner updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT name, email FROM prisoners WHERE id=$id"; // 'prisoners' table
$result = $conn->query($sql);
$row = $result->fetch_assoc();

?>

<h1>Edit Prisoner</h1>
<form method="post" action="update.php?id=<?php echo $id; ?>">
    Name: <input type="text" name="name" value="<?php echo $row['name']; ?>"><br>
    Email: <input type="text" name="email" value="<?php echo $row['email']; ?>"><br>
    <input type="submit" value="Update Prisoner">
</form>
<a href="index.php">Back to Home</a>

<?php
$conn->close();
?>