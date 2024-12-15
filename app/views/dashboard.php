<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Mulai sesi jika belum dimulai
}

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'guru') {
    header("Location: index.php");
    exit();
}

// Include file koneksi database dan model
require_once '../models/Database.php'; // Pastikan path sudah benar
require_once '../models/Attendance.php'; // Pastikan path sudah benar
require_once '../controllers/AttendanceController.php'; // Pastikan path sudah benar

// Inisialisasi koneksi database
$database = new Database();
$db = $database->getConnection();

// Inisialisasi model Attendance
$attendanceModel = new Attendance($db);

// Inisialisasi controller Attendance
$attendanceController = new AttendanceController($attendanceModel);

// Mendapatkan jumlah absensi per status
$attendanceCounts = $attendanceController->getAttendanceCountByStatus(date('Y-m-d'));

// Tampilkan ringkasan absensi
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - SMP Negeri 8 Satap Tondano</title>
    <link rel="stylesheet" href="css/beranda.css"> <!-- Ganti dengan path CSS Anda -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9fafb;
            background-image: url('picture/Foto_sekolah.jpg');
            background-size: cover;
            background-position: right;
            background-attachment: fixed;
            background-repeat: no-repeat;
            margin: 0;
            padding: 0;
            display: flex;
        }

        /* Main Content Styling */
        .content {
            padding: 3rem 2rem;
            flex: 1;
        }

        .header {
            margin-bottom: 2rem;
            text-align: center;
        }

        .header h1 {
            font-size: 2rem;
            font-weight: 600;
            color: #fefefe;
            margin-bottom: 1rem;
        }

        .header p {
            font-size: 1.1rem;
            color: #fefefe;
            max-width: 700px;
            margin: 0 auto;
        }

        /* Attendance Management Section */
        .attendance-management {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            text-align: center;
            transition: all 0.3s ease-in-out;
        }

        .attendance-management h2 {
            color: #007bff;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .action-button {
            background-color: #28a745;
            color: #fff;
            padding: 1rem 2rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 1rem;
            transition: background-color 0.3s ease, transform 0.2s ease-in-out;
            margin-bottom: 1rem;
        }

        .action-button:hover {
            background-color: #218838;
            transform: translateY(-3px);
        }

        .action-button:active {
            transform: translateY(2px);
        }

        /* Responsiveness */
        @media (max-width: 768px) {
            .content {
                padding: 2rem;
            }

            .attendance-management {
                padding: 1.5rem;
            }

            .action-button {
                width: 100%;
                margin-bottom: 1.5rem;
            }
        }

        /* Sidebar Style */
        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #007bff;
            padding-top: 20px;
            color: white;
            font-family: Arial, sans-serif;
        }

        .sidebar a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
            transition: background-color 0.3s;
        }

        .sidebar a:hover {
            background-color: #0056b3;
        }

        /* Main content style */
        .content {
            margin-left: 250px;
            /* Adjust content to the right side of the sidebar */
            padding: 20px;
        }

        .header h1 {
            color: #fefefe;
        }

        .action-button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .action-button:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">

        <a href="index.php">Beranda</a>
        <a href="manage_attendance.php">Input Absensi Siswa</a>
        <a href="attendance_summary.php">Lihat Rekap Absensi</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <main class="content">
        <header class="header">
            <h1>Selamat Datang di Sistem Absensi</h1>
            <p>Gunakan sistem ini untuk melakukan input absensi siswa dan melihat rekap absensi siswa.</p>
        </header>

        <!-- Attendance Management Section -->
        <section class="attendance-management">
            <h2>Aksi Absensi</h2>
            <button class="action-button" onclick="window.location.href='manage_attendance.php'">Input Absensi
                Siswa</button>
            <button class="action-button" onclick="window.location.href='attendance_summary.php'">Lihat Rekap
                Absensi</button>
        </section>
    </main>

    <script src="script.js"></script> <!-- Ganti dengan path script.js Anda -->
</body>

</html>