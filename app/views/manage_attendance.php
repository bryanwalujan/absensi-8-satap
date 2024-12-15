<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Mulai sesi jika belum dimulai
}

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'guru') {
    header("Location: index.php");
    exit();
}

// Menghubungkan ke database dan model yang diperlukan
include_once '../controllers/AttendanceController.php';
include_once '../controllers/AdminController.php';
include_once '../models/Database.php';
include_once '../models/Attendance.php';

// Inisialisasi koneksi database dan model
$database = new Database();
$db = $database->getConnection();
$attendanceModel = new Attendance($db);
$attendanceController = new AttendanceController($attendanceModel);

// Inisialisasi AdminController untuk mendapatkan data siswa dan guru
$adminController = new AdminController($db);

// Ambil data guru untuk dropdown
$teachers = $adminController->getAllTeachers();

// Ambil data siswa ketika kelas dipilih
$selectedClass = $_POST['class'] ?? '';
$students = [];
if ($selectedClass) {
    $students = $adminController->getStudentsByGrade($selectedClass);
}

// Proses penyimpanan absensi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_attendance'])) {
    $teacher_id = $_POST['teacher_id'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $date = $_POST['date'] ?? date('Y-m-d');
    $student_statuses = $_POST['status'] ?? [];

    if ($teacher_id && $subject && !empty($student_statuses)) {
        foreach ($student_statuses as $student_id => $status) {
            if (!empty($status)) {
                $attendanceController->recordNewAttendance(
                    $student_id,
                    $teacher_id,
                    $date,
                    $subject,
                    $status
                );
            }
        }
        echo "<script>alert('Absensi berhasil disimpan!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Absensi - SMP Negeri 8 Satap Tondano</title>
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --success-color: #16a34a;
            --border-color: #e5e7eb;
            --header-bg: #f8fafc;
            --hover-bg: #f1f5f9;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9fafb;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }

        h1 {
            color: var(--primary-color);
            font-size: 2rem;
            margin-bottom: 2rem;
            text-align: center;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 1.5rem;
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #374151;
        }

        select,
        input[type="text"],
        input[type="date"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        select:focus,
        input[type="text"]:focus,
        input[type="date"]:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        table {
            width: 100%;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin: 2rem 0;
        }

        th {
            background-color: var(--header-bg);
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #1f2937;
        }

        td {
            padding: 1rem;
            border-top: 1px solid var(--border-color);
        }

        tr:hover {
            background-color: var(--hover-bg);
        }

        .submit-btn {
            background-color: var(--primary-color);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
            width: 100%;
        }

        .submit-btn:hover {
            background-color: var(--secondary-color);
        }

        .navigation {
            margin-top: 2rem;
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .nav-link {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            color: var(--primary-color);
            text-decoration: none;
            border-radius: 6px;
            transition: background-color 0.2s;
            font-weight: 500;
        }

        .nav-link:hover {
            background-color: var(--hover-bg);
        }

        select[name^="status"] {
            background-color: white;
            border: 1px solid var(--border-color);
            padding: 0.5rem;
            border-radius: 4px;
            width: 100%;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            table {
                display: block;
                overflow-x: auto;
            }

            .navigation {
                flex-direction: column;
                align-items: stretch;
            }

            .nav-link {
                text-align: center;
            }
        }

        /* Status badges */
        select[name^="status"] option[value="hadir"] {
            background-color: #dcfce7;
            color: #166534;
        }

        select[name^="status"] option[value="alpha"] {
            background-color: #fee2e2;
            color: #991b1b;
        }

        select[name^="status"] option[value="izin"] {
            background-color: #fff7ed;
            color: #9a3412;
        }

        select[name^="status"] option[value="sakit"] {
            background-color: #fef3c7;
            color: #92400e;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Kelola Absensi</h1>

        <!-- Form pemilihan kelas -->
        <form method="POST" action="">
            <div class="form-group">
                <label for="class">Pilih Kelas:</label>
                <select name="class" id="class" onchange="this.form.submit()">
                    <option value="">Pilih Kelas</option>
                    <option value="7" <?= $selectedClass == '7' ? 'selected' : '' ?>>Kelas 7</option>
                    <option value="8" <?= $selectedClass == '8' ? 'selected' : '' ?>>Kelas 8</option>
                    <option value="9" <?= $selectedClass == '9' ? 'selected' : '' ?>>Kelas 9</option>
                </select>
            </div>
        </form>

        <?php if ($selectedClass && !empty($students)): ?>
            <!-- Form absensi -->
            <form method="POST" action="">
                <input type="hidden" name="class" value="<?= $selectedClass ?>">

                <div class="form-group">
                    <label for="teacher_id">Guru:</label>
                    <select name="teacher_id" id="teacher_id" required>
                        <option value="">Pilih Guru</option>
                        <?php foreach ($teachers as $teacher): ?>
                            <option value="<?= $teacher['id'] ?>"><?= htmlspecialchars($teacher['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="subject">Mata Pelajaran:</label>
                    <input type="text" name="subject" id="subject" required>
                </div>

                <div class="form-group">
                    <label for="date">Tanggal:</label>
                    <input type="date" name="date" id="date" value="<?= date('Y-m-d') ?>" required>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Status Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $index => $student): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($student['name']) ?></td>
                                <td>
                                    <select name="status[<?= $student['id'] ?>]" required>
                                        <option value="">Pilih Status</option>
                                        <option value="Hadir">Hadir</option>
                                        <option value="Alpha">Alpha</option>
                                        <option value="Izin">Izin</option>
                                        <option value="Sakit">Sakit</option>
                                    </select>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="form-group" style="text-align: center;">
                    <button type="submit" name="submit_attendance" class="submit-btn">Simpan Absensi</button>
                </div>
            </form>
        <?php elseif ($selectedClass): ?>
            <p class="form-group" style="text-align: center;">Tidak ada siswa di kelas ini.</p>
        <?php endif; ?>

        <div class="navigation">
            <a href="dashboard.php" class="nav-link">Kembali ke Dashboard</a>
            <a href="attendance_summary.php" class="nav-link">Lihat Rekap Absensi</a>
        </div>
    </div>
</body>

</html>