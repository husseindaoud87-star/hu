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

// Handle POST request to update prices and names
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the request is a JSON request or a normal form submission
    $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

    if (strpos($contentType, 'application/json') === 0) {
        // Handle position updates
        $positions = json_decode(file_get_contents('php://input'), true);
        if ($positions) {
            foreach ($positions as $position) {
                $stmt = $conn->prepare("UPDATE menu_items SET position = ? WHERE id = ?");
                $stmt->bind_param("ii", $position['position'], $position['id']);
                $stmt->execute();
                $stmt->close(); // Close the statement
            }
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    } else {
        // Handle price and name updates
        foreach ($_POST['prices'] as $name => $value) {
            $stmt = $conn->prepare("UPDATE menu_items SET price = ? WHERE name = ?");
            if ($stmt) {
                $stmt->bind_param("ds", $value, $name);
                $stmt->execute();
                $stmt->close(); // Close the statement
            } else {
                echo "Error preparing statement: " . $conn->error; // Error handling
            }
        }

        foreach ($_POST['name'] as $oldName => $newName) {
            $stmt = $conn->prepare("UPDATE menu_items SET name = ? WHERE name = ?");
            if ($stmt) {
                $stmt->bind_param("ss", $newName, $oldName);
                $stmt->execute();
                $stmt->close(); // Close the statement
            } else {
                echo "Error preparing statement: " . $conn->error; // Error handling
            }
        }

        // Redirect after processing
        header("Location: index.php");
        exit();
    }
}

$conn->close();
?>