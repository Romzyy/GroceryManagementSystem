<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Quantity</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Update Item Quantity</h2>
        <?php
        // Include required classes
        require_once '../classes/DataHandler.php';
        require_once '../classes/InventoryManager.php';

        // Instantiate DataHandler and InventoryManager objects
        $dataHandler = new DataHandler(); // Assuming DataHandler handles basic CRUD operations
        $inventoryManager = new InventoryManager($dataHandler);

        // Check if form submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate and sanitize inputs
            $itemName = $_POST['item'];
            $newQuantity = intval($_POST['quantity']); // Convert to integer for safety

            // Update item quantity
            $success = $inventoryManager->updateQuantity($itemName, $newQuantity);

            // Display success or error message
            if ($success) {
                echo '<p>Quantity updated successfully!</p>';
            } else {
                echo '<p>Failed to update quantity. Please try again.</p>';
            }
        }

        // Fetch inventory items for dropdown
        $inventory = $inventoryManager->getAllItems();

        if (!empty($inventory['itemDetails'])) {
            // Display form to update quantity
            ?>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <label for="item">Select Item:</label>
                <select id="item" name="item" required>
                    <?php
                    foreach ($inventory['itemDetails'] as $itemName => $itemDetails) {
                        echo '<option value="' . htmlspecialchars($itemName) . '">' . htmlspecialchars($itemName) . '</option>';
                    }
                    ?>
                </select>
                <label for="quantity">New Quantity:</label>
                <input type="number" id="quantity" name="quantity" min="0" required>
                <button type="submit" class="button">Update Quantity</button>
            </form>
            <?php
        } else {
            echo '<p>No items found in inventory.</p>';
        }
        ?>
        <a href="staff_home.php" class="button">Back to Staff Home</a>
    </div>
</body>
</html>
