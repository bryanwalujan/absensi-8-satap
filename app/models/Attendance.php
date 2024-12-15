<?php
class Attendance
{
    private $conn;
    private $table = "attendance"; // Nama tabel yang menyimpan data absensi

    public $id;
    public $student_id;
    public $teacher_id;
    public $date;
    public $subject;
    public $status;

    // Konstruktor untuk menghubungkan ke database
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Fungsi untuk mencatat absensi
    public function recordAttendance($student_id, $teacher_id, $date, $subject, $status): string
    {
        // Tambahkan logging
        error_log("Mencoba menyimpan absensi: Student=$student_id, Status=$status");

        if (empty($student_id) || empty($teacher_id) || empty($date) || empty($subject) || empty($status)) {
            error_log("Ada field yang kosong");
            return "Semua field harus diisi.";
        }

        $query = "INSERT INTO " . $this->table . " (student_id, teacher_id, date, subject, status) 
                  VALUES (:student_id, :teacher_id, :date, :subject, :status)";

        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            error_log("Gagal prepare query: " . implode(", ", $this->conn->errorInfo()));
            return "Gagal menyiapkan query: " . implode(", ", $this->conn->errorInfo());
        }

        // Bind parameter
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':teacher_id', $teacher_id);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':status', $status);

        // Eksekusi query
        if ($stmt->execute()) {
            error_log("");
            return "";
        } else {
            $errorInfo = $stmt->errorInfo();
            error_log("Gagal menyimpan absensi: " . $errorInfo[2]);
            return "Terjadi kesalahan: " . $errorInfo[2];
        }
    }


    // Fungsi untuk mendapatkan absensi berdasarkan siswa dan tanggal
    public function getAttendanceByStudentAndDate($student_id, $date): array
    {
        $query = "SELECT * FROM " . $this->table . " WHERE student_id = :student_id AND date = :date";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':date', $date);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fungsi untuk mendapatkan ringkasan absensi siswa berdasarkan tanggal
    public function getAttendanceSummary($date): array
    {
        $query = "SELECT attendance.status, COUNT(*) as total
                  FROM " . $this->table . " AS attendance
                  WHERE attendance.date = :date
                  GROUP BY attendance.status";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':date', $date);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Mengembalikan jumlah status absensi
    }

    // Mendapatkan rekap absensi siswa per kelas
    public function getAttendanceByClass($date)
    {
        $query = "
             SELECT 
                 s.name AS student_name,
                 s.grade AS student_grade,
                 COUNT(CASE WHEN a.status = 'Hadir' THEN 1 END) AS hadir,
                 COUNT(CASE WHEN a.status = 'Alpha' THEN 1 END) AS alpha,
                 COUNT(CASE WHEN a.status = 'Izin' THEN 1 END) AS izin,
                 COUNT(CASE WHEN a.status = 'Sakit' THEN 1 END) AS sakit
             FROM 
                 attendance a
             JOIN 
                 students s ON a.student_id = s.id
             WHERE 
                 a.date = :date
             GROUP BY 
                 s.id
             ORDER BY 
                 s.grade, s.name
         ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':date', $date);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAttendanceByClassAndSubject($date, $subject)
    {
        $query = "
        SELECT 
            s.name AS student_name,
            s.grade AS student_grade,
            COUNT(CASE WHEN a.status = 'Hadir' THEN 1 END) AS hadir,
            COUNT(CASE WHEN a.status = 'Alpha' THEN 1 END) AS alpha,
            COUNT(CASE WHEN a.status = 'Izin' THEN 1 END) AS izin,
            COUNT(CASE WHEN a.status = 'Sakit' THEN 1 END) AS sakit
        FROM 
            attendance a
        JOIN 
            students s ON a.student_id = s.id
        WHERE 
            a.date = :date
            AND a.subject = :subject
        GROUP BY 
            s.id
        ORDER BY 
            s.grade, s.name
    ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':subject', $subject);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAttendanceByClassAndSubjectWithDate($date, $subject)
    {
        $query = "
        SELECT 
            s.name AS student_name,
            s.grade AS student_grade,
            COUNT(CASE WHEN a.status = 'Hadir' THEN 1 END) AS hadir,
            COUNT(CASE WHEN a.status = 'Alpha' THEN 1 END) AS alpha,
            COUNT(CASE WHEN a.status = 'Izin' THEN 1 END) AS izin,
            COUNT(CASE WHEN a.status = 'Sakit' THEN 1 END) AS sakit
        FROM 
            attendance a
        JOIN 
            students s ON a.student_id = s.id
        WHERE 
            a.date = :date
            AND a.subject = :subject
        GROUP BY 
            s.id
        ORDER BY 
            s.grade, s.name
    ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':subject', $subject);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tambahkan fungsi untuk mendapatkan daftar mata pelajaran
    public function getAllSubjects()
    {
        $query = "SELECT DISTINCT subject FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Fungsi untuk mengubah status absensi berdasarkan ID
    public function updateAttendanceStatus($attendance_id, $status): string
    {
        $query = "UPDATE " . $this->table . " SET status = :status WHERE id = :attendance_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':attendance_id', $attendance_id);

        if ($stmt->execute()) {
            return "Status absensi berhasil diperbarui.";
        } else {
            return "Terjadi kesalahan. Silakan coba lagi.";
        }
    }

    // Fungsi untuk menghapus absensi berdasarkan ID
    public function deleteAttendance($attendance_id): string
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :attendance_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':attendance_id', $attendance_id);

        if ($stmt->execute()) {
            return "Absensi berhasil dihapus.";
        } else {
            return "Terjadi kesalahan. Silakan coba lagi.";
        }
    }
}
?>