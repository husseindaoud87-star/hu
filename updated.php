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

// Fetch menu items from the database
$sql = "SELECT * FROM menu_items";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Shop Menu</title>
    <style>
        body {
            font-family: 'Georgia', serif;
            margin: 0;
            padding: 0;
            background: #f0eae4 url("https://tse2.mm.bing.net/th?id=OIP.MfP5MS6LMHD7v9o46SkycgHaHa&pid=Api&P=0&h=220");
            background-size: contain;
            color: #3e2723;
        }

        header {
            background-color: #a1887f;
            color: white;
            padding: 20px;
            text-align: center;
            border-bottom: 5px solid #3e2723;
            position: relative;
        }

        .order-button {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #d32f2f;
            color: white;
            border: none;
            padding: 12px 18px;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
		.name-input {
    font-weight: bold;
    color: #0b0c0b; /* Text color */
    border: 1px solid #ccc; /* Add border for better visibility */
    border-radius: 5px; /* Rounded corners */
    padding: 2px; /* Padding for better spacing */
    width: 100%; /* Full width for better layout */
    margin-bottom: 5px; /* Space between inputs */
}

        .order-button:hover {
            background-color: #100dbe;
        }

        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background-color: #d4cbcb;
            border-radius: 40px;
            box-shadow: 0 4px 20px rgba(209, 12, 12, 0.1);
        }

        h1,
        h2 {
            margin: 0 0 15px;
            font-weight: normal;
            text-align: center;
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
            padding: 12px;
            border: 3px #531313;
            margin: 10px;
            border-radius: 10px;
            background-color: #a70e0ead;
            width: 150px;
        }

        .menu-item img {
            width: 100px;
            height: 100px;
            border-radius: 30px;
            margin-bottom: 5px;
        }

        .price-input {
            font-weight: bold;
            color: #0b0c0b;
            width: 40px;
            border: none;
            background: white;
            text-align: left;
        }

        footer {
            text-align: center;
            padding: 10px;
            background-color: #a1887f;
            color: white;
            position: relative;
            bottom: 0;
            width: 100%;
        }

        @media (max-width: 600px) {
            .order-button {
                padding: 8px 12px;
                font-size: 14px;
            }

            .container {
                padding: 15px;
            }

            h1 {
                font-size: 1.5em;
            }

            h2 {
                font-size: 1.25em;
            }

            .menu-item img {
                width: 40px;
                height: 40px;
            }

            .menu-item {
                width: 100px;
                /* Adjust for mobile */
            }
        }
    </style>
</head>

<body>

    <header>
        <h1>Welcome to Our Coffee Shop</h1>
    </header>
    <div class="container">
        <h2>Menu</h2>
        <form method="POST" action="update_prices.php" enctype="multipart/form-data">
            <div class="menu">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="menu-item" draggable="true" data-id="<?php echo $row['id']; ?>">
                            <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['name']; ?>">
							
                               <input type="text" name="name[<?php echo $row['name']; ?>]"
               value="<?php echo $row['name']; ?>" class="name-input">
                            <input type="text" name="prices[<?php echo $row['name']; ?>]"
                                value="<?php echo number_format($row['price'], 0); ?>" class="price-input" step="0.01">
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No menu items found.</p>
                <?php endif; ?>
            </div>


            <button type="submit" class="order-button">Update Prices</button>
        </form>
    </div>
    <footer>
        <p>Visit us for a cup of joy!</p>
    </footer>
	<script>
    const menu = document.querySelector('.menu');
    let draggedItem = null;

    menu.addEventListener('dragstart', (e) => {
        if (e.target.classList.contains('menu-item')) {
            draggedItem = e.target;
            e.target.style.opacity = 0.5; // Optional: make the dragged item semi-transparent
        }
    });

    menu.addEventListener('dragend', (e) => {
        e.target.style.opacity = ""; // Reset opacity
        draggedItem = null;
    });

    menu.addEventListener('dragover', (e) => {
        e.preventDefault(); // Allow drop
    });

    menu.addEventListener('dragenter', (e) => {
        if (e.target.classList.contains('menu-item')) {
            e.target.style.border = "2px dashed #7b5e57"; // Optional: highlight the drop target
        }
    });

    menu.addEventListener('dragleave', (e) => {
        if (e.target.classList.contains('menu-item')) {
            e.target.style.border = ""; // Reset border
        }
    });

    menu.addEventListener('drop', (e) => {
        e.preventDefault();
        if (e.target.classList.contains('menu-item') && draggedItem) {
            // Insert dragged item before the target item
            menu.insertBefore(draggedItem, e.target);
            e.target.style.border = ""; // Reset border
            updatePositions();
        }
    });

   function updatePositions() {
    const items = document.querySelectorAll('.menu-item');
    const positions = Array.from(items).map((item, index) => ({
        id: item.getAttribute('data-id'),
        position: index
    }));

    // Send positions to your server via fetch
    fetch('update_prices.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(positions)
    })
    .then(response => response.json())
    .then(data => {
        console.log(data); // Handle success or error response
    })
    .catch(error => console.error('Error:', error));
}
</script>
</body>

</html>