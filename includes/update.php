<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id         = intval($_POST['id'] ?? 0);
    $name       = $_POST['name'] ?? '';
    $surname    = $_POST['surname'] ?? '';
    $middlename = $_POST['middlename'] ?? '';
    $address    = $_POST['address'] ?? '';
    $contact    = $_POST['contact_number'] ?? '';

    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE students SET name=?, surname=?, middlename=?, address=?, contact_number=? WHERE id=?");
        $stmt->execute([$name, $surname, $middlename, $address, $contact, $id]);

        if ($stmt->rowCount() > 0) {
            header("Location: ../public/index.php?status=updated&section=update");
        } else {
            $check = $pdo->prepare("SELECT id FROM students WHERE id=?");
            $check->execute([$id]);
            if ($check->fetch()) {
                header("Location: ../public/index.php?status=updated&section=update");
            } else {
                header("Location: ../public/index.php?status=notfound&section=update");
            }
        }
        exit();
    } else {
        header("Location: ../public/index.php?status=invalid&section=update");
        exit();
    }
}
