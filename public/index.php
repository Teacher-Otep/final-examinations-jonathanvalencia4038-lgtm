<?php
require_once __DIR__ . '/includes/db.php';

$students = [];
$result = $conn->query("SELECT * FROM students ORDER BY id ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}

$fetchedStudent = null;
if (!empty($_GET['fetch_id'])) {
    $fid = intval($_GET['fetch_id']);
    $fstmt = $conn->prepare("SELECT * FROM students WHERE id=?");
    $fstmt->bind_param("i", $fid);
    $fstmt->execute();
    $fres = $fstmt->get_result();
    $fetchedStudent = $fres->fetch_assoc();
    $fstmt->close();

    if (!$fetchedStudent) {
        header("Location: index.php?status=notfound&section=update");
        exit();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KSU Student Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav class="navbar">
        <div class="brand-container">
            <img src="images/KSU_Logo.png" id="logo" onclick="showSection('home')" style="cursor: pointer;" alt="Logo">
            <span class="brand-text">Kalinga State University</span>
        </div>
        <button class="menu-toggle" onclick="toggleMenu()">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="nav-buttons-container" id="navMenu">
            <button class="navbarbuttons nav-create" onclick="showSection('create')"> Create </button>
            <button class="navbarbuttons nav-read" onclick="showSection('read')"> Read </button>
            <button class="navbarbuttons nav-update" onclick="showSection('update')"> Update </button>
            <button class="navbarbuttons nav-delete" onclick="showSection('delete')"> Delete </button>
        </div>
    </nav>

    <section id="home" class="homecontent">
        <h1 class="splash">Welcome to Student Management System</h1>
        <h2 class="splash">A Project in Integrative Programming Technologies</h2>
    </section>

    <section id="create" class="content">
        <h1 class="contenttitle"> Insert New Student </h1>
        <form action="includes/insert.php" method="POST">
            <div class="input-container">
                <input type="text" name="surname" id="surname" class="field" placeholder=" " required>
                <label for="surname" class="floating-label">Surname</label>
            </div>

            <div class="input-container">
                <input type="text" name="name" id="name" class="field" placeholder=" " required>
                <label for="name" class="floating-label">Name</label>
            </div>

            <div class="input-container">
                <input type="text" name="middlename" id="middlename" class="field" placeholder=" ">
                <label for="middlename" class="floating-label">Middle name</label>
            </div>

            <div class="input-container">
                <input type="text" name="address" id="address" class="field" placeholder=" ">
                <label for="address" class="floating-label">Address</label>
            </div>

            <div class="input-container">
                <input type="tel" name="contact_number" id="contact_number" class="field" placeholder=" "
                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)" required maxlength="11">
                <label for="contact_number" class="floating-label">Mobile Number</label>
            </div>

            <div id="btncontainer">
                <button type="reset" id="clrbtn" class="btns">Clear Fields</button>
                <button type="submit" id="savebtn" class="btns">Save</button>
            </div>
        </form>

        <div id="success-toast" class="toast toast-hidden">
            Registration Successful!
        </div>
    </section>

    <section id="read" class="content">
        <h1 class="contenttitle"> View Students </h1>
        <?php if (empty($students)): ?>
            <p class="label">No students found.</p>
        <?php else: ?>
            <table class="student-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Surname</th>
                        <th>Name</th>
                        <th>Middle Name</th>
                        <th>Address</th>
                        <th>Mobile Number</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= htmlspecialchars($student['id']) ?></td>
                            <td><?= htmlspecialchars($student['surname']) ?></td>
                            <td><?= htmlspecialchars($student['name']) ?></td>
                            <td><?= htmlspecialchars($student['middlename']) ?></td>
                            <td><?= htmlspecialchars($student['address']) ?></td>
                            <td><?= htmlspecialchars($student['contact_number']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>

    <section id="update" class="content">
        <h1 class="contenttitle"> Update Student Records </h1>

        <form action="index.php" method="GET" id="fetchForm">
            <input type="hidden" name="section" value="update">
            <div class="input-container">
                <input type="number" name="fetch_id" id="fetch_id" class="field" placeholder=" "
                    value="<?= htmlspecialchars($_GET['fetch_id'] ?? '') ?>" required>
                <label for="fetch_id" class="floating-label">Student ID</label>
            </div>
            <div class="btncontainer" style="margin-top:15px;">
                <div style="width: 48%;"></div>
                <button type="submit" class="btns">Find Student</button>
            </div>
        </form>

        <?php if ($fetchedStudent): ?>
            <form action="includes/update.php" method="POST" style="margin-top:20px;">
                <input type="hidden" name="id" value="<?= $fetchedStudent['id'] ?>">

                <div class="input-container">
                    <input type="text" name="surname" id="upd_surname" class="field" placeholder=" " required
                        value="<?= htmlspecialchars($fetchedStudent['surname']) ?>">
                    <label for="upd_surname" class="floating-label">Surname</label>
                </div>

                <div class="input-container">
                    <input type="text" name="name" id="upd_name" class="field" placeholder=" " required
                        value="<?= htmlspecialchars($fetchedStudent['name']) ?>">
                    <label for="upd_name" class="floating-label">Name</label>
                </div>

                <div class="input-container">
                    <input type="text" name="middlename" id="upd_middlename" class="field" placeholder=" "
                        value="<?= htmlspecialchars($fetchedStudent['middlename']) ?>">
                    <label for="upd_middlename" class="floating-label">Middle name</label>
                </div>

                <div class="input-container">
                    <input type="text" name="address" id="upd_address" class="field" placeholder=" "
                        value="<?= htmlspecialchars($fetchedStudent['address']) ?>">
                    <label for="upd_address" class="floating-label">Address</label>
                </div>

                <div class="input-container">
                    <input type="tel" name="contact_number" id="upd_contact" class="field" placeholder=" "
                        value="<?= htmlspecialchars($fetchedStudent['contact_number']) ?>"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)" required maxlength="11">
                    <label for="upd_contact" class="floating-label">Mobile Number</label>
                </div>

                <div id="upd-btncontainer" class="btncontainer">
                    <button type="reset" class="btns">Reset</button>
                    <button type="submit" class="btns">Update Record</button>
                </div>
            </form>
        <?php endif; ?>
    </section>

    <section id="delete" class="content">
        <h1 class="contenttitle"> Remove Student Records </h1>
        <form action="includes/delete.php" method="POST">
            <div class="input-container">
                <input type="number" name="id" id="del_id" class="field" placeholder=" " required>
                <label for="del_id" class="floating-label">Student ID</label>
            </div>

            <div class="btncontainer" style="margin-top:15px;">
                <div style="width: 48%;"></div>
                <button type="submit" class="btns" id="delbtn">Delete Student</button>
            </div>
        </form>
    </section>

    <div id="updated-toast" class="toast toast-hidden">Record updated successfully!</div>
    <div id="deleted-toast" class="toast toast-hidden">Student deleted successfully!</div>
    <div id="notfound-toast" class="toast toast-hidden">No student found with that ID.</div>





    <script src="script.js"></script>
</body>

</html>
