<?php
// Database connection details
$host = 'localhost';
$db = 'coffeeshop';
$user = 'root'; // your database username
$pass = ''; // your database password

// Create a connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$name = $_POST['name'];
$price = $_POST['price'];

// Check the upload method
if ($_POST['upload_method'] === 'file' && isset($_FILES['image_file']) && $_FILES['image_file']['error'] == UPLOAD_ERR_OK) {
    // Handle file upload
    $imageTmpName = $_FILES['image_file']['tmp_name'];
    $imageName = basename($_FILES['image_file']['name']);
    $uploadDir = 'uploads/'; // Directory to store images

    // Create the upload directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $uploadFile = $uploadDir . $imageName;

    // Move the uploaded file to the designated directory
    if (move_uploaded_file($imageTmpName, $uploadFile)) {
        $image_url = $uploadFile; // Set the image URL to the path of the uploaded file
    } else {
        die("Error moving uploaded file.");
    }
} elseif ($_POST['upload_method'] === 'url') {
    // Use the image URL
    $image_url = $_POST['image_url'];
} else {
    die("No valid image uploaded.");
}

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO menu_items (name, price, image_url) VALUES (?, ?, ?)");
$stmt->bind_param("sds", $name, $price, $image_url); // 's' for string, 'd' for double

// Execute the statement
if ($stmt->execute()) {
    echo "New item added successfully!";
} else {
    echo "Error: " . $stmt->error;
}

// Close connections
$stmt->close();
$conn->close();

// Redirect back to menu (optional)
header("Location: index.php"); // Change to the appropriate redirect URL
exit();
?>