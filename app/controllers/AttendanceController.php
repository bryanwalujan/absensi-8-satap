<?php
require_once '../models/Attendance.php';  // Pastikan path sudah benar
require_once '../models/Student.php';  // Pastikan path sudah benar
require_once '../models/Teacher.php';  // Pastikan path sudah benar
require_once '../controllers/AdminController.php';  // Pastikan path sudah benar

class AttendanceController
{
    private $attendanceModel;

    // Konstruktor untuk inisialisasi model Attendance
    public function __construct($attendanceModel)
    {
        $this->attendanceModel = $attendanceModel;
    }

    // Mendapatkan ringkasan absensi siswa
    public function getAttendanceSummary(): array
    {
        $date = date('Y-m-d'); // Tanggal hari ini
        return $this->attendanceModel->getAttendanceSummary($date); // Mengambil ringkasan absensi berdasarkan tanggal
    }

    // Mencatat absensi siswa baru
    public function recordNewAttendance($student_id, $teacher_id, $date, $subject, $status): void
    {
        // Validasi status
        $validStatuses = ['Hadir', 'Alpha', 'Izin', 'Sakit'];
        if (!in_array($status, $validStatuses)) {
            echo "Status tidak valid";
            return;
        }

        if (empty($student_id) || empty($teacher_id) || empty($date) || empty($subject) || empty($status)) {
            echo "Semua field harus diisi.";
            return;
        }

        $result = $this->attendanceModel->recordAttendance($student_id, $teacher_id, $date, $subject, $status);
        if (!empty($result)) {
            echo "Error: " . $result;
        } else {
            echo "";
        }
    }

    public function getAttendanceCountByStatus($date): array
    {
        $counts = [
            'hadir' => 0,
            'alpha' => 0,
            'izin' => 0,
            'sakit' => 0,
        ];

        $attendanceRecords = $this->attendanceModel->getAttendanceSummary($date);

        foreach ($attendanceRecords as $record) {
            switch ($record['status']) {
                case 'Hadir':
                    $counts['hadir']++;
                    break;
                case 'Alpha':
                    $counts['alpha']++;
                    break;
                case 'Izin':
                    $counts['izin']++;
                    break;
                case 'Sakit':
                    $counts['sakit']++;
                    break;
            }
        }

        return $counts;
    }

    // Mendapatkan rekap absensi siswa per kelas
    public function getAttendanceSummaryByClass($date)
    {
        return $this->attendanceModel->getAttendanceByClass($date);
    }

    public function getAttendanceSummaryByClassAndSubject($date, $subject)
    {
        return $this->attendanceModel->getAttendanceByClassAndSubject($date, $subject);
    }

    public function getAllSubjects()
    {
        return $this->attendanceModel->getAllSubjects();
    }


    // Mengubah status absensi berdasarkan ID
    public function changeAttendanceStatus($attendance_id, $status): void
    {
        if (empty($status)) {
            echo "Status harus diisi.";
            return;
        }

        $result = $this->attendanceModel->updateAttendanceStatus($attendance_id, $status);
        echo $result;
    }

    public function getAttendanceSummaryByDate($date): array
    {
        return $this->attendanceModel->getAttendanceSummary($date);
    }

    // Menghapus absensi berdasarkan ID
    public function removeAttendance($attendance_id): void
    {
        $result = $this->attendanceModel->deleteAttendance($attendance_id);
        echo $result;
    }

    public function getAttendanceSummaryByClassAndSubjectWithDate($date, $subject)
    {
        return $this->attendanceModel->getAttendanceByClassAndSubjectWithDate($date, $subject);
    }
}
?>