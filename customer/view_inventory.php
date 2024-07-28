<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory View</title>
    <link rel="stylesheet" href="../css/styles.css">
  
</head>
<body>
    <div class="inventory-container">
        <h2>Inventory View</h2>
        <div class="inventory-cards">
            <?php
            require_once '../classes/DataHandler.php';
            require_once '../classes/InventoryManager.php';

            $dataHandler = new DataHandler();
            $inventoryManager = new InventoryManager($dataHandler);

            $inventory = $inventoryManager->getAllItems();

            if (!empty($inventory['itemDetails'])) {
                foreach ($inventory['itemDetails'] as $itemName => $itemDetails) {
                    $expiryDate = isset($itemDetails['expiry_date']) ? $itemDetails['expiry_date'] : '';
                    $status = ($expiryDate < date("Y-m-d")) ? "Expired" : "Valid";
                    $statusClass = strtolower($status);
                    ?>
                    <div class="card">
                        <h3><?php echo htmlspecialchars($itemName); ?></h3>
                        <p><strong>Type:</strong> <?php echo isset($itemDetails['type']) ? htmlspecialchars($itemDetails['type']) : ''; ?></p>
                        <p><strong>Price:</strong> $<?php echo isset($itemDetails['price']) ? htmlspecialchars($itemDetails['price']) : ''; ?></p>
                        <p><strong>Quantity:</strong> <?php echo isset($itemDetails['quantity']) ? htmlspecialchars($itemDetails['quantity']) : ''; ?></p>
                        <p><strong>Expiry Date:</strong> <?php echo htmlspecialchars($expiryDate); ?></p>
                        <div class="status <?php echo $statusClass; ?>"><?php echo $status; ?></div>
                    </div>
                    <?php
                }
            } else {
                echo '<p>No items found in inventory.</p>';
            }
            ?>
        </div>
        <br>
        <a href="customer_home.php" class="button">Back to Home</a>
    </div>
</body>
</html>
