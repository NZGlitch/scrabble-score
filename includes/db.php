<?php
class Database {
    private static $instance = null;
    private $db;

    private function __construct($dbPath) {
        $newDB = !file_exists($dbPath);

        try {
            if ($newDB) {
                // Check if the database file exists
                if (!file_exists($dbPath)) {
                // Create the database file
                touch($dbFile);
                $this->db = new SQLite3($dbPath);
                $this->initializeSchema();
            } else { 
                $this->db = new SQLite3($dbPath);
            }
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

    public function getHandle() {
        return $this->db;
    }

    private function initializeSchema() {
        $schemaFile = __DIR__ . '/schema.sql';

        if (!file_exists($schemaFile)) {
            die("Schema file not found: $schemaFile");
        }

        $schemaSql = file_get_contents($schemaFile);
        if (!$this->db->exec($schemaSql)) {
            die("Failed to execute schema SQL: " . $this->db->lastErrorMsg());
        }
    }
}

// Make the database instance globally available
$GLOBALS['db'] = Database::getInstance();
