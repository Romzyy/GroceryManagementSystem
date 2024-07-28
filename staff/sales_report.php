<?php
// Include DataHandler class
require_once '../classes/DataHandler.php';

// Instantiate DataHandler
$dataHandler = new DataHandler();

// Get all sales data
$salesData = $dataHandler->getAllSales();

// Get all orders data
$orderData = $dataHandler->getAllOrders();

// Calculate total orders
$totalOrders = $dataHandler->getTotalOrdersCount();

// Calculate total sales
$totalSales = $dataHandler->getTotalSalesAmount();

// Other calculations as per your requirements (item sales, order details, etc.)
$itemSales = []; // Implement logic for item sales

// HTML structure for sales report
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <title>Sales Report</title>
    <!-- CSS styles -->
    <style>
        /* Your CSS styles here */
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Sales Report</h2>
            <div class="total-sales">
                <div class="card">
                    <h3>Total Orders</h3>
                    <!-- Display total number of orders -->
                    <p><?php echo $totalOrders; ?></p>
                    <h3>Total Sales</h3>
                    <!-- Display total sales -->
                    <p>$<?php echo number_format($totalSales, 2); ?></p>
                </div>
            </div>
        </div>
        <div class="card">
            <h3>Sales for Each Item</h3>
            <table>
                <tr>
                    <th>Item Name</th>
                    <th>Quantity Sold</th>
                    <th>Total Worth</th>
                </tr>
                <!-- Loop through item sales data and display each item's sales -->
                <?php foreach ($itemSales as $itemName => $item): ?>
                    <tr>
                        <td><?php echo $itemName; ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>$<?php echo number_format($item['totalWorth'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div class="card">
            <h3>All Order Details</h3>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Payment Method</th>
                    <th>Items Ordered</th>
                    <th>Order Total</th>
                </tr>
                <!-- Loop through all order data and display each order's details -->
                <?php foreach ($orderData as $order): ?>
                    <tr>
                        <td><?php echo $order['name']; ?></td>
                        <td><?php echo $order['address']; ?></td>
                        <td><?php echo $order['payment']; ?></td>
                        <td>
                            <!-- Display each item ordered within a table -->
                            <table>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                </tr>
                                <?php foreach ($order['items'] as $item): ?>
                                    <tr>
                                        <td><?php echo $item['name']; ?></td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td>$<?php echo isset($item['price']) ? $item['price'] : ''; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </td>
                        <td>$<?php echo number_format($order['order_total'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <!-- Link back to home page -->
        <a class="button" href="staff_home.php">Back to Home</a>
    </div>
</body>
</html>
