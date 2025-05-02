<?php
session_start();
include 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Fetch user information from the database
$stmt = $conn->prepare("SELECT full_name, email, profile_picture, password FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_profile'])) {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);

        // Handle profile picture upload
        if (!empty($_FILES['profile_picture']['name'])) {
            $target_dir = "profile_picture/";
            $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
            
            // Validate file upload (e.g., file type)
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $allowedTypes = array('jpg', 'png', 'jpeg', 'gif');
            
            if (in_array($imageFileType, $allowedTypes)) {
                move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);
                
                $stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ?, profile_picture = ? WHERE id = ?");
                $stmt->bind_param("sssi", $name, $email, $target_file, $user_id);
            } else {
                $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            }
        } else {
            // If no new picture, update name and email only
            $stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ? WHERE id = ?");
            $stmt->bind_param("ssi", $name, $email, $user_id);
        }

        if ($stmt->execute()) {
            $message = "Profile updated successfully!";
        }
    }

    if (isset($_POST['change_password'])) {
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $new_password, $user_id);
        if ($stmt->execute()) {
            $message = "Password changed successfully!";
        }
    }

    if (isset($_POST['delete_account'])) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            session_destroy();
            header("Location: register.php");
            exit();
        }
    }

    header("Location: profile.php");  // Redirect back to profile page to refresh the page after changes
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swift Buy 🛒 - Edit Profile</title>
    <link rel="icon" href="favicon.png" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-100 text-gray-800">
    <!-- Header -->
    <header class="flex justify-between items-center px-6 py-4 bg-white shadow-md">
        <div class="text-2xl font-bold text-indigo-600">
            <a href="user_dashboard.php">Swift Buy 🛒</a>
        </div>
        <div>
            <a href="logout.php" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Logout</a>
        </div>
    </header>

    <!-- Profile Update Section -->
    <div class="container mx-auto p-6 mt-10 bg-white shadow-md rounded-lg max-w-4xl">
        <h1 class="text-3xl font-semibold text-center mb-6">Welcome, <?php echo htmlspecialchars($user['full_name'], ENT_QUOTES); ?>!</h1>
        <p class="text-center mb-6">Manage your profile and account settings.</p>

        <p class="text-center text-green-600"><?php echo $message; ?></p>

        <!-- Edit Profile Form -->
        <form method="POST" action="" enctype="multipart/form-data" class="space-y-6">
            <div class="form-group">
                <input type="text" name="name" value="<?php echo htmlspecialchars($user['full_name'], ENT_QUOTES); ?>" required class="w-full p-3 border border-gray-300 rounded-md">
            </div>
            <div class="form-group">
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'], ENT_QUOTES); ?>" required class="w-full p-3 border border-gray-300 rounded-md">
            </div>
            <div class="form-group">
                <label for="profile_picture">Profile Picture:</label>
                <input type="file" name="profile_picture" class="w-full p-3 border border-gray-300 rounded-md">
            </div>
            <button type="submit" name="update_profile" class="w-full bg-indigo-600 text-white px-4 py-3 rounded-md text-lg hover:bg-indigo-700">Update Profile</button>
        </form>

        <!-- Change Password Form -->
        <h2 class="text-xl font-semibold mt-10">Change Password</h2>
        <form method="POST" action="" class="space-y-6">
            <div class="form-group">
                <input type="password" name="new_password" placeholder="New Password" required class="w-full p-3 border border-gray-300 rounded-md">
            </div>
            <button type="submit" name="change_password" class="w-full bg-indigo-600 text-white px-4 py-3 rounded-md text-lg hover:bg-indigo-700">Change Password</button>
        </form>

        <!-- Delete Account Form -->
        <h2 class="text-xl font-semibold mt-10">Delete Account</h2>
        <form method="POST" action="">
            <button type="submit" name="delete_account" class="w-full bg-red-600 text-white px-4 py-3 rounded-md text-lg hover:bg-red-700"
                onclick="return confirm('Are you sure? This action cannot be undone!')">Delete Account</button>
        </form>
    </div>

</body>

</html>
