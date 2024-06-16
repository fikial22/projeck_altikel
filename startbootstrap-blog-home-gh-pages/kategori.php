<?php
require 'db_connection.php';

$kategori_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data artikel berdasarkan kategori
$sql = "SELECT artikel.*, kategori.nama_kategori 
        FROM artikel 
        JOIN kategori ON artikel.id_kategori = kategori.id_kategori 
        WHERE artikel.id_kategori = $kategori_id";
$result = $conn->query($sql);

$articles = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $articles[] = $row;
    }
}

// Ambil nama kategori
$sql_kategori = "SELECT nama_kategori FROM kategori WHERE id_kategori = $kategori_id";
$result_kategori = $conn->query($sql_kategori);
$nama_kategori = $result_kategori->num_rows > 0 ? $result_kategori->fetch_assoc()['nama_kategori'] : 'Unknown Category';

// Ambil data kategori untuk ditampilkan di sidebar
$sql_categories = "SELECT * FROM kategori";
$result_categories = $conn->query($sql_categories);

$categories = [];
if ($result_categories->num_rows > 0) {
    while ($row = $result_categories->fetch_assoc()) {
        $categories[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Articles in <?php echo $nama_kategori; ?> - Blog</title>
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
            <a class="navbar-brand" href="index.php">AlTikel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="../startbootstrap-sb-admin-2-gh-pages/index.php">Tambah</a></li>
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php">Beranda</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="py-5 bg-light border-bottom mb-4"
        style="background-image: url('dasboard1.jpg'); background-size: cover; background-position: center; color: white; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);">
        <div class="container">
            <div class="text-center my-5">
                <h1 class="fw-bolder">Articles in <?php echo $nama_kategori; ?></h1>
                <p class="lead mb-0">Explore the articles in this category</p>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <?php if (!empty($articles)): ?>
                    <div class="row">
                        <?php foreach ($articles as $article): ?>
                            <div class="col-lg-6">
                                <div class="card mb-4">
                                    <a href="artikel.php?id=<?php echo $article['id_artikel']; ?>"><img class="card-img-top"
                                            src="../startbootstrap-sb-admin-2-gh-pages/images/<?php echo $article['gambar']; ?>"
                                            alt="Gambar Artikel"
                                            onerror="this.onerror=null; this.src='startbootstrap-sb-admin-2-gh-pages/images/default-image.jpg';" /></a>
                                    <div class="card-body">
                                        <div class="small text-muted"><?php echo $article['hari_tanggal']; ?></div>
                                        <h2 class="card-title h4"><?php echo $article['judul']; ?></h2>
                                        <p class="card-text"><?php echo substr($article['isi'], 0, 100) . '...'; ?></p>
                                        <a class="btn btn-primary"
                                            href="artikel.php?id=<?php echo $article['id_artikel']; ?>">Read more →</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>No articles found in this category</p>
                <?php endif; ?>
            </div>
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">Pencarian</div>
                    <div class="card-body">
                        <div class="input-group">
                            <input class="form-control" type="text" placeholder="Enter search term..."
                                aria-label="Enter search term..." aria-describedby="button-search" />
                            <button class="btn btn-primary" id="button-search" type="button">Cari</button>
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header">Kategori</div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($categories as $category): ?>
                                <div class="col-sm-6">
                                    <ul class="list-unstyled mb-0">
                                        <li><a
                                                href="kategori.php?id=<?php echo $category['id_kategori']; ?>"><?php echo $category['nama_kategori']; ?></a>
                                        </li>
                                    </ul>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header">Kutipan Motivasi</div>
                    <div class="card-body">Rasa ingin tahu akan membawa Anda ke tempat-tempat baru dan membuka pintu ke
                        pengalaman yang tak terduga</div>
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