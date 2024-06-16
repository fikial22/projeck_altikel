<?php
// Mulai sesi
session_start();

// Hapus semua variabel sesi
session_unset();

// Hancurkan sesi
session_destroy();

// Redirect kembali ke halaman login
header("Location: login.html");
exit;
?>
