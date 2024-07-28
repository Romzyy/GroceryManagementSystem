<?php
require_once '../classes/DataHandler.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the form
    $name = $_POST['name'];
    $address = $_POST['address'];
    $payment = $_POST['payment'];
    $quantities = $_POST['quantities'];

    // Initialize variables for order summary and sales data
    $orderSummary = [
        'name' => $name,
        'address' => $address,
        'payment' => $payment,
        'items' => []
    ];

    $salesData = [];

    // Load data handler
    $dataHandler = new DataHandler();

    // Get current inventory data
    $inventory = $dataHandler->read('inventory');

    // Convert inventory data to associative array with item names as keys
    $inventoryData = [];
    foreach ($inventory as $item) {
        $inventoryData[$item['name']] = $item;
    }

    foreach ($quantities as $itemName => $quantity) {
        // Check if item exists in inventory
        if (isset($inventoryData[$itemName])) {
            $itemDetails = $inventoryData[$itemName];
            $availableQuantity = $itemDetails['quantity'];

            // Validate if sufficient quantity is available
            if ($availableQuantity >= $quantity && $quantity > 0) {
                // Update inventory
                $newQuantity = $availableQuantity - $quantity;
                $dataHandler->update('inventory', ['name' => $itemName], ['quantity' => $newQuantity]);

                // Update sales data
                if (isset($salesData[$itemName])) {
                    $salesData[$itemName]['quantity'] += (int)$quantity;
                } else {
                    $salesData[$itemName] = [
                        'name' => $itemName,
                        'quantity' => (int)$quantity,
                        'price' => $itemDetails['price']
                    ];
                }

                // Add item to order summary
                $orderSummary['items'][] = [
                    'name' => $itemName,
                    'quantity' => (int)$quantity,
                    'price' => $itemDetails['price']
                ];
            } else {
                // If insufficient quantity, redirect back with error message
                echo "Error: Insufficient quantity for $itemName.";
                exit();
            }
        } else {
            // If item not found in inventory, redirect back with error message
            echo "Error: Item $itemName not found.";
            exit();
        }
    }

    // Append order summary to existing orders file
    $ordersFile = '../data/orders.json';
    $existingOrders = [];
    if (file_exists($ordersFile)) {
        $existingOrders = json_decode(file_get_contents($ordersFile), true);
    }
    $existingOrders[] = $orderSummary;
    file_put_contents($ordersFile, json_encode($existingOrders, JSON_PRETTY_PRINT));

    // Update sales data file
    $salesFile = '../data/sales.json';
    $existingSales = [];
    if (file_exists($salesFile)) {
        $existingSales = json_decode(file_get_contents($salesFile), true);
    }
    foreach ($salesData as $itemName => $itemSales) {
        if (isset($existingSales[$itemName])) {
            $existingSales[$itemName]['quantity'] += $itemSales['quantity'];
        } else {
            $existingSales[$itemName] = $itemSales;
        }
    }
    file_put_contents($salesFile, json_encode($existingSales, JSON_PRETTY_PRINT));

    // Display order summary
    echo "<!DOCTYPE html>";
    echo "<html lang='en'>";
    echo "<head>";
    echo "<meta charset='UTF-8'>";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    echo "<title>Order Summary</title>";
    echo "<link rel='stylesheet' href='../css/styles.css'>";
    echo "</head>";
    echo "<body>";
    echo "<div class='container'>";
    echo "<div class='order-summary'>";
    echo "<h2>Order Summary</h2>";
    echo "<p><strong>Name:</strong> {$orderSummary['name']}</p>";
    echo "<p><strong>Address:</strong> {$orderSummary['address']}</p>";
    echo "<p><strong>Payment Method:</strong> {$orderSummary['payment']}</p>";
    echo "<h3>Items Ordered:</h3>";
    echo "<ul>";
    foreach ($orderSummary['items'] as $item) {
        echo "<li>{$item['name']} - Quantity: {$item['quantity']}, Price: \${$item['price']}</li>";
    }
    echo "</ul>";
    echo "<a class='button' href='../customer/customer_home.php'>Back to Home</a>";
    echo "</div>";
    echo "</div>";
    echo "</body>";
    echo "</html>";

    exit(); // Ensure script stops after displaying order summary
} else {
    // If the request method is not POST, display an error message
    echo "<p class='error'>Invalid request.</p>";
}
?>
