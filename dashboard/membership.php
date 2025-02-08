<?php
include '../koneksi.php';
session_start();

$limit = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Jika ada pencarian, tampilkan hanya membership yang dicari
if (!empty($search)) {
    $memberships = $koneksi->query("SELECT m.id, u.name, m.start_date, m.end_date, m.type, m.status 
                                    FROM memberships m 
                                    JOIN users u ON m.user_id = u.id
                                    WHERE u.name LIKE '%$search%' OR m.type LIKE '%$search%' OR m.status LIKE '%$search%'");
    $total_pages = 1;
} else {
    $total_results = $koneksi->query("SELECT COUNT(m.id) AS count 
                                      FROM memberships m 
                                      JOIN users u ON m.user_id = u.id")->fetch_assoc()['count'];
    $total_pages = ceil($total_results / $limit);
    $memberships = $koneksi->query("SELECT m.id, u.name, m.start_date, m.end_date, m.type, m.status 
                                    FROM memberships m 
                                    JOIN users u ON m.user_id = u.id
                                    LIMIT $start, $limit");
}

// Hapus Membership
if (isset($_POST['hapus'])) {
    $id = $_POST['id'];

    $koneksi->query("DELETE FROM memberships WHERE id = $id");
    header("Location: membership.php");
    exit();
}

// Edit Membership
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $koneksi->query("UPDATE memberships SET status = '$status' WHERE id = $id");
    header("Location: membership.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Membership</title>
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
        <form class="d-flex" method="GET">
            <input class="form-control me-2" type="text" name="search" placeholder="Cari membership" value="<?= $search; ?>">
            <button class="btn btn-primary" type="submit">Cari</button>
        </form>
    </div>

    <!-- Tabel Membership -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Berakhir</th>
                <th>Tipe</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php while ($row = $memberships->fetch_assoc()) { ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row['name']; ?></td>
                    <td><?= $row['start_date']; ?></td>
                    <td><?= $row['end_date']; ?></td>
                    <td><?= $row['type']; ?></td>
                    <td>
                        <span class="badge <?= $row['status'] == 'batal' ? 'bg-danger' : ($row['status'] == 'pending' ? 'bg-warning' : 'bg-success') ?>">
                            <?= $row['status']; ?>
                        </span>
                    </td>
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
                                <h5 class="modal-title">Edit Membership</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form action="" method="POST">
                                    <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select name="status" class="form-select">
                                            <option value="pending" <?= $row['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="batal" <?= $row['status'] == 'batal' ? 'selected' : ''; ?>>Batal</option>
                                            <option value="selesai" <?= $row['status'] == 'selesai' ? 'selected' : ''; ?>>Selesai</option>
                                        </select>
                                    </div>
                                    <button type="submit" name="edit" class="btn btn-primary">Simpan</button>
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
                                <p>Apakah Anda yakin ingin menghapus membership <strong><?= $row['name']; ?></strong>?</p>
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
        <ul class="pagination justify-content-center mx-2">
            <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                <li class="page-item <?= ($page == $i) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?= $i; ?><?= !empty($search) ? '&search=' . $search : ''; ?>"><?= $i; ?></a>
                </li>
            <?php } ?>
        </ul>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
