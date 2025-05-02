<?php
// Start session and check if the user is logged in
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Include the database configuration
include 'db_config.php';

// Get the product details from the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = htmlspecialchars($_POST['product_name']);
    $product_price = $_POST['product_price'];

    // Check if the product is already in the user's cart
    $check_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_name = ?");
    $check_cart->bind_param("is", $user_id, $product_name);
    $check_cart->execute();
    $cart_result = $check_cart->get_result();

    if ($cart_result->num_rows > 0) {
        // If product is already in the cart, update the quantity
        $cart_item = $cart_result->fetch_assoc();
        $new_quantity = $cart_item['quantity'] + 1;

        $update_quantity = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $update_quantity->bind_param("ii", $new_quantity, $cart_item['id']);
        $update_quantity->execute();
    } else {
        // If the product is not in the cart, insert it
        $insert_cart = $conn->prepare("INSERT INTO cart (user_id, product_name, product_price, quantity) VALUES (?, ?, ?, ?)");
        $quantity = 1; // Default quantity
        $insert_cart->bind_param("isdi", $user_id, $product_name, $product_price, $quantity);
        $insert_cart->execute();
    }

    // Redirect to cart.php
    header("Location: cart.php");
    exit();
}
?>
