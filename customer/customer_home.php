<?php
require_once '../classes/DataHandler.php';
$dataHandler = new DataHandler();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Grocery Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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

.index-container {
    background-color: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 300px;
}

.button {
    display: block;
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    text-decoration: none;
    background-color: #28a745;
    color: white;
    font-size: 16px;
    border-radius: 4px;
    transition: background-color 0.3s;
}

.button:hover {
    background-color: #218838;
}

.logout-button {
    display: block;
    padding: 10px;
    margin-top: 20px;
    text-decoration: none;
    background-color: #dc3545;
    color: white;
    font-size: 16px;
    border-radius: 4px;
    transition: background-color 0.3s;
}

.logout-button:hover {
    background-color: #c82333;
}

.logout-button i {
    margin-right: 5px;
}

    </style>
</head>
<!-- Customer Portal -->
<body>
    <div class="index-container card">
        <h1>Customer Portal</h1>
        <a href="customer_order.php" class="button">Place Order</a>
        <a href="view_inventory.php" class="button">View Inventory</a>
        <a href="../user/profile.php" class="button">My Profile</a>
        <a href="../user/logout.php" class="logout-button"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</body>
</html>