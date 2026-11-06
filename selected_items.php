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

// Increment the counter for the specified item
if (isset($_POST['id'])) {
    $itemId = intval($_POST['id']);
    $sql = "UPDATE menu_items SET click_count = click_count + 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $itemId);
    $stmt->execute();
    $stmt->close();
}

// Initialize total cost and selected items
$totalCost = 0;
$selected_items = [];

// Check if any items were selected
if (isset($_POST['selected_items'])) {
    $selected_ids = $_POST['selected_items'];
    $ids = implode(',', array_map('intval', $selected_ids));

    // Fetch selected items from the database
    $sql = "SELECT * FROM menu_items WHERE id IN ($ids)";
    $result = $conn->query($sql);

    // Calculate total cost and store selected items
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $selected_items[] = $row;
            $totalCost += $row['price']; // Assuming 'price' is in L.L
        }
    }
} else {
    echo "No items selected.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selected Items</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0eae4;
            color: #3e2723;
        }

        header {
            background-color: #7b5e57;
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background-color: #f5e3e3;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .menu {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .menu-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 15px;
            margin: 10px;
            border: 2px solid #531313;
            border-radius: 10px;
            background-color: #eae0e0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s;
            width: 150px;
        }

        .menu-item:hover {
            transform: scale(1.05);
        }

        .menu-item img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .price-input {
            font-weight: bold;
            color: #0b0c0b;
        }

        .total-cost {
            margin-top: 20px;
            font-size: 1.5em;
            text-align: center;
            color: #d32f2f; /* Red for emphasis */
        }

        footer {
            text-align: center;
            padding: 15px;
            background-color: #7b5e57;
            color: white;
            position: static;
            bottom: 0;
            width: 100%;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>
    <header>
        <h1>Your Selected Items</h1>
    </header>
    <div class="container">
        <h2>Items You Chose</h2>
        <div class="menu">
            <?php if (!empty($selected_items)): ?>
                <?php foreach ($selected_items as $row): ?>
                    <div class="menu-item">
                        <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['name']; ?>">
                        <span><?php echo $row['name']; ?></span>
                        <span class="price-input"><?php echo number_format($row['price'], 0); ?> L.L</span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No items found.</p>
            <?php endif; ?>
        </div>
        
        <!-- Total Cost Display -->
        <div class="total-cost">
            Total Cost: <?php echo number_format($totalCost, 0); ?> L.L
        </div>
    </div>
    <footer>
        <p>Thank you for visiting!</p>
    </footer>
</body>

</html>

<?php
$conn->close();
?>