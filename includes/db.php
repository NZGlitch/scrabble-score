<?php
class Database {
    private static $instance = null;
    private $pdo;

    private function __construct($dbFile) {
        $newDB = !file_exists($dbFile);

        try {
            if ($newDB) {
                // Create the database file
                touch($dbFile);
                $this->pdo = new PDO('sqlite:' . $dbFile);
                $this->initializeSchema();
            } else { 
                $this->pdo = new PDO('sqlite:' . $dbFile);
            }

            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self(__DIR__ . '/../data/scrabble.db');
        }
        return self::$instance;
    }

    public function getHandle(): PDO {
        return $this->pdo;
    }

    private function initializeSchema() {
        $schemaFile = __DIR__ . '/schema.sql';

        if (!file_exists($schemaFile)) {
            die("Schema file not found: $schemaFile");
        }

        $schemaSql = file_get_contents($schemaFile);
        try {
            $this->pdo->exec($schemaSql);
        } catch (PDOException $e) {
            die("Failed to execute schema SQL: " . $e->getMessage());
        }
    }
}

// Make the database instance globally available
$GLOBALS['db'] = Database::getInstance();
