<?php

class Student
{
    private $conn;
    private $table = 'students';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Menambahkan siswa baru
    public function addStudent($name, $grade, $roll_number)
    {
        $query = "INSERT INTO " . $this->table . " (name, grade, roll_number) 
                  VALUES (:name, :grade, :roll_number)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':grade', $grade);
        $stmt->bindParam(':roll_number', $roll_number);
        return $stmt->execute();
    }

    public function getStudentsByGrade($grade)
    {
        $query = "SELECT * FROM students WHERE grade = :grade";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':grade', $grade,  PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Mengembalikan hasil pencarian siswa berdasarkan kelas
    }

    // Mendapatkan semua siswa
    public function getAllStudents()
    {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Menghapus siswa
    public function deleteStudent($studentId)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $studentId);
        return $stmt->execute();
    }

    // Update siswa
    public function updateStudent($studentId, $name, $grade, $roll_number)
    {
        $query = "UPDATE " . $this->table . " 
                  SET name = :name, grade = :grade, roll_number = :roll_number 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':grade', $grade);
        $stmt->bindParam(':roll_number', $roll_number);
        $stmt->bindParam(':id', $studentId);
        return $stmt->execute();
    }
}
?>