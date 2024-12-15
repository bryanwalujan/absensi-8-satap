<?php
include_once __DIR__ . '/../models/Database.php';
include_once __DIR__ . '/../models/User.php'; // Masukkan model User

class UserController {
    private $conn;
    public $userModel;

    public function __construct($db) {
        $this->conn = $db;
        $this->userModel = new User($db); // Inisialisasi model User
    }

    // Mendapatkan semua pengguna
    public function getUsers() {
        // Query untuk mengambil semua pengguna, kecuali password
        $query = "SELECT id, username, email, phone, address, role FROM users"; 
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        // Menarik hasil sebagai array asosiatif
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $users;
    }

    // Menghapus pengguna
    public function deleteUser($id): bool {
        return $this->userModel->deleteUserById($id); // Memanggil metode dari model User
    }
}
?>