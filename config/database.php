<?php
/**
 * Database Configuration
 * Assemblies of God Church House of Bread
 */

// Database configuration constants
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'aghob');
define('DB_CHARSET', 'utf8mb4');

/**
 * Database Connection Class (Singleton Pattern)
 */
class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        try {
            $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            if ($this->connection->connect_error) {
                throw new Exception("Connection failed: " . $this->connection->connect_error);
            }

            $this->connection->set_charset(DB_CHARSET);
        } catch (Exception $e) {
            die("Database Connection Error: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    // Prevent cloning
    private function __clone() {}

    // Prevent unserialization
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}

/**
 * Helper function to get database connection
 * @return mysqli
 */
function getDB() {
    static $db = null;
    if ($db === null) {
        $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($db->connect_error) {
            die("Database connection failed: " . $db->connect_error);
        }
        $db->set_charset(DB_CHARSET);
    }
    return $db;
}

/**
 * Execute a prepared statement and return results
 * @param string $sql SQL query with placeholders
 * @param array $params Parameters to bind
 * @param string $types Parameter types (s = string, i = integer, d = double, b = blob)
 * @return mysqli_result|bool
 */
function executeQuery($sql, $params = [], $types = '') {
    $db = getDB();
    $stmt = $db->prepare($sql);

    if (!$stmt) {
        error_log("SQL Error: " . $db->error);
        return false;
    }

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    return $result !== false ? $result : $stmt;
}

/**
 * Fetch all rows from a query
 * @param string $sql SQL query
 * @param array $params Parameters
 * @param string $types Parameter types
 * @return array
 */
function fetchAll($sql, $params = [], $types = '') {
    $result = executeQuery($sql, $params, $types);
    if ($result && $result instanceof mysqli_result) {
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    return [];
}

/**
 * Fetch single row from a query
 * @param string $sql SQL query
 * @param array $params Parameters
 * @param string $types Parameter types
 * @return array|null
 */
function fetchOne($sql, $params = [], $types = '') {
    $result = executeQuery($sql, $params, $types);
    if ($result && $result instanceof mysqli_result) {
        return $result->fetch_assoc();
    }
    return null;
}
