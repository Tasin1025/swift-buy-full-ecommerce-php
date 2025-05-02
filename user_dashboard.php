<?php
// Start the session and check if the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user information from the database
include 'db_config.php';
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swift Buy 🛒</title>
    <link rel="icon" href="favicon.png" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url('banner-tech.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            padding-bottom: 50px;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800">
    <!-- Header -->
    <header class="flex justify-between items-center px-6 py-4 bg-white shadow-md">
        <div class="text-2xl font-bold text-indigo-600">
            <a href="index.php">Swift Buy 🛒</a>
        </div>

        <div>
            <ul class="flex gap-6">
                <li><a href="index.php" class="text-gray-700 hover:text-indigo-600">Home</a></li>
                <li><a href="#about" class="text-gray-700 hover:text-indigo-600">About Us</a></li>
                <li><a href="#products" class="text-gray-700 hover:text-indigo-600">Product List</a></li>
                <li><a href="#contact" class="text-gray-700 hover:text-indigo-600">Contact Us</a></li>
                <li><a href="cart.php" class="text-gray-700 hover:text-indigo-600">View Cart 🛒</a></li>
            </ul>
        </div>
        <div>
            <a href="logout.php" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Logout</a>
        </div>
    </header>

    <!-- Welcome Section -->
    <div class="container mx-auto p-6 mt-10 bg-white shadow-md rounded-lg max-w-4xl">
        <h1 class="text-3xl font-semibold text-center mb-6">Welcome, <?php echo htmlspecialchars($user['full_name'], ENT_QUOTES); ?>!</h1>
        <p class="text-center mb-6">Your one-stop shop for exclusive gadgets and smart accessories.</p>
        <p class="text-center">Swift Buy 🛒 is dedicated to providing the best and most cutting-edge gadgets and accessories. Shop with confidence and enjoy top-tier products for modern living.</p>
    </div>

    <!-- User Info Section -->
    <div class="container mx-auto p-6 mt-10 bg-white shadow-md rounded-lg max-w-4xl">
        <h2 class="text-xl font-semibold mb-4 text-center">Your Information</h2>
        <div class="text-center mb-4">
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email'], ENT_QUOTES); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone'], ENT_QUOTES); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($user['address'], ENT_QUOTES); ?></p>
            <p><strong>Profile Picture:</strong> <img src="<?php echo htmlspecialchars($user['profile_picture'], ENT_QUOTES); ?>" alt="Profile Picture" class="w-24 h-24 rounded-full mx-auto"></p>
        </div>
        <div class="text-center mt-4">
            <a href="profile.php" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Edit Profile</a>
        </div>
    </div>

    <!-- About Us Section -->
    <!-- <div id="about" class="container mx-auto p-6 bg-white shadow-md rounded-lg mt-10 max-w-4xl">
        <h1 class="text-3xl font-semibold text-center mb-6">About Us</h1>
        <p class="text-center">Swift Buy 🛒 is dedicated to providing the best and most cutting-edge gadgets and accessories. Shop with confidence and enjoy top-tier products for modern living.</p>
    </div> -->

    <!-- Products Section -->
    <div id="products" class="container mx-auto p-6 mt-10 bg-white shadow-md rounded-lg max-w-4xl">
    <h2 class="text-3xl font-semibold text-center mb-6">Our Bestsellers</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
        <?php
        // Fetch products from the database
        $sql = "SELECT * FROM products";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '
                    <div class="product bg-white shadow-md rounded-lg overflow-hidden text-center">
                        <img src="' . $row['image'] . '" alt="' . htmlspecialchars($row['name'], ENT_QUOTES) . '" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold">' . htmlspecialchars($row['name'], ENT_QUOTES) . '</h3>
                            <p class="text-indigo-600 font-bold">' . number_format($row['price'], 2) . ' Taka</p>
                            <form method="POST" action="add_to_cart.php">
                                <input type="hidden" name="product_name" value="' . htmlspecialchars($row['name'], ENT_QUOTES) . '">
                                <input type="hidden" name="product_price" value="' . $row['price'] . '">
                                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                ';
            }
        } else {
            echo "<p>No products found!</p>";
        }
        ?>
    </div>
</div>

</div>

    </div>

    <!-- Contact Us Section -->
    <div id="contact" class="container mx-auto p-6 mt-10 bg-white shadow-md rounded-lg max-w-4xl">
        <h2 class="text-3xl font-semibold text-center mb-6">Contact Us</h2>
        <form action="noaction.php" method="POST">
            <div class="form-group mb-4">
                <input type="text" name="name" placeholder="Enter your name" required class="w-full p-3 border border-gray-300 rounded-md">
            </div>
            <div class="form-group mb-4">
                <input type="email" name="email" placeholder="Enter your Email ID" required class="w-full p-3 border border-gray-300 rounded-md">
            </div>
            <div class="form-group mb-4">
                <input type="text" name="phone" placeholder="Enter your Phone Number" required class="w-full p-3 border border-gray-300 rounded-md">
            </div>
            <div class="form-group mb-4">
                <textarea name="message" placeholder="Enter your Message" required class="w-full p-3 border border-gray-300 rounded-md"></textarea>
            </div>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-3 rounded-md text-lg hover:bg-indigo-700">Submit</button>
        </form>
    </div>

</body>

</html>
