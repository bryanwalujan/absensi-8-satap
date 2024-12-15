<?php
// Include model dan controller
require_once './models/Database.php'; // Sesuaikan dengan path Anda
require_once './controllers/UserController.php'; // Sesuaikan dengan path Anda

// Inisialisasi database dan controller
$database = new Database();
$db = $database->getConnection();
$userController = new UserController($db);

// Pesan error (jika ada)
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil input dari form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek login
    $query = "SELECT * FROM users WHERE username = :username";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Jika username ditemukan
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Login berhasil
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect ke halaman sesuai role
            if ($user['role'] == 'admin') {
                header('Location: views/admin.php'); // Halaman untuk admin
                exit();
            } elseif ($user['role'] == 'guru') {
                header('Location: views/dashboard.php'); // Halaman untuk guru
                exit();
            } else {
                header('Location: views/index.php'); // Halaman untuk role lain (misalnya siswa)
                exit();
            }
        } else {
            // Password salah
            $error = "Password salah. Silakan coba lagi.";
        }
    } else {
        // Username tidak ditemukan
        $error = "Username tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SMP Negeri 8 Satap Tondano</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<style>
    /* Global Reset */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, sans-serif;
    }

    body {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        background-color: #f4f7fb;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        line-height: 1.6;
        color: #333;
        background-color: #f9fafb;
    }

    /* Container for Login Form */
    .login-container {
        width: 100%;
        max-width: 400px;
        background-color: #fff;
        padding: 2rem;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        text-align: center;
    }

    .login-header .logo {
        width: 80px;
        margin-bottom: 1rem;
    }

    .login-header h2 {
        color: #333;
        margin-bottom: 0.5rem;
        font-size: 1.8rem;
    }

    .login-header p {
        color: #666;
        margin-bottom: 1.5rem;
    }

    /* Form Styling */
    form label {
        display: block;
        text-align: left;
        margin: 0.5rem 0 0.2rem;
        color: #333;
        font-weight: 500;
    }

    form input {
        width: 100%;
        padding: 0.8rem;
        margin-bottom: 1rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
        transition: border-color 0.3s ease-in-out;
    }

    form input:focus {
        border-color: #007bff;
        outline: none;
    }

    form button {
        width: 100%;
        padding: 0.8rem;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 4px;
        font-size: 1rem;
        cursor: pointer;
        transition: background-color 0.3s ease-in-out;
    }

    form button:hover {
        background-color: #0056b3;
    }

    /* Error Message */
    .error {
        background-color: #ffdddd;
        color: #d8000c;
        border: 1px solid #d8000c;
        padding: 10px;
        margin-bottom: 1rem;
        border-radius: 4px;
        text-align: center;
        font-size: 1rem;
    }

    /* Responsive Design for Small Screens */
    @media (max-width: 480px) {
        .login-container {
            padding: 1rem;
        }

        .login-header h2 {
            font-size: 1.6rem;
        }

        .login-header p {
            font-size: 0.9rem;
        }

        form label,
        form input,
        form button {
            font-size: 0.9rem;
        }
    }
</style>

<body>
    <div class="login-container">
        <div class="login-header">
            <img src="picture/Logo-Tut-Wuri-Handayani-PNG-Warna.png" alt="Logo SMP Negeri 8 Satap Tondano" class="logo">
            <h2>Selamat Datang</h2>
            <p>Silakan login untuk melanjutkan</p>
        </div>

        <!-- Tampilkan pesan error jika ada -->
        <?php if (!empty($error)): ?>
            <div class="error"><?= $error; ?></div>
        <?php endif; ?>

        <!-- Form login -->
        <form action="index.php" method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Masuk</button>
        </form>
    </div>
</body>

</html>