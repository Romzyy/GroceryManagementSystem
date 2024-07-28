<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Add New Item</h2>
        <form action="add_item.php" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="type">Type:</label>
            <input type="text" id="type" name="type" required>
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" required>
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" required>
            <label for="expiry">Expiry Date:</label>
            <input type="date" id="expiry" name="expiry" required>
            <button type="submit" class="button">Add Item</button>
        </form>
        <a href="staff_home.php" class="button">Back to Staff Home</a>
    </div>
</body>
</html>

<?php
require_once '../classes/DataHandler.php';
require_once '../classes/InventoryManager.php';

$dataHandler = new DataHandler();
$inventoryManager = new InventoryManager($dataHandler);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $itemName = htmlspecialchars($_POST['name']);
    $itemType = htmlspecialchars($_POST['type']);
    $itemPrice = htmlspecialchars($_POST['price']);
    $itemQuantity = htmlspecialchars($_POST['quantity']);
    $itemExpiry = htmlspecialchars($_POST['expiry']);

    $result = $inventoryManager->addItem($itemName, $itemType, $itemPrice, $itemQuantity, $itemExpiry);

    if ($result) {
        header("Location: staff_home.php?success=1");
        exit();
    } else {
        header("Location: add_item.php?error=1");
        exit();
    }
} 
?>
