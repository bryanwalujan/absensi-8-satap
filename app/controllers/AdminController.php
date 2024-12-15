<?php
include_once '../models/Student.php';
include_once '../models/Teacher.php';

class AdminController
{
    private $studentModel;
    private $teacherModel;

    public function __construct($db)
    {
        $this->studentModel = new Student($db);
        $this->teacherModel = new Teacher($db);
    }

    // Fungsi untuk mendapatkan semua siswa
    public function getAllStudents()
    {
        return $this->studentModel->getAllStudents(); // Mengembalikan hasil dari model
    }

    // Menambah siswa baru
    public function addNewStudent($name, $grade, $roll_number)
    {
        if (empty($name) || empty($grade) || empty($roll_number)) {
            echo "<script>alert('Semua field harus diisi.');</script>";
            return;
        }

        $response = $this->studentModel->addStudent($name, $grade, $roll_number);
        if ($response) {
            echo "<script>alert('Siswa berhasil ditambahkan.'); window.location.href='admin.php';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan. Silakan coba lagi.');</script>";
        }
    }

    // Mendapatkan siswa berdasarkan kelas
    public function getStudentsByGrade($grade)
    {
        return $this->studentModel->getStudentsByGrade($grade); // Mengambil data siswa berdasarkan kelas
    }




    // Mengedit siswa
    public function editStudent($id, $name, $grade, $roll_number)
    {
        if (empty($name) || empty($grade) || empty($roll_number)) {
            echo "<script>alert('Semua field harus diisi.');</script>";
            return;
        }

        $response = $this->studentModel->updateStudent($id, $name, $grade, $roll_number);
        if ($response) {
            echo "<script>alert('Siswa berhasil diperbarui.'); window.location.href='admin.php';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan. Silakan coba lagi.');</script>";
        }
    }

    // Menghapus siswa
    public function removeStudent($id)
    {
        $response = $this->studentModel->deleteStudent($id);
        if ($response) {
            echo "<script>alert('Siswa berhasil dihapus.'); window.location.href='admin.php';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan. Silakan coba lagi.');</script>";
        }
    }

    public function getStudentsByClass($class) {
        return $this->studentModel->getStudentsByGrade($class); // Ambil siswa berdasarkan kelas
    }
    

    // Mendapatkan semua guru
    public function getAllTeachers()
    {
        return $this->teacherModel->getAllTeachers(); // Mengembalikan hasil dari model
    }

    // Menambah guru baru
    public function addNewTeacher($name, $subject)
    {
        if (empty($name) || empty($subject)) {
            echo "<script>alert('Semua field harus diisi.');</script>";
            return;
        }

        $response = $this->teacherModel->addTeacher($name, $subject);
        if ($response) {
            echo "<script>alert('Guru berhasil ditambahkan.'); window.location.href='admin.php';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan. Silakan coba lagi.');</script>";
        }
    }

    // Mengedit guru
    public function editTeacher($id, $name, $subject)
    {
        if (empty($name) || empty($subject)) {
            echo "<script>alert('Semua field harus diisi.');</script>";
            return;
        }

        $response = $this->teacherModel->updateTeacher($id, $name, $subject);
        if ($response) {
            echo "<script>alert('Guru berhasil diperbarui.'); window.location.href='admin.php';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan. Silakan coba lagi.');</script>";
        }
    }

    // Menghapus guru
    public function removeTeacher($id)
    {
        $response = $this->teacherModel->deleteTeacher($id);
        if ($response) {
            echo "<script>alert('Guru berhasil dihapus.'); window.location.href='admin.php';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan. Silakan coba lagi.');</script>";
        }
    }
}
?>