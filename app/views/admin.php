<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Mulai sesi jika belum dimulai
}

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Memasukkan file yang diperlukan
include_once '../models/Database.php';  // Untuk koneksi ke database
include_once '../controllers/AdminController.php';  // Controller admin

// Inisialisasi koneksi database
$database = new Database();
$db = $database->getConnection();

// Membuat objek controller admin
$adminController = new AdminController($db);

// Mendapatkan data siswa berdasarkan kelas
$students7 = $adminController->getStudentsByGrade(7);
$students8 = $adminController->getStudentsByGrade(8);
$students9 = $adminController->getStudentsByGrade(9);

// Menambahkan siswa baru jika form submit
if (isset($_POST['add_student'])) {
    $name = $_POST['name'];
    $grade = $_POST['grade'];
    $roll_number = $_POST['roll_number'];
    $adminController->addNewStudent($name, $grade, $roll_number);  // Panggil fungsi controller
}

// Menghapus siswa
if (isset($_GET['delete_student'])) {
    $studentId = $_GET['delete_student'];
    $adminController->removeStudent($studentId);  // Panggil fungsi controller
}

// Menambahkan guru baru jika form submit
if (isset($_POST['add_teacher'])) {
    $name = $_POST['teacher_name'];
    $subject = $_POST['subject'];
    $adminController->addNewTeacher($name, $subject);  // Panggil fungsi controller
}

// Menghapus guru
if (isset($_GET['delete_teacher'])) {
    $teacherId = $_GET['delete_teacher'];
    $adminController->removeTeacher($teacherId);  // Panggil fungsi controller
}

// Mendapatkan data siswa dan guru
$students = $adminController->getAllStudents();  // Ambil data siswa dari controller
$teachers = $adminController->getAllTeachers();  // Ambil data guru dari controller
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Admin</title>
    <link rel="stylesheet" href="css/admin.css">
</head>

<body>
    <h1>Halaman Admin</h1>
    <a href="logout.php"
        style="background-color: #dc3545; color: #fff; padding: 8px 16px; text-decoration: none; border-radius: 5px; font-size: 16px; transition: background-color 0.3s;"
        onmouseover="this.style.backgroundColor='#c82333'" onmouseout="this.style.backgroundColor='#dc3545'">Logout</a>

    <h2>Tambah Siswa</h2>
    <form method="POST" action="admin.php">
        <label for="name">Nama Siswa:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="grade">Kelas:</label>
        <input type="number" id="grade" name="grade" required><br><br>

        <label for="roll_number">Nomor Absen:</label>
        <input type="number" id="roll_number" name="roll_number" required><br><br>

        <input type="submit" name="add_student" value="Tambah Siswa">
    </form>

    <h2>Daftar Siswa Kelas 7</h2>
    <table border="1">
        <tr>
            <th>Nama</th>
            <th>Kelas</th>
            <th>Nomor Absen</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($students7 as $student): ?>
            <tr>
                <td><?php echo $student['name']; ?></td>
                <td><?php echo $student['grade']; ?></td>
                <td><?php echo $student['roll_number']; ?></td>
                <td>
                    <a href="admin.php?delete_student=<?php echo $student['id']; ?>">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h2>Daftar Siswa Kelas 8</h2>
    <table border="1">
        <tr>
            <th>Nama</th>
            <th>Kelas</th>
            <th>Nomor Absen</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($students8 as $student): ?>
            <tr>
                <td><?php echo $student['name']; ?></td>
                <td><?php echo $student['grade']; ?></td>
                <td><?php echo $student['roll_number']; ?></td>
                <td>
                    <a href="admin.php?delete_student=<?php echo $student['id']; ?>">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h2>Daftar Siswa Kelas 9</h2>
    <table border="1">
        <tr>
            <th>Nama</th>
            <th>Kelas</th>
            <th>Nomor Absen</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($students9 as $student): ?>
            <tr>
                <td><?php echo $student['name']; ?></td>
                <td><?php echo $student['grade']; ?></td>
                <td><?php echo $student['roll_number']; ?></td>
                <td>
                    <a href="admin.php?delete_student=<?php echo $student['id']; ?>">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h2>Tambah Guru</h2>
    <form method="POST" action="admin.php">
        <label for="teacher_name">Nama Guru:</label>
        <input type="text" id="teacher_name" name="teacher_name" required><br><br>

        <label for="subject">Mata Pelajaran:</label>
        <input type="text" id="subject" name="subject" required><br><br>

        <input type="submit" name="add_teacher" value="Tambah Guru">
    </form>

    <h2>Daftar Guru</h2>
    <table border="1">
        <tr>
            <th>Nama</th>
            <th>Mata Pelajaran</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($teachers as $teacher): ?>
            <tr>
                <td><?php echo $teacher['name']; ?></td>
                <td><?php echo $teacher['subject']; ?></td>
                <td>
                    <a href="admin.php?delete_teacher=<?php echo $teacher['id']; ?>">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>