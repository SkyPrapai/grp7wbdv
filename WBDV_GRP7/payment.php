<?php
session_start();
$host = "localhost";
$username = "root";
$password = "";
$dbname = "bookstore_db";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize session variables if set
$firstname = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : '';
$lastname = isset($_SESSION['lastname']) ? $_SESSION['lastname'] : '';
$address = isset($_SESSION['address']) ? $_SESSION['address'] : '';
$phone_number = isset($_SESSION['phone_number']) ? $_SESSION['phone_number'] : '';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

// Handle login submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $_SESSION['firstname'] = $_POST['firstname'];
    $_SESSION['lastname'] = $_POST['lastname'];
    $_SESSION['address'] = $_POST['address'];
    $_SESSION['phone_number'] = $_POST['phone_number'];
    $email = $username;
    
    // Retrieve user from the database
    $sql = "SELECT id, firstname, lastname, email, phone_number, address, password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify password (assuming md5 is used)
        if (md5($password) === $row['password']) {
            $_SESSION['firstname'] = $row['firstname'];
            $_SESSION['lastname'] = $row['lastname'];
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['phone_number'] = $row['phone_number'];
            $_SESSION['address'] = $row['address'];
            $_SESSION['email'] = $row['email'];
            
            header("Location: homepage.php");
            exit;
        } else {
            $message = "Invalid password!";
        }
    } else {
        $message = "No user found with that username!";
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy(); // Destroy the session to log the user out
    header("Location: homepage.php"); // Redirect to homepage
    exit();
}

// Add to cart if requested
if (isset($_POST['add_to_cart'])) {
    if (isset($_SESSION['firstname'])) {
        $book_id = $_POST['book_id']; // Add this line to get the book ID
        // Fetch the book details using the book ID
        $stmt_book = $conn->prepare("SELECT book_title, book_author, price FROM books WHERE id = ?");
        $stmt_book->bind_param("i", $book_id);
        $stmt_book->execute();
        $stmt_book->bind_result($book_title, $book_author, $book_price);
        $stmt_book->fetch();
        $stmt_book->close();

        $book = array(
            'id' => $book_id, // Include the book ID in the cart array
            'title' => $book_title,
            'author' => $book_author,
            'price' => floatval(str_replace(',', '', $book_price)),
            'quantity' => 1
        );

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }

        // Check if the book is already in the cart
        $found = false;
        foreach ($_SESSION['cart'] as &$cart_item) {
            if ($cart_item['id'] == $book_id) {
                $cart_item['quantity'] += 1; // Increment quantity
                $found = true;
                break;
            }
        }

        // If not found, add as a new item with quantity 1
        if (!$found) {
            $_SESSION['cart'][] = $book;
        }
    } else {
        header("Location: login.php");
        exit();
    }
    header("Location: homepage.php");
    exit();
}

// Clear cart if requested
if (isset($_POST['clear_cart'])) {
    if (isset($_SESSION['firstname'])) {
        if (isset($_SESSION['cart'])) {
            unset($_SESSION['cart']);
        }
    } else {
        header("Location: login.php");
        exit();
    }
    header("Location: payment.php");
    exit();
}

if (isset($_POST['decrease_quantity']) || isset($_POST['increase_quantity'])) {
    $book_id = $_POST['book_id'];

    foreach ($_SESSION['cart'] as &$cart_item) {
        if ($cart_item['id'] == $book_id) {
            if (isset($_POST['decrease_quantity'])) {
                $cart_item['quantity'] -= 1; 
            } elseif (isset($_POST['increase_quantity'])) {
                $cart_item['quantity'] += 1; 
            }

            if ($cart_item['quantity'] <= 0) {
                $cart_item['quantity'] = 0; // Set to zero but do not unset here
            }
            break; // Break after modifying the item
        }
    }

    // Clean up the cart
    $new_cart = array();
    foreach ($_SESSION['cart'] as $item) {
        if ($item['quantity'] > 0) {
            $new_cart[] = $item; // Only keep items with quantity > 0
        }
    }
    $_SESSION['cart'] = $new_cart; // Assign the cleaned array back to session
}




// Redirect to login if user is not logged in and accessing other sections
if (!isset($_SESSION['firstname'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['query'])) {
    $search = strtolower($_POST['query']); // Convert query to lowercase for case-insensitive matching
    $search = mysqli_real_escape_string($conn, $search); // Sanitize input to prevent SQL injection

    // SQL query to search for books by title or author
    $sql = "SELECT book_title, book_author, book_price, book_image FROM books WHERE LOWER(book_title) LIKE '%$search%' OR LOWER(book_author) LIKE '%$search%'";

    // Execute the query
    $result = mysqli_query($conn, $sql);

    // Check if any results were returned
    if (mysqli_num_rows($result) > 0) {
        echo '<div class="search-results">';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="search-result">';
            echo '<img src="' . $row['book_image'] . '" alt="' . $row['book_title'] . '" class="result-image" style="height: 200px;">'; // Display book image
            echo '<div class="result-info">';
            echo '<strong class="result-title">' . $row['book_title'] . '</strong><br>';
            echo '<em class="result-author">' . $row['book_author'] . '</em><br>';
            echo '<span class="result-price">Price: ₱' . $row['book_price'] . '</span>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo '<p>No results found</p>';
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Uplift Bookstore</title>
	<link rel="icon" href="460509624_1463840257655227_6223856608048021337_n.png" type="image/png">
    <style>
          body {
            font-family: 'Georgia', serif;
            background-color: #f4f0e6;
            background-image: url('https://64.media.tumblr.com/c25d3b2f64c96184584b831fba6bb0e2/tumblr_oyfsbzUOey1r9co7bo1_1280.gifv');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            overflow x: hidden; /* Prevent vertical scrolling on the body */
        }
 
             header {
            background-color: rgba(74, 60, 49, 0.7);
            backdrop-filter: blur(10px);
            color: white;
            padding: 10px 0;
            display: flex;
            align-items: center;
            height: 80px;
            width: 100%;
            box-sizing: border-box;
        }

       
           header h3 {
            font-family: 'Georgia', serif;
            color: white;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            flex-grow: 0.155;
            letter-spacing: 0.5px;
        }
        
        
		
        .search-bar-container {
            display: flex;
            justify-content: center;
            flex-grow: 1;
            padding-right: 15px;
        }

       .search-bar-container {
            display: flex;
            justify-content: center;
            flex-grow: 1;
            padding-right: 15px;
        }

        .search-bar {
            width: 100%;
            max-width: 600px;
            display: flex;
            align-items: center;
        }

        .search-bar input[type="text"] {
            width: 100%;
            padding: 10px;
            border-radius: 5px 0 0 5px;
            border: 1px solid #ccc;
            font-size: 14px;
            border-right: none;
        }

        .search-bar button {
            background-color: white;
            border: 1px solid #ccc;
            padding: 6.5px;
            cursor: pointer;
            border-radius: 0 5px 5px 0;
        }

        .search-bar button img {
            width: 20px;
            height: 20px;
        }
		
		.header-buttons {
            display: flex;
            align-items: center;
        }

        .header-buttons img {
            width: 50px;
             margin-left: 10px;
			margin-right: 20px;
            cursor: pointer;
        }
		
		.headerlogo{
            display: flex;
            align-items: center;
        }
		
		.headerlogo img{
            margin-left: 10px;
			margin-right: 20px;
            cursor: pointer;
        }
		
		.categories-container {
            display: flex;
            justify-content: center;
            padding: 10px 0;
            background-color: rgb(245, 245, 220);
            border-bottom: 2px solid #4a3c31;
            margin: 0;
            width: 100%;
            box-sizing: border-box;
            text-align: center;
            flex-wrap: wrap;
        }

        .category-link {
            margin: 5px 15px;
            font-size: 16px;
            color: #4a3c31;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .category-link:hover {
            color: #6b5446;
		}

        .cart-container {
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 10px;
        }

        .categories-container {
            display: flex;
            justify-content: center;
            padding: 10px 0;
            background-color: rgb(245, 245, 220);
            border-bottom: 2px solid #4a3c31;
            margin: 0;
            width: 100%;
            box-sizing: border-box;
            text-align: center;
            flex-wrap: wrap;
        }

        .category-link {
            margin: 5px 15px;
            font-size: 16px;
            color: #4a3c31;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .category-link:hover {
            color: #6b5446;
        }
        .cart-container {
    background-color: rgba(255, 255, 255, 0.9);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 45%; /* Adjust cart container width */
    padding: 20px;
    border-radius: 10px;
}
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        table th {
            background-color: #f2f2f2;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .total {
            text-align: right;
            font-size: 18px;
            margin-top: 10px;
        }
        .buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        .clear-cart-btn {
            background-color: #ff6f61;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .clear-cart-btn:hover {
            background-color: #ff4f41;
        }
        .checkout-form {
    background-color: rgba(255, 255, 255, 0.8);
    padding: 20px;
    border-radius: 8px;
    width: 45%; /* Adjust form width */
    flex-grow: 1;
}
        .checkout-form input, .checkout-form select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        .checkout-form button {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .checkout-form button:hover {
            background-color: #45a049;
        }
		   footer {
            background-color: #4a3c31;
            color: white;
            padding: 0.5px 15px;
            text-align: center;
            margin-top: 280px;
        }

        .social-media {
            margin-top: 1px;
        }

        .social-media a {
            margin: 0 3px;
            display: inline-block;
        }

        footer p {
            margin-top: 5px;
        }

        .social-media img {
            width: 35px;
            height: 35px;
            transition: transform 0.3s ease;
        }

        .social-media img:hover {
            transform: scale(1.2); /* Increase size on hover */
        }
		
		.username-message {
    font-size: 16px;
    color: white;
    margin-right: 10px; /* Space between username and logout button */
}

.logout-button {
    font-size: 14px;
    color: white;
    background-color: #4a3c31;
    border: none;
    padding: 6px 12px;  /* Adjust padding for a better look */
    cursor: pointer;
    border-radius: 5px;
}

.logout-button:hover {
    background-color: #6b5446;
}

    .content {
    display: flex;
    justify-content: space-between; /* Ensure elements are spaced out side by side */
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
	gap: 20px;
}

 .total-container {
        
            padding: 0.00001px;

        }
		 .total-container h3 {
           text-align:right;
        }
		
		
.search-results-container {
    max-height: 300px; /* Adjust as needed */

    position: relative;
    z-index: 9999; /* High enough to appear on top of other elements */
    background-color: white;
    border: 1px solid #ddd;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
}


.search-result {
    padding: 5px 10px;
    background-color: #fff;
    border-bottom: 1px solid #ddd;
    text-align: left;
    height: 40px; /* Set a fixed height for uniformity */
    display: flex;
    align-items: center; /* Center content vertically */
}


.search-result a {
    color: #333;
    text-decoration: none;
    display: inline-block;
}

.search-result a:hover {
    color: #007bff;
}
.search-result:hover {
    transform: translateY(-2px); /* Slight lift effect on hover */
}

.result-title a {
    font-size: 12px;
    font-weight: 600;
    color: #333;
    text-decoration: none;
}

.result-title a:hover {
    color: #007bff;
}
    </style>
</head>
<body>
<header>
    <div class="headerlogo">
        <img src="460509624_1463840257655227_6223856608048021337_n.png" alt="Logo" height="108px">
    </div>
    <h3>UPLIFT PAGE BOOKSTORE</h3>
    <div class="search-bar-container">
        <form action="fetch_search_results.php" method="GET" class="search-bar" id="searchForm">
    <input type="text" name="query" id="searchInput" placeholder="Search for books...">
</form>

<!-- Search Results Container -->
<div id="searchResults" style="position: absolute; background-color: white; margin-top: 35px; max-height: 300px; overflow-y: auto; z-index: 9999; border: 1px solid #ddd; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
    <!-- Results will appear here -->
</div>
    </div>
    <div class="main-content">
        <?php if (isset($_SESSION['firstname'])): ?>
            <span class="username-message">Hello, <?php echo htmlspecialchars($_SESSION['firstname']); ?>!</span>
            <a href="homepage.php?logout=true"><button class="logout-button">Logout</button></a>
        <?php else: ?>
            <span class="username-message">Hello, Guest!</span>
            <a href="login.php"><button class="logout-button">Login</button></a>
        <?php endif; ?>
    </div>
    <div class="header-buttons">
        <a href="profile.php"><img src="https://raw.githubusercontent.com/SkyPrapai/oopr/main/login-removebg-preview.png" alt="Login" height="58px"></a>
        
        <img src="https://raw.githubusercontent.com/SkyPrapai/oopr/main/cart-removebg-preview.png" alt="Cart" height="51px">
        <span class="cart-count">
            <?php 
            $cart_count = 0;
            if (isset($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $cart_item) {
                    $cart_count += $cart_item['quantity'];
                }
            }
            echo $cart_count; 
            ?>
        </span>
    
    </div>
</header>
<div class="categories-container">
    <a href="homepage.php" class="category-link">Home</a>
    <a href="new.php" class="category-link">New Arrivals</a>
    <a href="sale.php" class="category-link">Sale!</a>
    <a href="best.php" class="category-link">Best Seller</a>
    <a href="faq.php" class="category-link">FAQs</a>
</div>
<div class="content">
    <div class="cart-container">
    <h1>Your Cart</h1>
    <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
    <?php 
    $total_price = 0;
    foreach ($_SESSION['cart'] as $book): 
        $subtotal = $book['price'] * $book['quantity'];
        $total_price += $subtotal;
    ?>
        <tr>
            <td><?php echo htmlspecialchars($book['title']); ?></td>
            <td><?php echo htmlspecialchars($book['author']); ?></td>
            <td>
                <form method="post" action="payment.php" style="display: inline;">
                    <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                    <button type="submit" name="decrease_quantity" class="quantity-btn">-</button>
                    <?php echo htmlspecialchars($book['quantity']); ?>
                    <button type="submit" name="increase_quantity" class="quantity-btn">+</button>
                </form>
            </td>
            <td>₱<?php echo htmlspecialchars(number_format($book['price'], 2)); ?></td>
            <td>₱<?php echo htmlspecialchars(number_format($subtotal, 2)); ?></td>
        </tr>
    <?php endforeach; ?>
</tbody>

        </table>
        <div class="total-container">
            <h3>Total Price: ₱<?php echo htmlspecialchars(number_format($total_price, 2)); ?></h3>
        </div>
        <div class="buttons">
            <form method="post" action="payment.php">
                <button type="submit" name="clear_cart" class="clear-cart-btn">Clear Cart</button>
            </form>
        </div>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</div>



    <div class="checkout-form">
        <h2>Enter Shipping Details</h2>
        <form action="process_payment.php" method="POST">
            <label for="name">First Name:</label>
            <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>" required readonly>
            <label for="lastname">Last Name:</label>
            <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>" required readonly>
			
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($address); ?>" required readonly>
            <label for="phone_number">Contact Number:</label>
            <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>" required readonly>
            <label for="payment_method">Payment Method:</label>
            <select name="payment_method" required>
                <option value="" disabled selected>Select Payment Method</option>
                <option value="PayPal">PayPal</option>
                <option value="Gcash">Gcash</option>
                <option value="Cash on Delivery">Cash on Delivery</option>
            </select>
            <input type="hidden" name="total_price" value="<?php echo number_format($total_price, 2); ?>">
            <button type="submit">Proceed to Payment</button>
        </form>
    </div>
</div>

<script>

searchInput.addEventListener('input', function () {
    const formData = new FormData(searchForm);

    fetch('fetch_search_results.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        searchResults.innerHTML = data; // Display the results
    });
});

$(document).on('click', '.search-result a', function() {
    // Your click action here
});

</script>
<footer>
    <div class="social-media">
        <a href="https://facebook.com" target="_blank">
            <img src="https://raw.githubusercontent.com/SkyPrapai/oopr/main/fb-removebg-preview.png" alt="Facebook">
        </a>
        <a href="https://twitter.com" target="_blank">
            <img src="https://raw.githubusercontent.com/SkyPrapai/oopr/main/5ec14aa8686c6761c75b20a164a8afc2-removebg-preview.png" alt="Twitter">
        </a>
        <a href="https://instagram.com" target="_blank">
            <img src="https://raw.githubusercontent.com/SkyPrapai/oopr/main/images-removebg-preview.png" alt="Instagram">
        </a>
    </div>
    <p>&copy; 2024 Online Bookstore. All rights reserved.</p>
</footer>
</body>
</html>