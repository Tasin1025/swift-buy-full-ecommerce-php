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
        <div class="text-2xl font-bold text-indigo-600">Swift Buy 🛒</div>
        <div>
            <button class="bg-indigo-600 text-white px-4 py-2 rounded-md mr-2 hover:bg-indigo-700" onclick="location.href='login.php'">Login</button>
            <button class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700" onclick="location.href='register.php'">Register</button>
        </div>
    </header>

    <section class="text-center py-20 bg-cover bg-center text-white" style="background-image: url('banner-tech.jpg');">
        <h1 class="text-5xl font-bold mb-4">Welcome to Swift Buy 🛒</h1>
        <p class="text-xl mb-6">Explore cutting-edge gadgets and smart accessories designed for modern living.</p>
        <button class="bg-indigo-600 text-white px-6 py-3 rounded-md text-lg hover:bg-indigo-700">Shop Now</button>
    </section>

    <section class="py-16 bg-gray-50 text-center">
        <h2 class="text-3xl font-semibold mb-10">Featured Categories</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 px-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <img src="https://cdn.mos.cms.futurecdn.net/FkGweMeB7hdPgaSFQdgsfj.jpg" alt="Smart Watches" class="w-full h-48 object-cover rounded mb-4">
                <h3 class="text-xl font-semibold mb-2">Smart Watches</h3>
                <p>Stay connected and healthy with our latest collection of smart wearables.</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <img src="https://images.othoba.com/images/thumbs/0663980_wireless-earbuds-bluetooth-headphones-with-noise-cancellation-in-ear-earbuds-with-touch-control-buil.webp" alt="Wireless Earbuds" class="w-full h-48 object-cover rounded mb-4">
                <h3 class="text-xl font-semibold mb-2">Wireless Earbuds</h3>
                <p>Experience crystal clear sound and premium audio quality anywhere.</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <img src="https://sc04.alicdn.com/kf/Hb630d22fd70f429dbbad5bd11f19c644A.jpg" alt="Laptop Accessories" class="w-full h-48 object-cover rounded mb-4">
                <h3 class="text-xl font-semibold mb-2">Laptop Accessories</h3>
                <p>Upgrade your workflow with top-tier tech accessories for your devices.</p>
            </div>
        </div>
    </section>

    <section class="py-16 text-center px-6">
        <h2 class="text-3xl font-semibold mb-10">Our Bestsellers</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            <?php
            include 'db_config.php';
            $sql = "SELECT * FROM products";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '
                        <div class="bg-white shadow-md rounded-lg overflow-hidden text-center">
                            <img src="uploads/' . $row['image'] . '" alt="' . $row['name'] . '" class="w-full h-48 object-cover">
                            <div class="p-4">
                                <h3 class="text-lg font-semibold">' . $row['name'] . '</h3>
                                <p class="text-indigo-600 font-bold">' . number_format($row['price'], 2) . ' Taka</p>
                            </div>
                        </div>
                    ';
                }
            } else {
                echo "<p class='text-gray-600'>No products found!</p>";
            }
            ?>
        </div>
    </section>

    <section class="py-16 bg-white text-center">
        <h2 class="text-3xl font-semibold mb-10">What Our Customers Say</h2>
        <div class="flex flex-col md:flex-row justify-center gap-8 px-6">
            <div class="bg-gray-100 p-6 rounded-lg shadow-md max-w-md mx-auto">
                <p class="text-gray-700 italic mb-4">"Amazing quality and fast delivery. These gadgets made my life easier!"</p>
                <h4 class="text-indigo-600 font-semibold">Nafisa</h4>
                <p class="text-sm text-gray-500">Tech Enthusiast</p>
            </div>
            <div class="bg-gray-100 p-6 rounded-lg shadow-md max-w-md mx-auto">
                <p class="text-gray-700 italic mb-4">"Highly satisfied with the accessories. Definitely coming back for more!"</p>
                <h4 class="text-indigo-600 font-semibold">Rashed</h4>
                <p class="text-sm text-gray-500">Freelancer</p>
            </div>
        </div>
    </section>

    <footer class="text-center py-6 bg-gray-200 text-gray-600">
        <p>&copy; 2025 Swift Buy 🛒 | All Rights Reserved</p>
    </footer>
</body>

</html>
