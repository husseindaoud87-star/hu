<?php
// Database connection details
$host = 'localhost';
$db = 'coffeeshop';
$user = 'root'; // Your database username
$pass = ''; // Your database password

// Create a connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle POST request to delete the menu item
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'])) {
    $name = $_POST['name'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("DELETE FROM menu_items WHERE name = ?");
    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("s", $name);
        $stmt->execute();

        // Check if the item was deleted
        if ($stmt->affected_rows > 0) {
            // Optional: You could set a success message here
        } else {
            // Optional: Handle case where no item was deleted
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    // Redirect to index.php after deletion
    header("Location: index.php");
    exit();
}

$conn->close();
?>