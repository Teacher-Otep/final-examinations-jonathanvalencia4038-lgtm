<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name       = $_POST['name'] ?? '';
    $surname    = $_POST['surname'] ?? '';
    $middlename = $_POST['middlename'] ?? '';
    $address    = $_POST['address'] ?? '';
    $contact    = $_POST['contact_number'] ?? '';

    $stmt = $pdo->prepare("INSERT INTO students (name, surname, middlename, address, contact_number) VALUES (?, ?, ?, ?, ?)");

    if ($stmt->execute([$name, $surname, $middlename, $address, $contact])) {
        header("Location: ../public/index.php?status=success&section=create");
        exit();
    } else {
        echo "Error inserting record.";
    }
}
