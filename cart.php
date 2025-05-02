<?php
include 'db_config.php';

// Handle item deletion from the cart
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: cart.php");
        exit();
    } else {
        echo "Error deleting item.";
    }
    $stmt->close();
}

// Handle clearing the cart
if (isset($_GET['action']) && $_GET['action'] === 'clear') {
    $sql = "DELETE FROM cart";

    if ($conn->query($sql)) {
        header("Location: cart.php");
        exit();
    } else {
        echo "Error clearing cart.";
    }
}

// Update quantity if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity'])) {
    $id = intval($_POST['id']);
    $quantity = intval($_POST['quantity']);

    // Ensure the quantity is a valid number
    if ($quantity > 0) {
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $stmt->bind_param("ii", $quantity, $id);

        if ($stmt->execute()) {
            header("Location: cart.php");
            exit();
        } else {
            echo "Error updating quantity.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swift Buy 🛒 - Cart</title>
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

    <!-- Cart Section -->
    <div class="container mx-auto p-6 mt-10 bg-white shadow-md rounded-lg max-w-4xl">
        <h2 class="text-3xl font-semibold text-center mb-6">Your Cart</h2>
        
        <form method="POST" action="checkout.php">
            <!-- Cart Items Table -->
            <table class="w-full table-auto mb-6">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 text-left">Product Name</th>
                        <th class="px-4 py-2 text-left">Price (Taka)</th>
                        <th class="px-4 py-2 text-left">Quantity</th>
                        <th class="px-4 py-2 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;

                    // Fetch cart products
                    $sql = "SELECT * FROM cart";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $total += $row['product_price'] * $row['quantity'];
                            echo '
                                <tr class="border-b">
                                    <td class="px-4 py-2">' . htmlspecialchars($row['product_name'], ENT_QUOTES) . '</td>
                                    <td class="px-4 py-2">' . number_format($row['product_price'], 2) . '</td>
                                    <td class="px-4 py-2">
                                        <form method="POST" action="cart.php">
                                            <input type="hidden" name="id" value="' . $row['id'] . '">
                                            <input type="number" name="quantity" value="' . $row['quantity'] . '" min="1" class="w-16 p-2 text-center border border-gray-300 rounded-md">
                                            <button type="submit" name="update_quantity" class="ml-2 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Update</button>
                                        </form>
                                    </td>
                                    <td class="px-4 py-2">
                                        <a href="cart.php?id=' . $row['id'] . '" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">Delete</a>
                                    </td>
                                </tr>
                            ';
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center py-4'>Your cart is empty!</td></tr>";
                    }
                    ?>
                    <tr>
                        <td colspan="3" class="px-4 py-2 text-right font-bold">Total</td>
                        <td class="px-4 py-2 text-left text-indigo-600 font-bold"><?php echo number_format($total, 2); ?> Taka</td>
                    </tr>
                </tbody>
            </table>

            <!-- Address Input -->
            <div class="mb-6">
                <label for="address" class="block text-lg font-semibold mb-2">Delivery Address</label>
                <input type="text" id="address" name="address" placeholder="Enter your delivery address" required class="w-full p-3 border border-gray-300 rounded-md">
            </div>

            <!-- Checkout & Clear Cart Buttons -->
            <div class="flex justify-between">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700">Checkout</button>
                <a href="cart.php?action=clear" class="bg-red-600 text-white px-6 py-3 rounded-md hover:bg-red-700">Clear Cart</a>
            </div>
        </form>
    </div>
</body>

</html>
