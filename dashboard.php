<?php
session_start();
include 'db_config.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";


$stmt = $conn->prepare("SELECT name, email, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_profile'])) {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);

        
        if (!empty($_FILES['profile_picture']['name'])) {
            $target_dir = "profile_picture/";
            $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
            move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);
            $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, profile_picture = ? WHERE id = ?");
            $stmt->bind_param("sssi", $name, $email, $target_file, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
            $stmt->bind_param("ssi", $name, $email, $user_id);
        }
        
        if ($stmt->execute()) {
            $message = "Profile updated successfully!";
        }
    }

    
    if (isset($_POST['change_password'])) {
        $new_password = htmlspecialchars($_POST['new_password']);
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

    
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header class="header">
        <div class="left">
            <div id="website_name" onclick="location.href='index.php'"> Swift Buy 🛒</div>
        </div>
        <div class="right">
            <button class="btn" onclick="location.href='logout.php'">Logout</button>
        </div>

    </header>
    <div class="container">
        <h1>Welcome, <?php echo $user['name']; ?>!</h1>
        <p>Email: <?php echo $user['email']; ?></p>


        <img src="<?php echo $user['profile_picture']; ?>" alt="Profile Picture" width="100">

        <p style="color: green;"><?php echo $message; ?></p>


        <h2>Edit Profile</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <input type="text" name="name" value="<?php echo $user['name']; ?>" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="form-group">
                <label>Profile Picture:</label>
                <input type="file" name="profile_picture">
            </div>
            <button type="submit" name="update_profile" class="btn">Update Profile</button>
        </form>


        <h2>Change Password</h2>
        <form method="POST" action="">
            <div class="form-group">
                <input type="password" name="new_password" placeholder="New Password" required>
            </div>
            <button type="submit" name="change_password" class="btn">Change Password</button>
        </form>


        <h2>Delete Account</h2>
        <form method="POST" action="">
            <button type="submit" name="delete_account" class="btn danger"
                onclick="return confirm('Are you sure? This action cannot be undone!')">Delete Account</button>
        </form>


    </div>
</body>

</html>