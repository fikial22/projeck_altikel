<?php
session_start(); // Mulai sesi

// Periksa apakah pengguna sudah login, jika tidak, arahkan ke halaman login
if (!isset($_SESSION['user_id'])) {
    header("Location:login.html");
    exit;
}

require 'db_connection.php';

// Definisikan berapa banyak hasil yang diinginkan per halaman
$hasil_per_halaman = 5;

// Cari tahu jumlah total artikel yang tersimpan di database
$sql_count = "SELECT COUNT(id_artikel) AS total FROM artikel";
$result_count = $conn->query($sql_count);
$row_count = $result_count->fetch_assoc();
$total_artikel = $row_count['total'];

// Tentukan jumlah total halaman yang tersedia
$total_halaman = ceil($total_artikel / $hasil_per_halaman);

// Tentukan halaman saat ini
if (!isset($_GET['halaman'])) {
    $halaman = 1;
} else {
    $halaman = $_GET['halaman'];
}

// Tentukan nomor awal limit
$mulai_limit = ($halaman - 1) * $hasil_per_halaman;

// Ambil hasil yang dipilih dari database
$sql = "SELECT artikel.*, kategori.nama_kategori 
        FROM artikel 
        JOIN kategori ON artikel.id_kategori = kategori.id_kategori 
        LIMIT $mulai_limit, $hasil_per_halaman";
$result = $conn->query($sql);

$artikel = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $artikel[] = $row;
    }
}

$sql_kategori = "SELECT * FROM kategori";
$result_kategori = $conn->query($sql_kategori);

$kategori = [];
if ($result_kategori->num_rows > 0) {
    while ($row = $result_kategori->fetch_assoc()) {
        $kategori[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Blog Home - Start Bootstrap Template</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="css/styles.css" rel="stylesheet" />
    <style>
        .card-img-top {
            width: 100%;
            height: auto;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#!">AlTikel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="../startbootstrap-sb-admin-2-gh-pages/index.php">Tambah</a></li>
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Keluar</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <header class="py-5 bg-light border-bottom mb-4" style="background-image: url('dasboard1.jpg'); background-size: cover; background-position: center; color: white; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);">
    <div class="container">
        <div class="text-center my-5">
            <h1 class="fw-bolder">Selamat Datang di Blog Home!</h1>
            <p class="lead mb-0">Enjoy Your Reading</p>
        </div>
    </div>
</header>

    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <?php if (!empty($artikel)): ?>
                    <?php $artikel_utama = array_shift($artikel); ?>
                    <div class="card mb-4">
                        <a href="artikel.php?id=<?php echo $artikel_utama['id_artikel']; ?>"><img class="card-img-top" src="../startbootstrap-sb-admin-2-gh-pages/images/<?php echo $artikel_utama['gambar']; ?>" alt="Gambar Artikel" onerror="this.onerror=null; this.src='startbootstrap-sb-admin-2-gh-pages/images/default-image.jpg';" /></a>
                        <div class="card-body">
                            <div class="small text-muted"><?php echo $artikel_utama['hari_tanggal']; ?></div>
                            <h2 class="card-title"><?php echo $artikel_utama['judul']; ?></h2>
                            <p class="card-text"><?php echo substr($artikel_utama['isi'], 0, 100) . '...'; ?></p>
                            <a class="btn btn-primary" href="artikel.php?id=<?php echo $artikel_utama['id_artikel']; ?>">Baca selengkapnya →</a>
                        </div>
                    </div>
                    <div class="row">
                        <?php foreach ($artikel as $item): ?>
                            <div class="col-lg-6">
                                <div class="card mb-4">
                                    <a href="artikel.php?id=<?php echo $item['id_artikel']; ?>"><img class="card-img-top" src="../startbootstrap-sb-admin-2-gh-pages/images/<?php echo $item['gambar']; ?>" alt="Gambar Artikel" onerror="this.onerror=null; this.src='startbootstrap-sb-admin-2-gh-pages/images/default-image.jpg';" /></a>
                                    <div class="card-body">
                                        <div class="small text-muted"><?php echo $item['hari_tanggal']; ?></div>
                                        <h2 class="card-title h4"><?php echo $item['judul']; ?></h2>
                                        <p class="card-text"><?php echo substr($item['isi'], 0, 100) . '...'; ?></p>
                                        <a class="btn btn-primary" href="artikel.php?id=<?php echo $item['id_artikel']; ?>">Baca selengkapnya →</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>Tidak ada artikel ditemukan</p>
                <?php endif; ?>
                <nav aria-label="Pagination">
                    <hr class="my-0" />
                    <ul class="pagination justify-content-center my-4">
                        <?php if($halaman > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="index.php?halaman=<?php echo $halaman-1; ?>">Lebih Baru</a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_halaman; $i++): ?>
                            <li class="page-item <?php if($halaman == $i) echo 'active'; ?>">
                                <a class="page-link" href="index.php?halaman=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if($halaman < $total_halaman): ?>
                            <li class="page-item">
                                <a class="page-link" href="index.php?halaman=<?php echo $halaman+1; ?>">More</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">Pencarian</div>
                    <div class="card-body">
                        <form action="search.php" method="GET">
                            <div class="input-group">
                                <input class="form-control" name="query" type="text" placeholder="Masukkan kata kunci pencarian..." aria-label="Masukkan kata kunci pencarian..." aria-describedby="button-search" />
                                <button class="btn btn-primary" id="button-search" type="submit">Cari!</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header">Kategori</div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($kategori as $kat): ?>
                                <div class="col-sm-6">
                                    <ul class="list-unstyled mb-0">
                                        <li><a href="kategori.php?id=<?php echo $kat['id_kategori']; ?>"><?php echo $kat['nama_kategori']; ?></a></li>
                                    </ul>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header">Kutipan Motivasi</div>
                    <div class="card-body">Rasa ingin tahu akan membawa Anda ke tempat-tempat baru dan membuka pintu ke pengalaman yang tak terduga</div>
                </div>
                <div class="card mb-4">
                    <div class="card-header">Tentang</div>
                    <div class="card-body">Selamat datang di situs kami! Kami adalah platform yang didedikasikan untuk memberikan informasi yang terpercaya dan bermanfaat di berbagai bidang, termasuk wisata, teknologi, pendidikan, hobi, olahraga, dan gaya hidup.</div>
                </div>
            </div>
        </div>
    </div>
    <footer class="py-5 bg-dark">
        <!-- Footer Content Here -->
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>

</html>
