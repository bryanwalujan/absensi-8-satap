<?php
class User
{
    private $conn;
    private $table = "users";

    public $id;
    public $username;
    public $password;

    // Konstruktor untuk menghubungkan ke database
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Mendapatkan semua pengguna
    public function getAllUsers(): array
    {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Mengembalikan semua pengguna
    }

    // Menghapus pengguna berdasarkan ID
    public function deleteUserById($id): bool
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute(); // Mengembalikan hasil eksekusi
    }

    // Fungsi untuk signup pengguna baru
    public function signup($username, $password): string
    {
        // Validasi input - Pastikan tidak ada data kosong
        if (empty($username) || empty($password)) {
            return "Semua field harus diisi.";
        }

        // Periksa apakah username atau email sudah terdaftar
        $query = "SELECT id FROM " . $this->table . " WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return "Username sudah terdaftar.";
        }

        // Hash password menggunakan bcrypt sebelum menyimpannya di database
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Query untuk menyimpan pengguna baru
        $query = "INSERT INTO " . $this->table . " (username, password, role) 
              VALUES (:username, :password, :role)";
        $stmt = $this->conn->prepare($query);

        // Tentukan role default sebagai 'client'
        $role = 'client';

        // Bind parameter
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':role', $role);

        // Eksekusi query dan periksa apakah berhasil
        if ($stmt->execute()) {
            return "Pendaftaran berhasil. Silakan login.";
        } else {
            return "Terjadi kesalahan. Silakan coba lagi.";
        }
    }
}