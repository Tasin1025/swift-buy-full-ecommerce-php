<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db_config.php';

// Handle Add to Cart Request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve product data from the form
    $product_id = $_POST['product_id'];  // Pass product_id
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];

    // Get the logged-in user's ID
    $user_id = $_SESSION['user_id'];

    // Default quantity set to 1
    $quantity = 1;

    // Check if the product is already in the cart
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id); // Using product_id to check uniqueness
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // If the product is not in the cart, insert it
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, product_name, product_price, quantity) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisdi", $user_id, $product_id, $product_name, $product_price, $quantity);

        if ($stmt->execute()) {
            echo "<script>alert('Item added to cart');</script>";
            header("Location: cart.php"); // Redirect to cart page
            exit();
        } else {
            echo "<script>alert('Error adding item to cart');</script>";
        }
    } else {
        // If the item is already in the cart, increase quantity by 1
        $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $user_id, $product_id);
        if ($stmt->execute()) {
            echo "<script>alert('Item quantity increased in the cart');</script>";
            header("Location: cart.php");
            exit();
        } else {
            echo "<script>alert('Error updating cart quantity');</script>";
        }
    }

    $stmt->close();
}
?>
