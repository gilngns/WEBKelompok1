<?php
session_start();
include '../koneksi.php';
include '../middleware.php';

$limit = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Jika ada pencarian, tampilkan hanya produk yang dicari
if (!empty($search)) {
    $products = $koneksi->query("SELECT * FROM products WHERE name LIKE '%$search%' LIMIT 1");
    $total_pages = 1;
} else {
    $total_results = $koneksi->query("SELECT COUNT(id) AS count FROM products")->fetch_assoc()['count'];
    $total_pages = ceil($total_results / $limit);
    $products = $koneksi->query("SELECT * FROM products LIMIT $start, $limit");
}
// Tambah Produk
if (isset($_POST['tambah'])) {
    $nama = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Cek apakah produk dengan nama yang sama sudah ada
    $cekProduk = $koneksi->query("SELECT * FROM products WHERE name = '$nama'");
    if ($cekProduk->num_rows > 0) {
        echo "<script>alert('Produk ini sudah ada!'); window.location.href='../dashboard/dashboard.php';</script>";
        exit();
    }

    if (!empty($_FILES['gambar']['name'])) {
        $gambar = time() . '_' . $_FILES['gambar']['name'];
        $target = "../uploads/" . $gambar;
        move_uploaded_file($_FILES['gambar']['tmp_name'], $target);
    } else {
        $gambar = "";
    }

    $koneksi->query("INSERT INTO products (name, gambar, price, description) VALUES ('$nama', '$gambar', '$price', '$description')");
    header("Location: ../dashboard/dashboard.php");
    exit();
}


// Edit Produk
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nama = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    if (!empty($_FILES['gambar']['name'])) {
        $gambar = time() . '_' . $_FILES['gambar']['name'];
        $target = "../uploads/" . $gambar;
        move_uploaded_file($_FILES['gambar']['tmp_name'], $target);

        // Hapus gambar lama
        $result = $koneksi->query("SELECT gambar FROM products WHERE id='$id'");
        $data = $result->fetch_assoc();
        if (!empty($data['gambar'])) {
            unlink("../uploads/" . $data['gambar']);
        }

        $koneksi->query("UPDATE products SET name='$nama', gambar='$gambar', price='$price', description='$description' WHERE id='$id'");
    } else {
        $koneksi->query("UPDATE products SET name='$nama', price='$price', description='$description' WHERE id='$id'");
    }
    header("Location: ../dashboard/dashboard.php");
    exit();
}

// Hapus Produk
if (isset($_POST['hapus'])) {
    $id = $_POST['id'];

    // Hapus gambar terkait
    $result = $koneksi->query("SELECT gambar FROM products WHERE id='$id'");
    $data = $result->fetch_assoc();
    if (!empty($data['gambar'])) {
        unlink("../uploads/" . $data['gambar']);
    }

    $koneksi->query("DELETE FROM products WHERE id='$id'");
    header("Location: ../dashboard/dashboard.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Produk</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="container mt-4">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#" style="font-weight: 50px; font-size: 50px;">Dashboard SUELVI</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>" href="../dashboard/dashboard.php">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'membership.php' ? 'active' : '' ?>" href="../dashboard/membership.php">Membership</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'order.php' ? 'active' : '' ?>" href="../dashboard/order.php">Order</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="d-flex justify-content-between mb-3">
        <button class="btn btn-success mt-2" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Produk</button>
        <form class="d-flex" method="GET">
            <input class="form-control me-2" type="text" name="search" placeholder="Cari produk" value="<?= $search; ?>">
            <button class="btn btn-primary" type="submit">Cari</button>
        </form>
    </div>

    <!-- Tabel Produk -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Gambar</th>
                <th>Harga</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php while ($row = $products->fetch_assoc()) { ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row['name']; ?></td>
                    <td><img src="../uploads/<?= $row['gambar']; ?>" width="50"></td>
                    <td>Rp <?= number_format($row['price'], 0, ',', '.'); ?></td>
                    <td><?= $row['description']; ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id']; ?>">Edit</button>
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $row['id']; ?>">Hapus</button>
                    </td>
                </tr>

                <!-- Modal Edit -->
                <div class="modal fade" id="modalEdit<?= $row['id']; ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Produk</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                    <div class="mb-2">
                                        <label>Nama Produk</label>
                                        <input type="text" name="name" class="form-control" value="<?= $row['name']; ?>" required>
                                    </div>
                                    <div class="mb-2">
                                        <label>Harga</label>
                                        <input type="number" name="price" class="form-control" value="<?= $row['price']; ?>" required>
                                    </div>
                                    <div class="mb-2">
                                        <label>Deskripsi</label>
                                        <textarea name="description" class="form-control" required><?= $row['description']; ?></textarea>
                                    </div>
                                    <div class="mb-2">
                                        <label>Gambar</label>
                                        <input type="file" name="gambar" class="form-control">
                                    </div>
                                    <button type="submit" name="edit" class="btn btn-primary">Simpan Perubahan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Hapus -->
                <div class="modal fade" id="modalHapus<?= $row['id']; ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Konfirmasi Hapus</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Apakah Anda yakin ingin menghapus produk <strong><?= $row['name']; ?></strong>?</p>
                                <form action="" method="POST">
                                    <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                    <button type="submit" name="hapus" class="btn btn-danger">Hapus</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </tbody>
    </table>

    <nav>
        <a href="../home/index.php" style="color: black;">
            << Kembali ke Home</a>
                <ul class="pagination justify-content-center mx-2">
                    <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                        <li class="page-item <?= ($page == $i) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                        </li>
                    <?php } ?>
                </ul>
    </nav>

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-2">
                            <label>Nama Produk</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label>Harga</label>
                            <input type="number" name="price" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label>Deskripsi</label>
                            <textarea name="description" class="form-control" required></textarea>
                        </div>
                        <div class="mb-2">
                            <label>Gambar</label>
                            <input type="file" name="gambar" class="form-control">
                        </div>
                        <button type="submit" name="tambah" class="btn btn-success">Tambah</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
