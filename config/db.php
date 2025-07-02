
<?php
// Load environment variables from .env if present
$envFile = dirname(__DIR__) . '/.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#')) {
            continue;
        }
        [$key, $value] = array_map('trim', explode('=', $line, 2));
        $_ENV[$key] = $value;
    }
}

$host = $_ENV['DB_HOST'] ?? 'localhost';
$db   = $_ENV['DB_NAME'] ?? 'tickets_db';
$user = $_ENV['DB_USER'] ?? 'root';
$pass = $_ENV['DB_PASS'] ?? '';
$charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $conn = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    if (!is_dir(__DIR__ . '/../logs')) {
        mkdir(__DIR__ . '/../logs', 0777, true);
    }
    error_log($e->getMessage() . "\n", 3, __DIR__ . '/../logs/error.log');
    die('Error de conexión a la base de datos.');
}
?>
