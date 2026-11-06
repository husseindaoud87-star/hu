<?php
// Database connection details
$host = 'localhost';
$db = 'coffeeshop';
$user = 'root'; // your database username
$pass = ''; // your database password

// Create a connection
$conn = new mysqli($host, $user, $pass, $db);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update prices if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['prices'] as $id => $price) {
        $price = $conn->real_escape_string($price);
        $sql = "UPDATE menu_items SET price = '$price' WHERE id = $id";
        $conn->query($sql);
    }
}

// Fetch menu items from the database
$sql = "SELECT * FROM menu_items";
$result = $conn->query($sql);
header("Location:index.php");
?>
