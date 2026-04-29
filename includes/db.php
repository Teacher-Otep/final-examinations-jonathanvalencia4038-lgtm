<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbstudents";

mysqli_report(MYSQLI_REPORT_OFF);
$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
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
        <p style="color:#999;font-size:13px;">Error: ' . htmlspecialchars($conn->connect_error) . '</p>
    </div>');
}

$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
$conn->select_db($dbname);

$conn->query("CREATE TABLE IF NOT EXISTS students (
    id int(11) NOT NULL AUTO_INCREMENT,
    name varchar(100) NOT NULL,
    surname varchar(100) NOT NULL,
    middlename varchar(100) DEFAULT NULL,
    address text DEFAULT NULL,
    contact_number varchar(20) DEFAULT NULL,
    PRIMARY KEY (id)
)");

$conn->query("ALTER TABLE students MODIFY COLUMN contact_number VARCHAR(20) DEFAULT NULL");
