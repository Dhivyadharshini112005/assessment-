```php
<?php

$host = getenv('DB_HOST') ?: '';
$db   = getenv('DB_NAME') ?: 'defaultdb';
$user = getenv('DB_USER') ?: '';
$pass = getenv('DB_PASSWORD') ?: '';
$port = getenv('DB_PORT') ?: '22795';
$charset = 'utf8mb4';

if ($host === '' || $user === '' || $pass === '') {
    error_log('Database configuration is incomplete.');
    http_response_code(500);
    exit('Database configuration is incomplete. Please check Render environment variables.');
}

$dsn = "mysql:host={$host};port={$port};dbname={$db};charset={$charset}";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

/*
 * Use Aiven's CA certificate for verified TLS.
 * Set DB_SSL_CA to the certificate's absolute path
 * inside the running container.
 */
$caPath = getenv('DB_SSL_CA');

if ($caPath && is_file($caPath)) {
    $options[PDO::MYSQL_ATTR_SSL_CA] = $caPath;
    $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = true;
}

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    error_log('Database connection error: ' . $e->getMessage());
    http_response_code(500);
    exit('Database connection failed. Please check the database configuration.');
}
```
