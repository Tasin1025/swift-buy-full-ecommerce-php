<?php
// Start the session
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch user information from the database
include 'db_config.php';
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT full_name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle product deletion
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);

    // Delete product from the database
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        echo "<script>alert('Product deleted successfully!');</script>";
        header("Location: admin_dashboard.php#products"); // Redirect to products section
        exit();
    } else {
        echo "<script>alert('Error deleting product.');</script>";
    }
    $stmt->close();
}

// Handle product upload form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload_product'])) {
    $product_name = htmlspecialchars($_POST['product_name']);
    $price = htmlspecialchars($_POST['price']);
    $description = htmlspecialchars($_POST['description']);
    $stock = intval($_POST['stock']);
    
    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "product_images/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        
        // Check if file is a valid image
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Insert product into the database
                $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, image) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("ssdss", $product_name, $description, $price, $stock, $target_file);

                if ($stmt->execute()) {
                    echo "<script>alert('Product uploaded successfully!');</script>";
                } else {
                    echo "<script>alert('Error uploading product.');</script>";
                }
            } else {
                echo "<script>alert('Error uploading image.');</script>";
            }
        } else {
            echo "<script>alert('Invalid image file type.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swift Buy 🛒 - Admin Dashboard</title>
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
                <li><a href="admin_dashboard.php" class="text-gray-700 hover:text-indigo-600">Admin Home</a></li>
                <li><a href="view_orders.php" class="text-gray-700 hover:text-indigo-600">View Orders</a></li>
                
            </ul>
        </div>
        <div>
            <a href="logout.php" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Logout</a>
        </div>
    </header>

    <!-- Admin Dashboard Welcome Section -->
    <div class="container mx-auto p-6 mt-10 bg-white shadow-md rounded-lg max-w-4xl">
        <h1 class="text-3xl font-semibold text-center mb-6">Welcome, Admin <?php echo htmlspecialchars($user['full_name'], ENT_QUOTES); ?>!</h1>
        <p class="text-center mb-6">Manage products, view orders, and perform other administrative tasks.</p>
    </div>

    <!-- Product Upload Section -->
    <div id="products" class="container mx-auto p-6 mt-10 bg-white shadow-md rounded-lg max-w-4xl">
        <h2 class="text-3xl font-semibold mb-6 text-center">Upload New Product</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="product_name" class="block text-lg font-semibold">Product Name:</label>
                <input type="text" id="product_name" name="product_name" required class="w-full p-3 border border-gray-300 rounded-md">
            </div>

            <div class="mb-4">
                <label for="description" class="block text-lg font-semibold">Product Description:</label>
                <textarea id="description" name="description" required class="w-full p-3 border border-gray-300 rounded-md"></textarea>
            </div>

            <div class="mb-4">
                <label for="price" class="block text-lg font-semibold">Price (Taka):</label>
                <input type="number" id="price" name="price" required class="w-full p-3 border border-gray-300 rounded-md">
            </div>

            <div class="mb-4">
                <label for="stock" class="block text-lg font-semibold">Stock:</label>
                <input type="number" id="stock" name="stock" required class="w-full p-3 border border-gray-300 rounded-md">
            </div>

            <div class="mb-4">
                <label for="image" class="block text-lg font-semibold">Product Image:</label>
                <input type="file" id="image" name="image" accept="image/*" required class="w-full p-3 border border-gray-300 rounded-md">
            </div>

            <button type="submit" name="upload_product" class="bg-indigo-600 text-white px-6 py-3 rounded-md text-lg hover:bg-indigo-700">Upload Product</button>
        </form>
    </div>

    <!-- Manage Products Section -->
    <div id="products" class="container mx-auto p-6 mt-10 bg-white shadow-md rounded-lg max-w-4xl">
        <h2 class="text-3xl font-semibold mb-6 text-center">Manage Products</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            <?php
            // Fetch products from the database
            $sql = "SELECT * FROM products";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '
                        <div class="bg-white shadow-md rounded-lg overflow-hidden text-center">
                             <!-- Fixed image size with object-cover to maintain aspect ratio -->
                             <img src="' . $row['image'] . '" alt="' . htmlspecialchars($row['name'], ENT_QUOTES) . '" class="w-full h-48 object-cover">
                            <h3 class="text-lg font-semibold">' . htmlspecialchars($row['name'], ENT_QUOTES) . '</h3>
                            <p class="text-indigo-600 font-bold">' . number_format($row['price'], 2) . ' Taka</p>
                            <div class="p-4">
                                <!-- Delete button -->
                                <a href="admin_dashboard.php?delete_id=' . $row['id'] . '" class="bg-red-600 text-white px-6 py-2 rounded-md text-lg hover:bg-red-700">Delete</a>
                            </div>
                        </div>
                    ';
                }
            } else {
                echo "<p class='text-gray-600'>No products found!</p>";
            }
            ?>
        </div>
    </div>

    <footer class="text-center py-6 bg-gray-200 text-gray-600">
        <p>&copy; 2025 Swift Buy 🛒 | All Rights Reserved</p>
    </footer>

</body>

</html>
