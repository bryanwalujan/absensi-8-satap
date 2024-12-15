<?php

class Teacher
{
    private $conn;
    private $table = 'teachers';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Menambahkan guru baru
    public function addTeacher($name, $subject)
    {
        $query = "INSERT INTO " . $this->table . " (name, subject) 
                  VALUES (:name, :subject)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':subject', $subject);
        return $stmt->execute();
    }

    // Mendapatkan semua guru
    public function getAllTeachers()
    {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteTeacher($teacherId)
    {
        // Pertama, hapus semua data absensi yang terkait dengan guru ini
        $query = "DELETE FROM attendance WHERE teacher_id = :teacher_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':teacher_id', $teacherId);
        $stmt->execute();
    
        // Kemudian, hapus data guru
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $teacherId);
        return $stmt->execute();
    }
      

    // Update guru
    public function updateTeacher($teacherId, $name, $subject)
    {
        $query = "UPDATE " . $this->table . " 
                  SET name = :name, subject = :subject 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':id', $teacherId);
        return $stmt->execute();
    }
}
?>
