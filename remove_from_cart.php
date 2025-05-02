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

// Handle item removal from cart
if (isset($_GET['id'])) {
    $cart_id = intval($_GET['id']);

    // Delete the item from the cart
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cart_id, $user_id);

    if ($stmt->execute()) {
        header("Location: cart.php"); // Redirect to cart page after removal
        exit();
    } else {
        echo "<script>alert('Error removing item.');</script>";
    }
}
?>
