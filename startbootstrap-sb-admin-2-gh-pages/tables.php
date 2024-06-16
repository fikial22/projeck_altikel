<?php
session_start();
require 'koneksi.php';

// Redirect to login page if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../startbootstrap-blog-home-gh-pages/login.html");
    exit();
}

require 'koneksi.php';
$user_id = $_SESSION['user_id'];
$user_query = "SELECT nama FROM penulis WHERE id_penulis = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user_data = $user_result->fetch_assoc();
$user_name = $user_data['nama'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        // Add article
        $judul = $_POST['judul'];
        $tanggal = $_POST['tanggal'];
        $isi = $_POST['isi'];
        $id_kategori = $_POST['kategori']; // Mengambil id_kategori
        $gambar = $_FILES['gambar']['name'];
        $target = "images/" . basename($gambar);

        $query = "INSERT INTO artikel (judul, hari_tanggal, isi, id_kategori, gambar) VALUES ('$judul', '$tanggal', '$isi', '$id_kategori', '$gambar')";
        if ($conn->query($query) === TRUE) {
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target)) {
                echo "<script>alert('Artikel berhasil ditambahkan.');</script>";
            } else {
                echo "<script>alert('Gagal mengunggah gambar.');</script>";
            }
        } else {
            echo "<script>alert('Error: " . $query . "<br>" . $conn->error . "');</script>";
        }
    } elseif (isset($_POST['edit'])) {
        // Edit article
        $id = $_POST['id'];
        $judul = $_POST['judul'];
        $tanggal = $_POST['tanggal'];
        $isi = $_POST['isi'];
        $id_kategori = $_POST['kategori']; // Mengambil id_kategori
        $gambar = $_FILES['gambar']['name'];
        $target = "images/" . basename($gambar);

        if ($gambar) {
            $query = "UPDATE artikel SET judul='$judul', hari_tanggal='$tanggal', isi='$isi', id_kategori='$id_kategori', gambar='$gambar' WHERE id_artikel=$id";
            move_uploaded_file($_FILES['gambar']['tmp_name'], $target);
        } else {
            $query = "UPDATE artikel SET judul='$judul', hari_tanggal='$tanggal', isi='$isi', id_kategori='$id_kategori' WHERE id_artikel=$id";
        }

        if ($conn->query($query) === TRUE) {
            echo "<script>alert('Artikel berhasil diperbarui.');</script>";
        } else {
            echo "<script>alert('Error: " . $query . "<br>" . $conn->error . "');</script>";
        }
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM artikel WHERE id_artikel=$id";
    if ($conn->query($query) === TRUE) {
        echo "<script>alert('Artikel berhasil dihapus.');</script>";
    } else {
        echo "<script>alert('Error: " . $query . "<br>" . $conn->error . "');</script>";
    }
}

$query = "SELECT artikel.*, kategori.nama_kategori FROM artikel JOIN kategori ON artikel.id_kategori = kategori.id_kategori";
$result = $conn->query($query);

$kategori_query = "SELECT * FROM kategori";
$kategori_result = $conn->query($kategori_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>SB Admin 2 - Tables</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Admin </div>
            </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
            <hr class="sidebar-divider">
            <div class="sidebar-heading">
                Interface
            </div>
            <div class="sidebar-heading">
                Addons
            </div>
            <li class="nav-item">
                <a class="nav-link" href="tables.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Tables</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../startbootstrap-blog-home-gh-pages/index.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Artikel</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="kategori.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Kategori</span></a>
            </li>
            <hr class="sidebar-divider d-none d-md-block">
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    </form>
                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $user_name; ?></span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <div class="container-fluid">
                    <h1 class="h3 mb-2 text-gray-800">Tables</h1>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addModal">Tambah Artikel</button>
                            <h6 class="m-0 font-weight-bold text-primary">Data Tables</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Hari Tanggal</th>
                                            <th>Judul</th>
                                            <th>Isi</th>
                                            <th>Kategori</th> <!-- Tambahkan kolom kategori -->
                                            <th>Gambar</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $result->fetch_assoc()) { ?>
                                            <tr>
                                                <td><?php echo $row['id_artikel']; ?></td>
                                                <td><?php echo $row['hari_tanggal']; ?></td>
                                                <td><?php echo $row['judul']; ?></td>
                                                <td><?php echo $row['isi']; ?></td>
                                                <td><?php echo $row['nama_kategori']; ?></td> <!-- Tampilkan nama kategori -->
                                                <td><img src="images/<?php echo $row['gambar']; ?>" width="100" height="100"></td>
                                                <td>
                                                    <button class="btn btn-success editbtn">Edit</button>
                                                    <a href="?delete=<?php echo $row['id_artikel']; ?>" class="btn btn-danger">Delete</a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addModalLabel">Tambah Artikel</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Judul</label>
                                            <input type="text" name="judul" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Tanggal</label>
                                            <input type="date" name="tanggal" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Isi</label>
                                            <textarea name="isi" class="form-control" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Kategori</label>
                                            <select name="kategori" class="form-control" required>
                                                <?php while ($kategori = $kategori_result->fetch_assoc()) { ?>
                                                    <option value="<?php echo $kategori['id_kategori']; ?>"><?php echo $kategori['nama_kategori']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Gambar</label>
                                            <input type="file" name="gambar" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" name="add" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit Artikel</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <input type="hidden" name="id" id="edit-id">
                                        <div class="form-group">
                                            <label>Judul</label>
                                            <input type="text" name="judul" id="edit-judul" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Tanggal</label>
                                            <input type="date" name="tanggal" id="edit-tanggal" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Isi</label>
                                            <textarea name="isi" id="edit-isi" class="form-control" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Kategori</label>
                                            <select name="kategori" id="edit-kategori" class="form-control" required>
                                                <?php
                                                $kategori_result->data_seek(0); // Mengatur ulang pointer hasil query
                                                while ($kategori = $kategori_result->fetch_assoc()) { ?>
                                                    <option value="<?php echo $kategori['id_kategori']; ?>"><?php echo $kategori['nama_kategori']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Gambar</label>
                                            <input type="file" name="gambar" class="form-control">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" name="edit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="js/demo/datatables-demo.js"></script>
    <script>
        $(document).ready(function () {
            $('.editbtn').on('click', function () {
                $('#editModal').modal('show');

                $tr = $(this).closest('tr');

                var data = $tr.children("td").map(function () {
                    return $(this).text();
                }).get();

                $('#edit-id').val(data[0]);
                $('#edit-tanggal').val(data[1]);
                $('#edit-judul').val(data[2]);
                $('#edit-isi').val(data[3]);
                $('#edit-kategori').val(data[4]);
            });
        });
    </script>
</body>

</html>
