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
if (isset($_GET['id'])) {
    $order_id = intval($_GET['id']);

    // Delete order from the database
    $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->bind_param("i", $order_id);

    if ($stmt->execute()) {
        echo "<script>alert('Order deleted successfully!');</script>";
        header("Location: view_orders.php"); // Redirect to orders page
        exit();
    } else {
        echo "<script>alert('Error deleting order.');</script>";
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
            min-height: 100vh; /* Ensure full height */
        }

        main {
            flex-grow: 1; /* This will push the footer down */
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
                            echo '
                                <tr class="border-b">
                                    <td class="px-6 py-3">' . htmlspecialchars($order['id'], ENT_QUOTES) . '</td>
                                    <td class="px-6 py-3">' . htmlspecialchars($order['product_name'], ENT_QUOTES) . '</td>
                                    <td class="px-6 py-3">' . number_format($order['total'], 2) . ' Taka</td>
                                    <td class="px-6 py-3">' . htmlspecialchars($order['address'], ENT_QUOTES) . '</td>
                                    <td class="px-6 py-3">' . htmlspecialchars($order['status'], ENT_QUOTES) . '</td>
                                    <td class="px-6 py-3">
                                        <a href="view_order.php?id=' . $order['id'] . '" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">View</a>
                                        <a href="view_orders.php?id=' . $order['id'] . '" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">Delete</a>
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
