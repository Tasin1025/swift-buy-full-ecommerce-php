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


// Handle order deletion
if (isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == 'delete') {
    $order_id = intval($_GET['id']);

    // Delete related order items first
    $stmt = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    if ($stmt->execute()) {
        // Now delete the order itself
        $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
        $stmt->bind_param("i", $order_id);
        if ($stmt->execute()) {
            echo "<script>alert('Order deleted successfully!');</script>";
            header("Location: view_orders.php"); // Redirect to orders page
            exit();
        } else {
            echo "<script>alert('Error deleting order.');</script>";
        }
    } else {
        echo "<script>alert('Error deleting related order items.');</script>";
    }
    $stmt->close();
}


// Handle order status update
if (isset($_POST['update_status'])) {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];

    // Update status in the database
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);

    if ($stmt->execute()) {
        echo "<script>alert('Order status updated successfully!');</script>";
        header("Location: view_orders.php"); // Redirect to orders page
        exit();
    } else {
        echo "<script>alert('Error updating order status.');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swift Buy 🛒 - Admin Orders</title>
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
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex-grow: 1;
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

    <!-- Main Content Section -->
    <main>
        <!-- Orders Section -->
        <div id="orders" class="container mx-auto p-6 mt-10 bg-white shadow-md rounded-lg max-w-4xl">
            <h2 class="text-3xl font-semibold mb-6 text-center">View Orders</h2>
            <table class="min-w-full bg-white shadow-md rounded-lg">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-6 py-3 text-left">Order ID</th>
                        <th class="px-6 py-3 text-left">Product Name</th>
                        <th class="px-6 py-3 text-left">Total Price</th>
                        <th class="px-6 py-3 text-left">Delivery Address</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch orders from the database
                    $sql = "SELECT * FROM orders";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($order = $result->fetch_assoc()) {
                            // Get the product names for the order
                            $order_items_sql = "SELECT * FROM order_items WHERE order_id = ?";
                            $stmt = $conn->prepare($order_items_sql);
                            $stmt->bind_param("i", $order['id']);
                            $stmt->execute();
                            $order_items_result = $stmt->get_result();
                            $products = '';
                            while ($item = $order_items_result->fetch_assoc()) {
                                $products .= $item['product_name'] . ', ';
                            }
                            $products = rtrim($products, ', ');

                            // Display order details in the table
                            echo '
                                <tr class="border-b">
                                    <td class="px-6 py-3">' . htmlspecialchars($order['id'], ENT_QUOTES) . '</td>
                                    <td class="px-6 py-3">' . htmlspecialchars($products, ENT_QUOTES) . '</td>
                                    <td class="px-6 py-3">' . number_format($order['total'], 2) . ' Taka</td>
                                    <td class="px-6 py-3">' . htmlspecialchars($order['address'], ENT_QUOTES) . '</td>
                                    <td class="px-6 py-3">
                                        <form method="POST" action="">
                                            <select name="status" class="px-4 py-2 border rounded-md">
                                                <option value="pending" ' . ($order['status'] == 'pending' ? 'selected' : '') . '>Pending</option>
                                                <option value="delivered" ' . ($order['status'] == 'delivered' ? 'selected' : '') . '>Delivered</option>
                                                <option value="canceled" ' . ($order['status'] == 'canceled' ? 'selected' : '') . '>Canceled</option>
                                            </select>
                                            <input type="hidden" name="order_id" value="' . $order['id'] . '">
                                            <button type="submit" name="update_status" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 mt-2">Update</button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-3">
                                        <a href="view_orders.php?id=' . $order['id'] . '&action=delete" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">Delete</a>
                                    </td>
                                </tr>
                            ';
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center py-4'>No orders found!</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Footer -->
    <footer class="text-center py-6 bg-gray-200 text-gray-600">
        <p>&copy; 2025 Swift Buy 🛒 | All Rights Reserved</p>
    </footer>

</body>

</html>
