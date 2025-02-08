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
        .btn-buy:hover, .btn-brown:hover {
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
            <a class="navbar-brand" href="#">Dashboard SUELVI</a>
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
                    <td><?= $row['status'] == 1 ? 'Sudah Bayar' : 'Belum Bayar'; ?></td>
                    <td>
                        <a href="edit_order.php?id=<?= $row['id']; ?>" class="btn-brown btn-sm">Edit</a>
                        <a href="hapus_order.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?')">Hapus</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <nav>
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                <li class="page-item <?= ($page == $i) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                </li>
            <?php } ?>
        </ul>
    </nav>
</body>
</html>
