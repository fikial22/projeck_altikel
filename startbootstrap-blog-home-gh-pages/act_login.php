<?php
// Sertakan file koneksi database
require 'db_connection.php';

session_start(); // Mulai sesi

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Persiapkan dan eksekusi pernyataan SQL untuk mengambil data user berdasarkan email yang diberikan
    $stmt = $conn->prepare("SELECT id_penulis, email, password FROM penulis WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Periksa apakah pengguna ada
    if ($stmt->num_rows == 1) {
        // Bind result variables
        $stmt->bind_result($id_penulis, $email, $hashed_password);
        $stmt->fetch();

        // Verifikasi password
        if (password_verify($password, $hashed_password)) {
            // Password benar, mulai sesi baru dan simpan ID pengguna
            $_SESSION['user_id'] = $id_penulis;

            // Redirect ke halaman yang sudah login
            header("Location: index.php");
            exit();
        } else {
            // Password salah
            echo "Invalid email or password.";
        }
    } else {
        // Pengguna tidak ada
        echo "Invalid email or password.";
    }

    // Tutup statement dan koneksi
    $stmt->close();
    $conn->close();
}
?>
