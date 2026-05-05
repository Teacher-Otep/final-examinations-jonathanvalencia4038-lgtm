<?php
$host = 'localhost';
$db   = 'dbstudents';
$user = 'root';
$pass = '';
$port = '3306';
$charset = 'utf8mb4';

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO("mysql:host=$host;port=$port;charset=$charset", $user, $pass, $options);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db`");

    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=$charset", $user, $pass, $options);

    $pdo->exec("CREATE TABLE IF NOT EXISTS students (
        id INT(11) NOT NULL AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        surname VARCHAR(100) NOT NULL,
        middlename VARCHAR(100) DEFAULT NULL,
        address TEXT DEFAULT NULL,
        contact_number VARCHAR(20) DEFAULT NULL,
        PRIMARY KEY (id)
    )");

    $pdo->exec("ALTER TABLE students MODIFY COLUMN contact_number VARCHAR(20) DEFAULT NULL");
} catch (\PDOException $e) {
    die('
    <div style="font-family:Segoe UI,sans-serif;max-width:600px;margin:60px auto;padding:30px;
                border:2px solid #f44336;border-radius:12px;background:#fff3f3;text-align:center;">
        <h2 style="color:#f44336;">&#9888; Database Connection Failed</h2>
        <p style="color:#555;">MySQL is not running. Please:</p>
        <ol style="text-align:left;color:#555;">
            <li>Open the <strong>XAMPP Control Panel</strong></li>
            <li>Click <strong>Start</strong> next to <strong>MySQL</strong></li>
            <li>Click <strong>Start</strong> next to <strong>Apache</strong></li>
            <li>Reload this page</li>
        </ol>
        <p style="color:#999;font-size:13px;">Error: ' . htmlspecialchars($e->getMessage()) . '</p>
    </div>');
}
