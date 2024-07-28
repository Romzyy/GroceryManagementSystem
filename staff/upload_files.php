<?php
require_once '../classes/DataHandler.php';
require_once '../classes/InventoryManager.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dataHandler = new DataHandler();
    $inventoryManager = new InventoryManager($dataHandler);

    if (!isset($_FILES['files']['name']) || !is_array($_FILES['files']['name'])) {
        echo "No files were uploaded.";
        exit;
    }

    $upload_dir = '../uploads/';
    $success_count = 0;
    $error_count = 0;
    $skipped_count = 0;

    foreach ($_FILES['files']['name'] as $index => $fileName) {
        $fileTmpName = $_FILES['files']['tmp_name'][$index];
        $fileType = $_FILES['files']['type'][$index];
        $fileSize = $_FILES['files']['size'][$index];
        $fileError = $_FILES['files']['error'][$index];

        if ($fileError !== UPLOAD_ERR_OK) {
            $error_count++;
            echo "Error uploading file: {$fileName} <br>";
            continue; // Skip current iteration
        }

        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        if (!in_array($ext, ['json', 'xml', 'csv'])) {
            $error_count++;
            echo "Invalid file format: {$fileName}. Only JSON, XML, and CSV files are allowed. <br>";
            continue;
        }
        $fileDestination = $upload_dir . basename($fileName);

        if (move_uploaded_file($fileTmpName, $fileDestination)) {
            try {
                $result = false;
                if ($ext === 'json') {
                    $result = $inventoryManager->processJSON($fileDestination);
                } elseif ($ext === 'xml') {
                    $result = $inventoryManager->processXML($fileDestination);
                } elseif ($ext === 'csv') {
                    $result = $inventoryManager->processCSV($fileDestination);
                }
                if ($result['success']) {
                    $success_count += $result['processedCount'];
                    $skipped_count += $result['skippedCount'];
                } else {
                    $error_count++;
                }
            } catch (Exception $e) {
                $error_count++;
                echo "Error processing file: " . $e->getMessage() . "<br>";
            }
        } else {
            $error_count++;
            echo "Error moving file to upload directory: {$fileName} <br>";
        }
    }

    $message = "Upload Summary: <br> Successfully processed: $success_count <br> Skipped: $skipped_count <br> Errors: $error_count";
    echo $message;
} else {
    echo "No files were uploaded.";
}
?>
