<?php
require_once '../classes/DataHandler.php'; 

// Create an instance of DataHandler
$dataHandler = new DataHandler();

// Backup the database
$backupFilePath = '../data_backup/backup.sql'; 

$dataHandler->backupDatabase($backupFilePath);

$dataHandler->restoreDatabase($backupFilePath);
?>