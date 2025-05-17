<?php

class Database {
    private static final $dbFile = __DIR__ . '/../data/scrabble.db';
    private static final $schemaFile = __DIR__ . '/../data/schema.sql';
    private static final $migrationPath = __DIR__ . '/../data/migrations/';
    
    private static $instance = null;
    private $pdo = null;

    public function __construct() {
        $newDB = !file_exists(self::$dbFile);

        try {
            if ($newDB) {
                touch(self::$dbFile);
                $this->connect();
                initializeSchema();
            }
        } catch (Exception $e) {
            die("Database conection failed: " . $e->getMessage());
        }
        
        if ($this->pdo === null) $this->connect();
        $this->runMigrations();
    }

    private function connect() {
        $this->pdo = new PDO('sqlite:' . self::$dbFile);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    private function initializeSchema() {
        $sql= file_get_contents(self::$schemaFile);
        try {
            $this->pdo->exec($sql);
            die("Initialised database, refresh to continue.");
        } catch (PDOException $e) {
            die("Failed to execute schema SQL: " . $e->getMessage());
        }
    }

    private function runMigrations() {
        $appliedMigrations = [];

        $stmt = $this->pdo->query("SELECT file FROM migrations");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $appliedMigrations[] = $row['file'];
        }
        
        $files = glob(self::$migrationPath . '/*.sql');
        sort($files);

        foreach ($files as $file) {
            $filename = basename($file);
            if (!in_array($filename, $appliedMigrations)) {
                $sql = file_get_contents($file);
                try {
                    $this->pdo->exec($sql);
                    $stmt = $this->pdo->prepare("INSERT INTO migrations (file, created_at) VALUES (?, datetime('now'))");
                    $stmt->execute([$filename]);
                    die("Applied migration: " . $filename . ",refresh to continue.");
                } catch (PDOException $e) {
                    die("Failed to apply migration $filename: " . $e->getMessage());
                }
            }
        }
    }
}

public static function getInstance() {
    if (self::$instance === null) {
        self::$instance = new self(__DIR__ . '/../data/scrabble.db');
    }
    return self::$instance;
}

public function getHandle() {
    return $this->pdo;
}

// Instantiate once for global use
$db = new Database();

// Make the database instance globally available
$GLOBALS['db'] = Database::getInstance();
