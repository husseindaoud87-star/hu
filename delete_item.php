<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Menu Item</title>
    <style>
        body {
            font-family: 'Georgia', serif;
            margin: 0;
            padding: 20px;
            background-color: #f0eae4;
        }

        form {
            max-width: 400px;
            margin: auto;
            background: #d4cbcb;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(209, 12, 12, 0.1);
        }

        select,
        button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
    </style>
</head>

<body>

    <h1>Delete a Menu Item</h1>
    <form method="POST" action="delete_item_action.php">
        <select name="name" required>
            <option value="">Select Item</option>
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

            // Fetch menu items
            $result = $conn->query("SELECT name FROM menu_items");
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . htmlspecialchars($row['name']) . '">' . htmlspecialchars($row['name']) . '</option>';
            }

            // Close the connection
			
            $conn->close();
            ?>
        </select>
        <button type="submit">Delete Item</button>
	
    </form>

</body>

</html>