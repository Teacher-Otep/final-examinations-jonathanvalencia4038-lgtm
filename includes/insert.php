<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name       = $_POST['name'] ?? '';
    $surname    = $_POST['surname'] ?? '';
    $middlename = $_POST['middlename'] ?? '';
    $address    = $_POST['address'] ?? '';
    $contact    = $_POST['contact_number'] ?? '';

    $stmt = $conn->prepare("INSERT INTO students (name, surname, middlename, address, contact_number) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $surname, $middlename, $address, $contact);

    if ($stmt->execute()) {
        header("Location: ../index.php?status=success&section=create");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
