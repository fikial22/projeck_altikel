<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM kategori WHERE id_kategori = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Data tidak ditemukan";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id_kategori'];
    $nama_kategori = $_POST['nama_kategori'];
    $keterangan = $_POST['keterangan'];

    $sql = "UPDATE kategori SET nama_kategori = '$nama_kategori', keterangan = '$keterangan' WHERE id_kategori = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: kategori.php");
    } else {
        echo "Error updating record: " . $conn->error;
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kategori</title>
</head>
<body>
    <h2>Edit Kategori</h2>
    <form action="edit_kategori.php" method="post">
        <input type="hidden" name="id_kategori" value="<?php echo $row['id_kategori']; ?>">
        <label for="nama_kategori">Nama Kategori:</label>
        <input type="text" id="nama_kategori" name="nama_kategori" value="<?php echo $row['nama_kategori']; ?>"><br><br>
        <label for="keterangan">Keterangan:</label>
        <textarea id="keterangan" name="keterangan"><?php echo $row['keterangan']; ?></textarea><br><br>
        <input type="submit" value="Update">
    </form>
</body>
</html>
