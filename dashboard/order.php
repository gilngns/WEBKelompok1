<?php
include '../koneksi.php';

$limit = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Query untuk mengambil data pesanan dengan join ke tabel users dan products
$query = "SELECT orders.id, users.name AS name, products.name AS product_name, orders.quantity, orders.total_price, orders.order_date, orders.status
          FROM orders
          JOIN users ON orders.user_id = users.id
          JOIN products ON orders.product_id = products.id
          WHERE users.name LIKE '%$search%' OR products.name LIKE '%$search%'
          LIMIT $start, $limit";

$orders = $koneksi->query($query);
$total_results = $koneksi->query("SELECT COUNT(id) AS count FROM orders")->fetch_assoc()['count'];
$total_pages = ceil($total_results / $limit);

// Proses Update Status Pesanan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id']) && isset($_POST['status'])) {
        $id = $_POST['id'];
        $status = $_POST['status'];

        $query = "UPDATE orders SET status = '$status' WHERE id = $id";
        if ($koneksi->query($query)) {
            echo "<script>alert('Status pesanan berhasil diperbarui!'); window.location.href='order.php';</script>";
        } else {
            echo "<script>alert('Gagal memperbarui status pesanan!'); window.location.href='order.php';</script>";
        }
    }
}

if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $koneksi->query("DELETE FROM orders WHERE id = '$id'");

    echo "<script>alert('Pesanan berhasil dihapus!'); window.location.href='order.php';</script>";
}

?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Orders</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<style>
    .btn-brown {
        background: linear-gradient(135deg, #80543b, #a67c52);
        color: white;
        border: none;
        padding: 4px 10px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-buy:hover,
    .btn-brown:hover {
        background: linear-gradient(135deg, #a67c52, #80543b);
        transform: scale(1.08);
    }

    .pagination .page-item .page-link {
        background: #80543b;
        color: white;
        border-radius: 8px;
        margin: 0 5px;
        transition: all 0.3s;
    }

    .pagination .page-item .page-link:hover {
        background: #a67c52;
    }
</style>

<body class="container mt-4">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#" style="font-weight: 50px; font-size: 50px;">Dashboard SUELVI</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../dashboard/dashboard.php">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../dashboard/membership.php">Membership</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="../dashboard/order.php">Order</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="d-flex justify-content-between mb-3">
        <form class="d-flex" method="GET">
            <input class="form-control me-2" type="text" name="search" placeholder="Cari pesanan" value="<?= $search; ?>">
            <button class="btn-brown" type="submit">Cari</button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama User</th>
                    <th>Nama Produk</th>
                    <th>Jumlah</th>
                    <th>Total Harga</th>
                    <th>Tanggal Order</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $orders->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= $row['name']; ?></td>
                        <td><?= $row['product_name']; ?></td>
                        <td><?= $row['quantity']; ?></td>
                        <td>Rp <?= number_format($row['total_price'], 0, ',', '.'); ?></td>
                        <td><?= $row['order_date']; ?></td>
                        <td>
                            <span class="badge 
        <?= ($row['status'] == 'canceled') ? 'bg-danger' : (($row['status'] == 'pending') ? 'bg-warning text-dark' : 'bg-success'); ?>">
                                <?= $row['status']; ?>
                            </span>
                        </td>

                        <td>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id']; ?>">Edit</button>
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $row['id']; ?>">Hapus</button>
                        </td>
                    </tr>

                    <!-- Modal Edit Status -->
                    <div class="modal fade" id="editModal<?= $row['id']; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Status Pesanan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="" method="POST">
                                    <div class="modal-body">
                                        <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                        <label>Status:</label>
                                        <select class="form-control" name="status">
                                            <option value="pending" <?= $row['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="success" <?= $row['status'] == 'success' ? 'selected' : ''; ?>>Success</option>
                                            <option value="canceled" <?= $row['status'] == 'canceled' ? 'selected' : ''; ?>>Canceled</option>
                                        </select>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Hapus -->
                    <div class="modal fade" id="deleteModal<?= $row['id']; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    Apakah Anda yakin ingin menghapus pesanan ini?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <form method="POST">
                                        <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                        <button type="submit" name="delete" class="btn btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
