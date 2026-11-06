<?php
// Database connection details
$host = 'localhost';
$db = 'coffeeshop'; // Change to your database name
$user = 'root'; // Your database username
$pass = ''; // Your database password

// Create a connection
$conn = new mysqli($host, $user, $pass, $db);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the username and password from the form
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password']; // Do not escape this
    $redirectPage = $_POST['redirect']; // Get the chosen redirect page

    // Prepare the SQL statement
    $sql = "SELECT * FROM user WHERE username = '$username' AND password='$password'";
    $result = $conn->query($sql);

    // Check if the user exists
    if ($result->num_rows > 0) {
        // User exists, redirect based on selected option
        header("Location: $redirectPage");
        exit();
    } else {
        // Invalid username or password
        echo "Invalid username or password.";
    }
}

$conn->close();
?>