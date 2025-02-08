<?php
include('../koneksi.php');
session_start();

$success = false;
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['email'])) { // Cek apakah user sudah login berdasarkan email
        die("Silakan login terlebih dahulu.");
    }

    // Ambil user_id dari database berdasarkan email yang tersimpan di session
    $email = $_SESSION['email'];
    $query = "SELECT id FROM users WHERE email = '$email'";
    $result = mysqli_query($koneksi, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        $user_id = $row['id'];
    } else {
        die("User tidak ditemukan.");
    }

    $type = $_POST['membershipOption'] ?? '';

    if ($type === '') {
        $error = "Silakan pilih jenis keanggotaan.";
    } else {
        // Cek apakah user sudah terdaftar di membership
        $query = "SELECT id FROM memberships WHERE user_id = $user_id";
        $result = mysqli_query($koneksi, $query);

        if (mysqli_num_rows($result) > 0) {
            $error = "Anda sudah terdaftar sebagai member!";
        } else {
            // Jika belum terdaftar, tambahkan ke database
            $query = "INSERT INTO memberships (user_id, type, status, start_date, end_date) 
                      VALUES ($user_id, '$type', 'pending', NOW(), DATE_ADD(NOW(), INTERVAL 1 MONTH))";

            if (mysqli_query($koneksi, $query)) {
                $success = true;
            } else {
                $error = "Terjadi kesalahan, coba lagi nanti.";
            }
        }
    }
}
?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Suelvi</title>
    <link rel="icon" type="image/x-icon" href="../image/logo.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Link CSS AOS -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">



</head>

<body>

    <!-- Navbar -->
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
                        <a class="nav-link" href="#edukasi">Edukasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#produk">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#membership">Membership</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#komunitas">Komunitas</a>
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


    <!-- Carousel -->
    <div id="carouselExampleCaptions" class="carousel slide " data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://images.pexels.com/photos/7944406/pexels-photo-7944406.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2" class="d-block w-100" alt="First Slide">
                <div class="carousel-caption d-none d-md-block" style="font-family: 'Poppins', sans-serif;">
                    <h5 style="font-family: 'Poppins', sans-serif;">Dari Alam Kembali Ke Alam</h5>
                    <p style="font-family: 'Poppins', sans-serif;">Solusi pertanian organik yang ramah lingkungan bersama SUELVI</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://images.pexels.com/photos/6511168/pexels-photo-6511168.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2" class="d-block w-100" alt="Second Slide">
                <div class="carousel-caption d-none d-md-block" style="font-family: 'Poppins', sans-serif;">
                    <h5 style="font-family: 'Poppins', sans-serif;">Menghadirkan Masa Depan Hijau</h5>
                    <p style="font-family: 'Poppins', sans-serif;">Solusi pertanian organik yang ramah lingkungan bersama SUELVI</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://images.pexels.com/photos/9871920/pexels-photo-9871920.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2" class="d-block w-100" alt="Third Slide">
                <div class="carousel-caption d-none d-md-block" style="font-family: 'Poppins', sans-serif;">
                    <h5 style="font-family: 'Poppins', sans-serif;">Berkembang Bersama Alam</h5>
                    <p style="font-family: 'Poppins', sans-serif;">Solusi pertanian organik yang ramah lingkungan bersama SUELVI</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Edukasi Section -->
    <section id="edukasi" class="container py-5 poppins-regular">
        <h2 class="text-center mb-5" style="font-family: 'Poppins', sans-serif; color: #2a2c39;"
            data-aos="fade-up">
            Edukasi
        </h2>
        <div class="row g-4">
            <!-- Card Edukasi Gratis -->
            <div class="col-md-6" data-aos="fade-right" data-aos-delay="500">
                <div class="card shadow-lg border-0 rounded-4 h-100">
                    <div class="position-relative" style="padding: 10px;">
                        <img src="../image/edukasi1.jpg" class="card-img-top rounded-4" alt="Edukasi Gratis">
                        <div class="position-absolute top-0 start-0 p-2 bg-success text-white rounded-3" style="z-index: 1;">
                            Studi Ilmiah
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title" style="font-family: 'Raleway', sans-serif;">5 Prinsip Pengolahan Tanah Berkelanjutan</h5>
                        <p class="card-text" style="opacity: 0.8;">Sumber: FAO (Food and Agriculture Organization)</p>
                        <ul>
                            <li>✅ Peningkatan bahan organik tanah 2-3% meningkatkan produktivitas 20-30%</li>
                            <li>✅ Rotasi tanaman mengurangi resiko penyakit tanah hingga 45%</li>
                            <li>❌ Pupuk kimia berlebihan menurunkan pH tanah 0.5-1.0 dalam 5 tahun</li>
                        </ul>
                    </div>
                    <div class="card-footer text-center">
                        <a class="btn btn-success rounded-pill px-4 py-2" href="https://www.fao.org/home/en">Pelajari Lebih Lanjut</a>
                    </div>
                </div>
            </div>

            <!-- Card Edukasi Berbayar -->
            <div class="col-md-6" data-aos="fade-left" data-aos-delay="500">
                <div class="card shadow-lg border-0 rounded-4 h-100">
                    <div class="position-relative" style="padding: 10px;">
                        <img src="../image/edukasi2.jpg" class="card-img-top rounded-4" alt="Edukasi Berbayar">
                        <div class="position-absolute top-0 start-0 p-2 bg-success text-white rounded-3" style="z-index: 1;">
                            Data Visual
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title" style="font-family: 'Raleway', sans-serif;">Infografis: Dampak Pupuk Organik vs Kimia</h5>
                        <p class="card-text" style="opacity: 0.8;">Sumber: Kementrian Pertanian RI 2022</p>

                        <div class="d-flex justify-content-between mb-4">
                            <div class="card me-2 h-100" style="width: 48%; border-radius: 10px;" data-aos="zoom-in" data-aos-delay="500">
                                <div class="card-header text-white " style="background-color: #80543b;">
                                    Pupuk Organik
                                </div>
                                <div class="card-body">
                                    <ul>
                                        <li>▶️ Retensi air +35%</li>
                                        <li>▶️ Mikroorganisme +400%</li>
                                        <li>▶️ Hasil panen tahun ke-5 +22%</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="card ms-2 h-100" style="width: 48%; border-radius: 10px;" data-aos="zoom-in" data-aos-delay="500">
                                <div class="card-header bg-danger text-white ">
                                    Pupuk Kimia
                                </div>
                                <div class="card-body">
                                    <ul>
                                        <li>🔽 Erosi tanah 2x lebih cepat</li>
                                        <li>🔽 Biaya input +45%</li>
                                        <li>🔽 Ketergantungan pupuk +300%</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a class="btn btn-success rounded-pill px-4 py-2" href="../suelvi.pdf" download>Download PDF</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Membership Section -->
    <section id="membership" class="container py-5" style="background: linear-gradient(135deg, #80543b, #8E6B55FF); border-radius: 15px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <h2 class="text-center mb-5" style="font-family: 'Poppins', sans-serif; color: white;">Keanggotaan SUELVI</h2>
        <div class="row g-4">
            <!-- Membership Gratis (Discord) -->
            <div class="col-md-6" data-aos="zoom-in" data-aos-delay="300">
                <div class="card shadow-lg border-0 rounded-4 bg-light h-100" style="max-width: 95%; margin: auto;">
                    <div class="card-body text-center p-3" style="background: #fff; border-radius: 10px; border: 1px solid #ddd;">
                        <h5 class="card-title" style="font-family: 'Raleway', sans-serif; color: #2a2c39; font-size: 1.2rem;">Keanggotaan Gratis</h5>
                        <ul class="list-unstyled mb-3" style="font-size: 0.9rem;">
                            <li><i class="bi bi-check-circle" style="color: #ef6603;"></i> Akses ke artikel dasar</li>
                            <li><i class="bi bi-check-circle" style="color: #ef6603;"></i> Bergabung dengan grup Discord umum</li>
                            <li><i class="bi bi-check-circle" style="color: #ef6603;"></i> Dapatkan tips dan trik gratis lainnya!</li>
                        </ul>
                        <a href="https://discord.gg/mGASctvy" target="_blank" class="btn btn-primary rounded-pill shadow-lg" style="padding: 10px 20px; font-size: 14px;">Gabung Discord</a>
                    </div>
                </div>
            </div>

            <!-- Membership Berbayar -->
            <div class="col-md-6" data-aos="zoom-in" data-aos-delay="500">
                <div class="card shadow-lg border-0 rounded-4 bg-light h-100" style="max-width: 95%; margin: auto;">
                    <div class="card-body text-center p-3" style="background: #fff; border-radius: 10px; border: 1px solid #ddd;">
                        <h5 class="card-title" style="font-family: 'Raleway', sans-serif; color: #2a2c39; font-size: 1.2rem;">Keanggotaan Premium (250k/Member)</h5>
                        <p class="card-text mb-3" style="font-size: 0.9rem;">Akses penuh ke semua edukasi, produk dengan diskon, dan banyak manfaat lainnya. Cukup dengan biaya bulanan!</p>
                        <ul class="list-unstyled mb-3" style="font-size: 0.9rem;">
                            <li><i class="bi bi-check-circle" style="color: #ef6603;"></i> E-book lanjutan</li>
                            <li><i class="bi bi-check-circle" style="color: #ef6603;"></i> Webinar bulanan</li>
                            <li><i class="bi bi-check-circle" style="color: #ef6603;"></i> Akses grup privat + mentor</li>
                        </ul>
                        <div class="d-flex justify-content-center">
                            <a href="#" class="btn btn-success rounded-pill shadow-lg" style="padding: 10px 20px; font-size: 14px;" data-bs-toggle="modal" data-bs-target="#membershipModal">Gabung Sekarang</a>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Modal Form -->
            <div class="modal fade" id="membershipModal" tabindex="-1" aria-labelledby="membershipModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="membershipModalLabel">Pilih Opsi Keanggotaan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post">
                                <div class="mb-3">
                                    <label for="membershipOption" class="form-label">Pilih Opsi</label>
                                    <select class="form-select" id="membershipOption" name="membershipOption" required>
                                        <option value="product_promo">Beli produk 3 liter & dapat bonus membership (300k)</option>
                                        <option value="subscription">Langsung daftar sebagai member (250k)</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn-success w-100 rounded-pill p-2" style="color: white; font-family: poppins;">Lanjutkan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Komunitas Section -->
    <section id="komunitas" class="py-5" style="background-color: #f7f3ef;" data-aos="zoom-in" data-aos-delay="500">
        <div class="container">
            <div class="row align-items-center">
                <!-- Gambar -->
                <div class="col-lg-6 text-center mb-4 mb-lg-0" data-aos="fade-right" data-aos-delay="500">
                    <img src="https://plus.unsplash.com/premium_photo-1663089750414-a3071f3b9d62?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="img-fluid rounded" alt="Komunitas">
                </div>
                <!-- Deskripsi -->
                <div class="col-lg-6" data-aos="fade-left" data-aos-delay="500">
                    <h2 class="fw-bold" style="color: #80543b;">Bergabung dengan Komunitas Kami!</h2>
                    <p class="text-muted">
                        Kami adalah komunitas yang terdiri dari siswa dan penggiat teknologi. Bersama, kita berbagi ilmu, pengalaman, dan menjalin relasi yang bermanfaat.
                        Yuk, jadi bagian dari perjalanan inspiratif ini!
                    </p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Saling berbagi pengalaman</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Mengikuti acara eksklusif</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Belajar dari mentor ahli</li>
                    </ul>
                    <a href="https://wa.me/qr/R7VZU5MNSYX6J1" class="btn text-white" style="background-color: #80543b;">Bergabung Sekarang</a>
                </div>
            </div>
        </div>
    </section>

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



    <?php if ($success): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: "Transfer ke Rekening!",
                    text: "Silakan transfer ke rekening berikut: 123-456-789 (BCA) a.n SUELVI",
                    imageUrl: "https://img.freepik.com/free-vector/one-man-jumping-cheerful-icon-isolated_18591-82710.jpg?t=st=1739005458~exp=1739009058~hmac=6b6a7250d781fb3fe2140429d9fff7788c8f46a82d6c136abb5a20505daa461b&w=826",
                    imageWidth: 400,
                    imageHeight: 400,
                    imageAlt: "Custom image"
                });
            });
        </script>
    <?php elseif ($error): ?>
       <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: "Gagal!",
                    text: "<?php echo $error ?>",
                    imageUrl: "https://img.freepik.com/free-vector/woman-with-depression-raining-cloud_24908-81677.jpg?t=st=1739007587~exp=1739011187~hmac=344d965e1fbc005fd037f5dfb09c0c5f7f6df43a96efccef6f48115d5dc44631&w=826",
                    imageWidth: 400,
                    imageHeight: 400,
                    imageAlt: "Custom image"
                });
            });
        </script>
    <?php endif; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        AOS.init();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>