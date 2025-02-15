<?php
session_start();
require_once '../koneksi.php';

$userName = null;

if (isset($_SESSION['user_id'])) {
    $userId = (int) $_SESSION['user_id'];

    $query = "SELECT name FROM users WHERE id = $userId";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $userName = $row['name'] ?? null;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link rel="icon" type="image/x-icon" href="../image/logo.jpg">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- AOS CSS -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f1ec;
        }

        .header {
            background: linear-gradient(135deg, #7a3e12, #a0522d);
            color: #F7F7F7;
            padding: 30px;
            text-align: center;
            font-size: 28px;
            font-weight: 700;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 40px;
        }

        .card {
            background-color: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

        .card img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
        }

        .card h2 {
            font-size: 20px;
            font-weight: 600;
            margin: 15px 0;
            color: #7a3e12;
        }

        .card p {
            font-size: 16px;
            color: #444;
            padding: 0 10px;
        }

        .header {
            background: linear-gradient(135deg, #7a3e12, #a0522d);
            color: #F7F7F7;
            padding: 15px 40px;
            text-align: center;
            font-size: 28px;
            font-weight: 700;
            max-width: 950px;
            border-radius: 50px;
            margin: 30px auto;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary py-3 sticky-top">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <img src="../image/logo.jpg" alt="Logo" width="60" height="60" class="rounded-pill">
                <a class="navbar-brand ms-2" href="#" style="font-family: 'Poppins', sans-serif; font-weight: 800; font-size: 30px;">SUELVI</a>
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse d-flex justify-content-center" id="navbarNav">
                <ul class="navbar-nav" style="font-family: 'Poppins', sans-serif;">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="../home/index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../home/index.php">Edukasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="produk.php">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../home/index.php">Membership</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../home/index.php">Komunitas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                </ul>
            </div>

            <div class="ml-auto">
                <?php if (isset($_SESSION['name'])) : ?>
                    <div class="dropdown">
                        <button class="btn btn-outline-success dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: #80543b; color: #fff; border-color: #80543b;">
                            <?= htmlspecialchars($_SESSION['name']); ?> <!-- Hindari XSS -->
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="../Auth/logout.php">Logout</a></li>
                        </ul>
                    </div>
                <?php else : ?>
                    <a href="../Auth/login.php" class="btn btn-outline-success mx-auto" style="background-color: #80543b; color: #fff; border-color: #80543b;">Login</a>
                <?php endif; ?>
            </div>

        </div>
    </nav>

    <div class="header">
        🌿 About Us 🌿
    </div>

    <div class="card-container">
        <div class="card">
            <img src="../image/foto1.jpg" alt="Foto 1">
            <h2>Aqshal Fadlila Nugraha</h2>
            <p>Menghandle Pembelian Produk dan Mengelola Data Produk</p>
        </div>
        <div class="card">
            <img src="../image/foto2.jpg" alt="Foto 2">
            <h2>Zaqi Muhammad Zidan</h2>
            <p>Menghandle Authentication dan Membership Keanggotaan</p>
        </div>
        <div class="card">
            <img src="../image/foto3.jpg" alt="Foto 3">
            <h2>Gilang Nanda Saputra</h2>
            <p>Menghandle Tampilan Halaman dan Mengintegrasikan Back end ke Front End juga Membantu Fix Error dan Bug</p>
        </div>
    </div>

    <footer class="py-4" style="background-color: #80543b; color: #fff;">
        <div class="container">
            <div class="row">
                <!-- Logo dan Tentang Kami -->
                <div class="col-lg-4 col-md-6 mb-4 d-flex align-items-start">
                    <!-- Logo -->
                    <img src="../image/logo.jpg" alt="Logo Komunitas" class="me-3 rounded-circle" width="80px">
                    <!-- Tentang Kami -->
                    <div>
                        <h5 class="fw-bold">Tentang Kami</h5>
                        <p class="text-light" style="font-size: 0.9rem;">
                            Kami adalah komunitas yang fokus pada pengembangan siswa dan penggiat teknologi, menyediakan platform belajar, berbagi, dan berkembang bersama.
                        </p>
                    </div>
                </div>
                <!-- Navigasi Cepat -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="fw-bold">Navigasi Cepat</h5>
                    <ul class="list-unstyled">
                        <li><a href="#membership" class="text-light text-decoration-none">Keanggotaan</a></li>
                        <li><a href="#komunitas" class="text-light text-decoration-none">Komunitas</a></li>
                        <li><a href="#join" class="text-light text-decoration-none">Bergabung</a></li>
                        <li><a href="#kontak" class="text-light text-decoration-none">Kontak Kami</a></li>
                    </ul>
                </div>
                <!-- Media Sosial -->
                <div class="col-lg-4 col-md-12">
                    <h5 class="fw-bold">Ikuti Kami</h5>
                    <div>
                        <a href="https://facebook.com" class="text-light text-decoration-none me-3"><i class="bi bi-facebook"></i></a>
                        <a href="https://instagram.com" class="text-light text-decoration-none me-3"><i class="bi bi-instagram"></i></a>
                        <a href="https://twitter.com" class="text-light text-decoration-none me-3"><i class="bi bi-twitter"></i></a>
                        <a href="https://linkedin.com" class="text-light text-decoration-none"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
            </div>
            <hr style="border-color: rgba(255, 255, 255, 0.2);" />
            <div class="text-center">
                <p class="mb-0" style="font-size: 0.8rem;">&copy; 2025 SUELVI. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS & Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>