<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id'] ?? 0);

    if ($id > 0) {
        $stmt = $conn->prepare("DELETE FROM students WHERE id=?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                header("Location: ../index.php?status=deleted&section=delete");
            } else {
                header("Location: ../index.php?status=notfound&section=delete");
            }
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        header("Location: ../index.php?status=invalid&section=delete");
        exit();
    }
}

$conn->close();
