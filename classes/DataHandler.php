<?php
class DataHandler {
    private $servername = "127.0.0.1";
    private $username = "root";
    private $password = "";
    private $dbname = "grocery_db";
    private $conn;

    public function __construct() {
        // Create connection
        $dsn = "mysql:host=$this->servername;dbname=$this->dbname";
        try {
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->initializeMigrationsTable();
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    private function initializeMigrationsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS migrations (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    migration VARCHAR(255) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )";
        $this->conn->exec($sql);
    }

    public function update($table, $conditions, $data) {
        // Example: Update operation
        try {
            $sql = "UPDATE $table SET quantity = :quantity WHERE name = :name";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':quantity', $data['quantity']);
            $stmt->bindParam(':name', $conditions['name']);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo 'Update failed: ' . $e->getMessage();
            return false; // Handle update error
        }
    }

    public function read($table) {
        // Example: Read operation
        try {
            $sql = "SELECT * FROM $table";
            $stmt = $this->conn->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Read failed: ' . $e->getMessage();
            return []; // Handle read error
        }
    }

    public function getAllSales() {
        try {
            $sql = "SELECT * FROM sales";
            $stmt = $this->conn->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Read failed: ' . $e->getMessage();
            return []; // Handle read error
        }
    }

    public function getAllOrders() {
        try {
            $sql = "SELECT * FROM orders";
            $stmt = $this->conn->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Read failed: ' . $e->getMessage();
            return []; // Handle read error
        }
    }

    public function getTotalOrdersCount() {
        try {
            $sql = "SELECT COUNT(*) as total FROM orders";
            $stmt = $this->conn->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            echo 'Read failed: ' . $e->getMessage();
            return 0; // Handle read error
        }
    }

    public function getTotalSalesAmount() {
        try {
            $sql = "SELECT SUM(quantity * price) as total_sales FROM sales";
            $stmt = $this->conn->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total_sales'] ?? 0;
        } catch (PDOException $e) {
            echo 'Read failed: ' . $e->getMessage();
            return 0; // Handle read error
        }
    }

    public function executeQuery($query) {
        return $this->conn->query($query);
    }

    public function prepareStatement($query) {
        return $this->conn->prepare($query);
    }

    public function closeConnection() {
        $this->conn = null;
    }

    public function backupDatabase($backupFilePath) {
        if (!file_exists($backupFilePath)) {
            touch($backupFilePath);
        }

        $command = "mysqldump --user={$this->username} --password={$this->password} --host={$this->servername} {$this->dbname} > $backupFilePath";
        system($command, $output);
        if ($output === 0) {
            echo "Backup successful.\n";
        } else {
            echo "Backup failed.\n";
        }
    }

    public function restoreDatabase($backupFilePath) {
        $command = "mysql --user={$this->username} --password={$this->password} --host={$this->servername} {$this->dbname} < $backupFilePath";
        system($command, $output);
        if ($output === 0) {
            echo "Restore successful.\n";
        } else {
            echo "Restore failed.\n";
        }
    }

    public function applyMigrations($migrationsPath) {
        $appliedMigrations = $this->getAppliedMigrations();
        $migrations = glob($migrationsPath . '/*.sql');

        foreach ($migrations as $migration) {
            $migrationName = basename($migration);
            if (!in_array($migrationName, $appliedMigrations)) {
                $this->applyMigration($migration, $migrationName);
            }
        }
    }

    private function getAppliedMigrations() {
        $sql = "SELECT migration FROM migrations";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    private function applyMigration($migrationFile, $migrationName) {
        try {
            $migrationSQL = file_get_contents($migrationFile);
            $this->conn->exec($migrationSQL);

            $sql = "INSERT INTO migrations (migration) VALUES (:migration)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':migration', $migrationName);
            $stmt->execute();

            echo "Migration $migrationName applied successfully.\n";
        } catch (PDOException $e) {
            echo "Failed to apply migration $migrationName: " . $e->getMessage() . "\n";
        }
    }
}
?>
