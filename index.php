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
            background-size: cover;
            /* Changed to cover for better background display */
            color: #3e2723;
        }

        header {
            background-color: #7b5e57;
            /* Darker and richer color for header */
            color: white;
            padding: 30px;
            /* Increased padding for a bolder look */
            text-align: center;
            border-bottom: 5px solid #3e2723;
            position: relative;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            /* Subtle shadow for depth */
        }

        .order-button {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #d32f2f;
            color: white;
            border: none;
            padding: 12px 20px;
            /* Increased padding for button */
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .order-button:hover {
            background-color: #ff5722;
            /* Different hover color for better contrast */
        }

        .container {
            max-width: 100%;
            margin: 30px auto;
            padding: 20px;
            background-color: #f5e3e3;
            /* Lighter background for the container */
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
            /* Stack items vertically */
            align-items: center;
            /* Center align items */
            padding: 15px;
            /* Slightly increased padding */
            border: 3px solid #531313;
            /* Solid border */
            margin: 10px;
            border-radius: 10px;
            background-color: #eae0e0;
            /* Lighter background for menu items */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            /* Subtle shadow for depth */
            transition: transform 0.2s;
            /* Smooth hover effect */
        }

        .menu-item:hover {
            transform: scale(1.05);
            /* Scale effect on hover */
        }

        .menu-item img {
            width: 90px;
            /* Slightly larger for better visibility */
            height: 90px;
            /* Maintain aspect ratio */
            border-radius: 50%;
            /* Change to circular images */
            margin-bottom: 10px;
            /* Space below image */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            /* Shadow for images */
        }

        .name-input,
        .price-input {
            font-weight: bold;
            color: #0b0c0b;
            width: 100%;
            /* Full width for better layout */
            border: 1px solid #ccc;
            /* Add border for visibility */
            border-radius: 5px;
            /* Rounded corners */
            padding: 5px;
            /* Padding for better spacing */
            margin-bottom: 5px;
            /* Space between inputs */
            text-align: center;
            /* Center text in inputs */
        }

        footer {
            text-align: center;
            padding: 15px;
            /* Increased padding for footer */
            background-color: #7b5e57;
            /* Match header color */
            color: white;
            position: static;
            bottom: 0;
            width: 100%;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.2);
            /* Shadow for depth */
        }
		.button-wrapper {
    display: flex;               /* Use flexbox for centering */
    justify-content: center;     /* Center horizontally */
    margin-top: 20px;           /* Space above the button */
}
		.view-bill-button {
    background-color: #ff9800; /* Orange background color */
    color: white;              /* White text color */
    border: none;              /* No border */
    border-radius: 25px;      /* Fully rounded corners */
    padding: 12px 30px;       /* Padding for size */
    font-size: 18px;          /* Larger font size */
    font-weight: bold;         /* Bold text */
    cursor: pointer;           /* Pointer cursor on hover */
    transition: all 0.3s ease; /* Smooth transition for hover effects */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Drop shadow for depth */
    margin-top: 20px;         /* Space above the button */
}

.view-bill-button:hover {
    background-color: #f57c20; /* Darker shade on hover */
    transform: translateY(-2px); /* Slight lift effect on hover */
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3); /* Enhanced shadow on hover */
}

.view-bill-button:active {
    transform: translateY(0); /* Reset lift effect when clicked */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); /* Reduce shadow on click */
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
                width: 50px;
                /* Adjust for smaller screens */
                height: 50px;
            }

            .menu-item {
                width: 120px;
                /* Adjust for mobile */
            }
        }
    </style>
</head>

<body>

    <header>
        <h1>Welcome to Our Coffee Shop</h1>
        <a href="login.php" class="order-button">Admin</a>
    </header>
    <div class="container">
        <h2>Menu</h2>
		<form action="selected_items.php" method="POST">
        <div class="menu">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>

                    <div class="menu-item" onclick="incrementCounter(<?php echo $row['id']; ?>)">
                        <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['name']; ?>">

                        <span><?php echo $row['name']; ?></span>
                        <span class="price-input"><?php echo number_format($row['price'], 0); ?>L.L</span>
						<input type="checkbox" name="selected_items[]" value="<?php echo $row['id']; ?>">
                    </div>


                <?php endwhile; ?>
            <?php else: ?>
                <p>No menu items found.</p>
            <?php endif; ?>
        </div>
    </div>
	<div class="button-wrapper">
	  <button type="submit" class="view-bill-button">View your bill</button>
	  </div>
</form>

    
	<script>
function incrementCounter(itemId) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "increment_counter.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log("Counter incremented for item ID: " + itemId);
            // Optionally, you can update the UI or display a message here
        }
    };
    xhr.send("id=" + itemId);
}
</script>
</body>
<footer>
        <p>Visit us for a cup of joy!</p>
    </footer>

</html>

<?php
$conn->close();
?>