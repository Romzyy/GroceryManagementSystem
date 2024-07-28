<?php
require_once '../classes/Authentication.php';
session_start();

if (!isset($_SESSION['user_id']) && !isset($_COOKIE['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : $_COOKIE['user_id'];

$user = new Authentication();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    if ($user->updateProfile($user_id, $username, $email)) {
        echo "Profile updated!";
    } else {
        echo "Profile update failed.";
    }
}

$profile = $user->getProfile($user_id);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Grocery Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script>
        function goBack() {
            const role = "<?php echo $profile['role']; ?>";
            if (role === 'Admin') {
                window.location.href = '../staff/staff_home.php';
            } else {
                window.location.href = '../customer/customer_home.php';
            }
        }
    </script>
    <style>
        /* styles.css */

body {
    font-family: 'Roboto', sans-serif;
    background-color: #f7f7f7;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

h1 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

.profile-container {
    background-color: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 350px;
}

.profile-data p {
    font-size: 16px;
    color: #555;
    margin: 10px 0;
}

.profile-data strong {
    color: #333;
}

.form-group {
    margin: 15px 0;
}

label {
    display: block;
    font-size: 14px;
    color: #555;
    margin-bottom: 5px;
}

input[type="text"],
input[type="email"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

input[type="submit"] {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 4px;
    background-color: #28a745;
    color: white;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
}

input[type="submit"]:hover {
    background-color: #218838;
}

.button {
    display: block;
    width: 100%;
    padding: 10px;
    margin-top: 20px;
    text-decoration: none;
    background-color: #007bff;
    color: white;
    font-size: 16px;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.button:hover {
    background-color: #0056b3;
}

    </style>
</head>
<body>
    <div class="profile-container">
        <h1>Profile</h1>
        <div class="profile-data">
            <p><strong>Username:</strong> <?php echo htmlspecialchars($profile['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($profile['email']); ?></p>
            
        </div>
        <form method="POST" action="profile.php">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($profile['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($profile['email']); ?>" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Update Profile">
            </div>
        </form>
        <button class="button" onclick="goBack()">Back</button>
    </div>
</body>
</html>