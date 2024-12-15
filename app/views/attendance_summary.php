<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'guru') {
    header("Location: index.php");
    exit();
}

require_once '../models/Database.php';
require_once '../models/Attendance.php';
require_once '../models/Student.php';
require_once '../controllers/AttendanceController.php';

// Inisialisasi koneksi database
$database = new Database();
$db = $database->getConnection();

// Inisialisasi model Attendance
$attendanceModel = new Attendance($db);

// Inisialisasi controller Attendance
$attendanceController = new AttendanceController($attendanceModel);

// Mendapatkan daftar mata pelajaran
$subjects = $attendanceController->getAllSubjects();

// Mendapatkan tanggal yang dipilih
$selectedDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Mendapatkan mata pelajaran yang dipilih
$selectedSubject = isset($_GET['subject']) ? $_GET['subject'] : '';

// Mendapatkan rekap absensi siswa per kelas dan mata pelajaran jika ada yang dipilih
$attendanceSummary = [];
if ($selectedSubject) {
    $attendanceSummary = $attendanceController->getAttendanceSummaryByClassAndSubjectWithDate($selectedDate, $selectedSubject);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Absensi Siswa</title>
    <style>
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9fafb;
        }

        .content {
            margin: 20px;
        }

        .header {
            background-color: #007bff;
            color: #fff;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            font-size: 2rem;
            margin: 0;
        }

        .filter-form {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .date-select, .subject-select {
            width: 250px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            margin-right: 10px;
            transition: all 0.3s;
        }

        .submit-btn {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .attendance-table th, .attendance-table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .attendance-table th {
            background-color: #f4f4f4;
            color: #333;
        }

        .attendance-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .actions {
            text-align: center;
            margin-top: 30px;
        }

        .actions .button {
            padding: 12px 25px;
            background-color: #28a745;
            color: #fff;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            margin: 10px;
            transition: background-color 0.3s ease;
        }
    </style>
</head>
<body>
    <div class="content">
        <header class="header">
            <h1>Rekap Absensi Siswa - <?php echo $selectedDate; ?></h1>
            <p>Rekap absensi siswa per mata pelajaran</p>
        </header>

        <div class="filter-form">
            <form method="GET" action="">
                <input type="date" name="date" value="<?php echo htmlspecialchars($selectedDate); ?>" class="date-select">
                <select name="subject" class="subject-select">
                    <option value="">Pilih Mata Pelajaran</option>
                    <?php foreach ($subjects as $subject): ?>
                        <option value="<?php echo htmlspecialchars($subject); ?>" <?php echo $selectedSubject === $subject ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($subject); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="submit-btn">Tampilkan</button>
            </form>
        </div>

        <section class="attendance-summary">
            <?php if ($selectedSubject): ?>
                <h2>Mata Pelajaran: <?php echo htmlspecialchars($selectedSubject); ?></h2>
                <?php
                $grades = [7, 8, 9];
                foreach ($grades as $grade) {
                    $hasStudents = false;
                    foreach ($attendanceSummary as $attendance) {
                        if ($attendance['student_grade'] == $grade) {
                            $hasStudents = true;
                            break;
                        }
                    }

                    if ($hasStudents) {
                        echo "<h3>Kelas $grade</h3>";
                        echo "<table class='attendance-table'>
                                <thead>
                                    <tr>
                                        <th>Nama Siswa</th>
                                        <th>Hadir</th>
                                        <th>Alpha</th>
                                        <th>Izin</th>
                                        <th>Sakit</th>
                                    </tr>
                                </thead>
                                <tbody>";

                        foreach ($attendanceSummary as $attendance) {
                            if ($attendance['student_grade'] == $grade) {
                                echo "<tr>
                                        <td>{$attendance['student_name']}</td>
                                        <td>{$attendance['hadir']}</td>
                                        <td>{$attendance['alpha']}</td>
                                        <td>{$attendance['izin']}</td>
                                        <td>{$attendance['sakit']}</td>
                                    </tr>";
                            }
                        }

                        echo "</tbody></table><br>";
                    }
                }
                ?>
            <?php else: ?>
                <p class="text-center">Silakan pilih mata pelajaran untuk melihat rekap absensi.</p>
            <?php endif; ?>
        </section>

        <div class="actions">
            <a href="dashboard.php" class="button">Kembali ke Dashboard</a>
            <a href="manage_attendance.php" class="button">Tambah Absensi</a>
        </div>
    </div>
</body>
</html>