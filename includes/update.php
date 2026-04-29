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
        $stmt = $conn->prepare("UPDATE students SET name=?, surname=?, middlename=?, address=?, contact_number=? WHERE id=?");
        $stmt->bind_param("sssssi", $name, $surname, $middlename, $address, $contact, $id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                header("Location: ../index.php?status=updated&section=update");
            } else {

                $check = $conn->prepare("SELECT id FROM students WHERE id=?");
                $check->bind_param("i", $id);
                $check->execute();
                $check->store_result();
                if ($check->num_rows > 0) {

                    header("Location: ../index.php?status=updated&section=update");
                } else {

                    header("Location: ../index.php?status=notfound&section=update");
                }
                $check->close();
            }
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        header("Location: ../index.php?status=invalid&section=update");
        exit();
    }
}

$conn->close();
