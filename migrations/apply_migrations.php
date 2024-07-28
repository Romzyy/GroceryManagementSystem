<?php
require_once '../classes/DataHandler.php'; 

$dataHandler = new DataHandler();

$migrationsPath = '../migrations';
$dataHandler->applyMigrations($migrationsPath);
?>