<?php
// Database Configuration
include 'db_config.php';

// Handle Registration Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $location = htmlspecialchars($_POST['location']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Insert into `users` table based on selected role
    $sql = "INSERT INTO users (full_name, email, phone, address, password, role) VALUES ('$name', '$email', '$phone', '$location', '$password', '$role')";

    if ($conn->query($sql)) {
        echo "<script>alert('Registration Successful! You can now log in.'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('Error: Unable to register. Please try again later.');</script>";
    }
}
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
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800">
    <header class="flex justify-between items-center px-6 py-4 bg-white bg-opacity-80 shadow-md">
        <div class="text-2xl font-bold text-indigo-600">
            <a href="index.php">Swift Buy 🛒</a>
        </div>
        <div>
            <button class="bg-indigo-600 text-white px-4 py-2 rounded-md mr-2 hover:bg-indigo-700" onclick="location.href='login.php'">Login</button>
            <button class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700" onclick="location.href='register.php'">Register</button>
        </div>
    </header>

    <div class="container mx-auto p-6 max-w-md bg-white shadow-md rounded-lg mt-10">
        <h1 class="text-2xl font-bold text-center mb-4">Register</h1>
        <form method="POST" action="">
            <div class="form-group mb-4">
                <input type="text" name="name" placeholder="Name" required class="w-full p-3 border border-gray-300 rounded-md">
            </div>
            <div class="form-group mb-4">
                <input type="email" name="email" placeholder="Email" required class="w-full p-3 border border-gray-300 rounded-md">
            </div>
            <div class="form-group mb-4">
                <input type="text" name="phone" placeholder="Phone Number" required class="w-full p-3 border border-gray-300 rounded-md">
            </div>
            <div class="form-group mb-4">
                <input type="text" name="location" placeholder="Location" required class="w-full p-3 border border-gray-300 rounded-md">
            </div>
            <div class="form-group mb-4">
                <input type="password" name="password" placeholder="Password" required class="w-full p-3 border border-gray-300 rounded-md">
            </div>
            <div class="form-group mb-4">
                <select name="role" required class="w-full p-3 border border-gray-300 rounded-md">
                    <option value="" disabled selected>Select Role</option>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-3 rounded-md text-lg hover:bg-indigo-700">Register</button>
        </form>

        <div class="text-center mt-4">
            <button class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700" onclick="location.href='login.php'">Login</button>
            <button class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 ml-2" onclick="location.href='index.php'">Home</button>
        </div>
    </div>
</body>

</html>
