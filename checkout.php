<?php
include 'db_config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if ($order_id > 0) {
    // Fetch the order details
    $sql = "SELECT * FROM orders WHERE user_id = ? AND id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $order_id);
    $stmt->execute();
    $order_result = $stmt->get_result();

    if ($order_result->num_rows > 0) {
        $order = $order_result->fetch_assoc();
        // Fetch the products for the order
        $sql = "SELECT * FROM order_items WHERE order_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $items_result = $stmt->get_result();
    } else {
        echo "Order not found.";
        exit();
    }
} else {
    echo "Invalid Order ID.";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swift Buy 🛒 - Checkout</title>
    <link rel="icon" href="favicon.png" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
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
                <li><a href="user_dashboard.php#about" class="text-gray-700 hover:text-indigo-600">About Us</a></li>
                <li><a href="user_dashboard.php#products" class="text-gray-700 hover:text-indigo-600">Product List</a></li>
                <li><a href="user_dashboard.php#contact" class="text-gray-700 hover:text-indigo-600">Contact Us</a></li>
                <li><a href="cart.php" class="text-gray-700 hover:text-indigo-600">View Cart 🛒</a></li>
            </ul>
        </div>
        <div>
            <a href="logout.php" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Logout</a>
        </div>
    </header>

    <!-- Checkout Section -->
    <div class="container mx-auto p-6 mt-10 bg-white shadow-md rounded-lg max-w-4xl">
        <h2 class="text-3xl font-semibold text-center mb-6">Thank You for Your Order!</h2>
        <p class="text-center mb-6">Your order has been placed successfully. Below are the details:</p>

        <!-- Order Summary -->
        <div class="mb-6">
            <h3 class="text-xl font-semibold mb-4">Order Information</h3>
            <p><strong>Order ID:</strong> <?php echo $order['id']; ?></p>
            <p><strong>Total:</strong> <?php echo number_format($order['total'], 2); ?> Taka</p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status'], ENT_QUOTES); ?></p>
            <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($order['payment_method'], ENT_QUOTES); ?></p>
            <p><strong>Delivery Address:</strong> <?php echo htmlspecialchars($order['address'], ENT_QUOTES); ?></p>
        </div>

        <!-- Ordered Items -->
        <h3 class="text-xl font-semibold mb-4">Ordered Products</h3>
        <table class="w-full table-auto mb-6">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">Product Name</th>
                    <th class="px-4 py-2 text-left">Price (Taka)</th>
                    <th class="px-4 py-2 text-left">Quantity</th>
                    <th class="px-4 py-2 text-left">Total (Taka)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($item = $items_result->fetch_assoc()) {
                    $total_price = $item['product_price'] * $item['quantity'];
                    echo "
                    <tr class='border-b'>
                        <td class='px-4 py-2'>" . htmlspecialchars($item['product_name'], ENT_QUOTES) . "</td>
                        <td class='px-4 py-2'>" . number_format($item['product_price'], 2) . "</td>
                        <td class='px-4 py-2'>" . $item['quantity'] . "</td>
                        <td class='px-4 py-2'>" . number_format($total_price, 2) . "</td>
                    </tr>
                    ";
                }
                ?>
            </tbody>
        </table>

        <div class="flex justify-center mt-6">
                <button class="bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700">
                <a href="index.php" class="text-white">Continue Shopping</a>
                </button>
          
        </div>
    </div>
    
    <footer class="mt-4 text-center py-6 bg-gray-200 text-gray-600">
        <p>&copy; 2025 Swift Buy 🛒 | All Rights Reserved</p>
    </footer>
</body>

</html>
