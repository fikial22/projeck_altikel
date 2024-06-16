<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM kategori WHERE id_kategori = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: kategori.php");
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $conn->close();
}
?>
