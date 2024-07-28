<?php
require_once '../classes/DataHandler.php';
require_once '../classes/InventoryManager.php';

$dataHandler = new DataHandler();
$inventoryManager = new InventoryManager($dataHandler);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the form
    $itemName = htmlspecialchars($_POST['name']);
    $itemType = htmlspecialchars($_POST['type']);
    $itemPrice = htmlspecialchars($_POST['price']);
    $itemQuantity = htmlspecialchars($_POST['quantity']);
    $itemExpiry = htmlspecialchars($_POST['expiry']);

    // Add the item using the InventoryManager
    $result = $inventoryManager->addItem($itemName, $itemType, $itemPrice, $itemQuantity, $itemExpiry);

    if ($result) {
        header("Location: staff_home.php?success=1");
        exit();
    } else {
        header("Location: add_item.php?error=1");
        exit();
    }
} else {
    echo "Invalid request.";
}
?>
