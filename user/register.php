<?php
require_once '../classes/Authentication.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $user = new Authentication();
    if ($user->register($username, $password, $email, $role)) {
        header('Location: login.php'); 
        exit();
    } else {
        echo "Registration failed.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Grocery Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
<h1>Grocery Management System</h1>
<br><br>    
<form method="POST" action="register.php">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    Email: <input type="email" name="email" required><br>
    Role: 
    <select name="role" required>
        <option value="User">User</option>
        <option value="Admin">Admin</option>
    </select><br>
    <input type="submit" value="Register">
</form>
<p>Existing user? <a href="login.php">Login</a></p>
</body>
</html>