<?php
class InventoryManager {
    private $dataHandler;

    public function __construct($dataHandler) {
        $this->dataHandler = $dataHandler;
    }

    public function addItem($name, $type, $price, $quantity, $expiry) {
        $query = "INSERT INTO inventory (name, type, price, quantity, expiry) VALUES (:name, :type, :price, :quantity, :expiry)";
        $stmt = $this->dataHandler->prepareStatement($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':expiry', $expiry);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getAllItems() {
        $query = "SELECT * FROM inventory";
        $stmt = $this->dataHandler->executeQuery($query);

        if ($stmt->rowCount() > 0) {
            $items = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $items[$row['name']] = array(
                    'type' => $row['type'],
                    'price' => $row['price'],
                    'quantity' => $row['quantity'],
                    'expiry_date' => isset($row['expiry_date']) ? $row['expiry_date'] : '' // Check if expiry_date exists
                );
            }
            return array('itemDetails' => $items);
        } else {
            return array('itemDetails' => array());
        }
    }

    public function updateQuantity($itemName, $newQuantity) {
        $query = "UPDATE inventory SET quantity = :quantity WHERE name = :name";
        $stmt = $this->dataHandler->prepareStatement($query);
        $stmt->bindParam(':quantity', $newQuantity);
        $stmt->bindParam(':name', $itemName);

        return $stmt->execute();
    }

    public function processJSON($filePath) {
        $jsonData = file_get_contents($filePath);
        $items = json_decode($jsonData, true);

        $processedCount = 0;
        $skippedCount = 0;

        if (isset($items['groceryItems']) && isset($items['itemDetails'])) {
            foreach ($items['itemDetails'] as $name => $details) {
                if (isset($details['type'], $details['price'], $details['quantity'], $details['expiry_date'])) {
                    $this->addItem(
                        $name,
                        $details['type'],
                        $details['price'],
                        $details['quantity'],
                        $details['expiry_date']
                    );
                    $processedCount++;
                } else {
                    $skippedCount++;
                }
            }
            return ['success' => true, 'processedCount' => $processedCount, 'skippedCount' => $skippedCount];
        } else {
            return ['success' => false, 'processedCount' => 0, 'skippedCount' => 0];
        }
    }

    public function processXML($filePath) {
        $xml = simplexml_load_file($filePath);
        $processedCount = 0;
        $skippedCount = 0;

        if ($xml !== false) {
            foreach ($xml->item as $item) {
                if (isset($item->name, $item->type, $item->price, $item->quantity, $item->expiry_date)) {
                    $this->addItem(
                        (string)$item->name,
                        (string)$item->type,
                        (float)$item->price,
                        (int)$item->quantity,
                        (string)$item->expiry_date
                    );
                    $processedCount++;
                } else {
                    $skippedCount++;
                }
            }
            return ['success' => true, 'processedCount' => $processedCount, 'skippedCount' => $skippedCount];
        } else {
            return ['success' => false, 'processedCount' => 0, 'skippedCount' => 0];
        }
    }

    public function processCSV($filePath) {
        $processedCount = 0;
        $skippedCount = 0;

        if (($handle = fopen($filePath, "r")) !== FALSE) {
            fgetcsv($handle); // Skip the header row
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Check if all required fields are present
                if (count($data) === 5 && !in_array("", $data, true)) {
                    $this->addItem($data[0], $data[1], (float)$data[2], (int)$data[3], $data[4]);
                    $processedCount++;
                } else {
                    // Handle incomplete data (optional)
                    echo "Skipped row with incomplete data: " . implode(", ", $data) . "<br>";
                    $skippedCount++;
                }
            }
            fclose($handle);
            return ['success' => true, 'processedCount' => $processedCount, 'skippedCount' => $skippedCount];
        } else {
            return ['success' => false, 'processedCount' => 0, 'skippedCount' => 0];
        }
    }
}
?>
