<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id'] ?? 0);

    if ($id > 0) {
        $stmt = $pdo->prepare("DELETE FROM students WHERE id=?");
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            header("Location: ../public/index.php?status=deleted&section=delete");
        } else {
            header("Location: ../public/index.php?status=notfound&section=delete");
        }
        exit();
    } else {
        header("Location: ../public/index.php?status=invalid&section=delete");
        exit();
    }
}
