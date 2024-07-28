<?php
class OrderProcessor {
    private $dataHandler;
    private $orderFile;
    private $salesFile;

    public function __construct($dataHandler, $orderFile, $salesFile) {
        $this->dataHandler = $dataHandler;
        $this->orderFile = $orderFile;
        $this->salesFile = $salesFile;
    }

    public function processOrder($name, $address, $items, $payment) {
        $orderSummary = [
            'name' => $name,
            'address' => $address,
            'payment' => $payment,
            'items' => []
        ];

        $data = $this->dataHandler->getData();

        $salesData = [];
        if (file_exists($this->salesFile)) {
            $salesData = json_decode(file_get_contents($this->salesFile), true);
        }


        foreach ($items as $itemName => $quantity) {
            if (array_key_exists($itemName, $data['itemDetails'])) {
                $itemDetails = $data['itemDetails'][$itemName];
                $availableQuantity = $itemDetails['quantity'];
                if ($availableQuantity >= $quantity) {
                    // Update inventory
                    $data['itemDetails'][$itemName]['quantity'] -= $quantity;
                    // Update or create entry in sales data
                    if (array_key_exists($itemName, $salesData)) {
                        $salesData[$itemName]['quantity'] += $quantity;
                    } else {
                        $salesData[$itemName] = [
                            'name' => $itemName,
                            'quantity' => $quantity
                        ];
                    }
                    // Add item to order summary
                    $orderSummary['items'][] = [
                        'name' => $itemName,
                        'quantity' => $quantity,
                        'price' => $itemDetails['price']
                    ];
                } else {
                    return false;
                }
            }
        }
        $this->dataHandler->saveData($data);

        // Save order details
        $existingOrders = [];
        if (file_exists($this->orderFile)) {
            $existingOrders = json_decode(file_get_contents($this->orderFile), true);
        }
        $existingOrders[] = $orderSummary;
        file_put_contents($this->orderFile, json_encode($existingOrders, JSON_PRETTY_PRINT));

        // Save sales data
        file_put_contents($this->salesFile, json_encode($salesData, JSON_PRETTY_PRINT));

        return true;
    }
}
?>
