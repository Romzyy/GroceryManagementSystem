<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $items = $_POST['items'];
    $payment = $_POST['payment'];

    if (empty($items)) {
        
        echo "No items selected. Please go back and select items.";
        echo '<br><a href="customer_order.php">Back to Order Form</a>';
        exit();
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Select Quantities - Grocery Management System</title>        
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">        
        <link rel="stylesheet" type="text/css" href="../css/styles.css">
    </head>
    <body>
        <div class="container add-item-container">
            <h2>Select Quantities</h2>
            <form action="process_order.php" method="post">
                
                <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
                <input type="hidden" name="address" value="<?php echo htmlspecialchars($address); ?>">
                <input type="hidden" name="payment" value="<?php echo htmlspecialchars($payment); ?>">

                <?php foreach ($items as $itemName): ?>
                    <div>                        
                        <label for="quantity_<?php echo htmlspecialchars($itemName); ?>"><?php echo htmlspecialchars($itemName); ?>:</label>                        
                        <input type="number" id="quantity_<?php echo htmlspecialchars($itemName); ?>" name="quantities[<?php echo htmlspecialchars($itemName); ?>]" min="1" required>
                    </div>
                <?php endforeach; ?>
                
                <input type="submit" value="Submit Order">
            </form>
        </div>
    </body>
    </html>
    <?php
} else {

    echo "Invalid request.";
}
?>
