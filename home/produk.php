    <?php
    session_start();
    include("../koneksi.php");

    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('Anda harus login terlebih dahulu!'); window.location.href='../Auth/login.php';</script>";
        exit();
    }
    
    $limit = 6;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $start = ($page - 1) * $limit;
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $filter_price = isset($_GET['filter_price']) ? $_GET['filter_price'] : '';

    $query = "SELECT * FROM products WHERE 1";

    if (!empty($search)) {
        $query .= " AND name LIKE '%$search%'";
    }
    if (!empty($filter_price)) {
        $query .= " AND price <= $filter_price";
    }
    $query .= " LIMIT $start, $limit";

    $total_results = $koneksi->query("SELECT COUNT(id) AS count FROM products")->fetch_assoc()['count'];
    $total_pages = ceil($total_results / $limit);
    $products = $koneksi->query($query);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $user_id = $_SESSION['user_id']; // Ganti dengan user yang login
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];
        $total_price = $quantity * $price;
        $order_date = date('Y-m-d H:i:s');
        $status = "Pending";
        
        $stmt = $koneksi->prepare("INSERT INTO orders (user_id, product_id, quantity, total_price, order_date, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiidss", $user_id, $product_id, $quantity, $total_price, $order_date, $status);
        $stmt->execute();
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Pesanan Berhasil!',
                text: 'Silakan transfer ke rekening berikut: 123-456-789 (BCA) a.n SUELVI',
                imageUrl: 'https://img.freepik.com/free-vector/one-man-jumping-cheerful-icon-isolated_18591-82710.jpg?t=st=1739005458~exp=1739009058~hmac=6b6a7250d781fb3fe2140429d9fff7788c8f46a82d6c136abb5a20505daa461b&w=826',
                imageWidth: 400,
                imageHeight: 400,
                imageAlt: 'Custom image',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'produk.php';
            });
        });
    </script>";

        exit();
    }
    ?>


    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Katalog Produk</title>
        <link rel="icon" type="image/x-icon" href="../image/logo.jpg">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background-color: #f5f5f5;
            }
            .header {
                background: linear-gradient(135deg, #80543b, #a67c52);
                color: white;
                padding: 20px;
                text-align: center;
                font-size: 28px;
                font-weight: bold;
                border-radius: 15px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            }
            .product-card {
                border: none;
                border-radius: 15px;
                padding: 15px;
                text-align: center;
                background: white;
                box-shadow: 0 5px 10px rgba(0, 0, 0, 0.15);
                transition: transform 0.3s ease-in-out;
            }
            .product-card:hover {
                transform: scale(1.07);
            }
            .product-card img {
                width: 100%;
                height: 350px;
                object-fit: cover;
                border-radius: 12px;
            }
            .btn-buy, .btn-brown {
                background: linear-gradient(135deg, #80543b, #a67c52);
                color: white;
                border: none;
                padding: 12px 16px;
                border-radius: 10px;
                cursor: pointer;
                font-weight: bold;
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
    </head>

    <body>
        <div class="container mt-4">
            <a href="index.php" class="btn btn-brown mb-3">⬅ Kembali</a>
            <div class="header">🌿 Katalog Pupuk Cair Organik 🌿</div>
            
            <?php
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $min_price = isset($_GET['min_price']) && is_numeric($_GET['min_price']) ? (int) $_GET['min_price'] : 0;
    $max_price = isset($_GET['max_price']) && is_numeric($_GET['max_price']) ? (int) $_GET['max_price'] : 0;

    $query = "SELECT * FROM products WHERE 1=1";

    if (!empty($search)) {
        $query .= " AND name LIKE '%$search%'";
    }
    if ($min_price > 0) {
        $query .= " AND price >= $min_price";
    }
    if ($max_price > 0) {
        $query .= " AND price <= $max_price";
    }

    $query .= " LIMIT $start, $limit";

    // Debug query untuk cek apakah query benar
    //echo "<pre>$query</pre>";

    $products = $koneksi->query($query);
    ?>

    <form class="d-flex mt-4 mb-3" method="GET">
        <input class="form-control me-2" type="text" name="search" placeholder="Cari produk" value="<?= htmlspecialchars($search); ?>">
        <input class="form-control me-2" type="number" name="min_price" placeholder="Min Harga" value="<?= $min_price; ?>">
        <input class="form-control me-2" type="number" name="max_price" placeholder="Max Harga" value="<?= $max_price; ?>">
        <button class="btn-brown" type="submit">Cari</button>
    </form>

    <div class="row">
        <?php while ($row = $products->fetch_assoc()) { ?>
            <div class="col-md-4 mb-4">
                <div class="product-card">
                    <img src="../uploads/<?= $row['gambar']; ?>" class="img-fluid mb-2" alt="<?= $row['name']; ?>">
                    <h5><?= $row['name']; ?></h5>
                    <p>Rp <?= number_format($row['price'], 0, ',', '.'); ?></p>
                    <p><?= $row['description']; ?></p>
                    <button class="btn-buy" data-bs-toggle="modal" data-bs-target="#buyModal" 
                        data-id="<?= $row['id']; ?>" data-name="<?= $row['name']; ?>" data-price="<?= $row['price']; ?>">
                        Beli Sekarang
                    </button>
                </div>
            </div>
        <?php } ?>
    </div>
        <div class="modal fade" id="buyModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Pembelian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST">
                            <input type="hidden" id="productId" name="product_id">
                            <div class="mb-3">
                                <label for="productName">Nama Produk</label>
                                <input type="text" class="form-control" id="productName" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="productPrice">Harga</label>
                                <input type="text" class="form-control" id="productPrice" readonly>
                                <input type="hidden" name="price" id="hiddenProductPrice">
                            </div>
                            <div class="mb-3">
                                <label>Jumlah</label>
                                <input type="number" class="form-control" name="quantity" id="quantity" min="1" value="1">
                            </div>
                            <button type="submit" class="btn-brown">Konfirmasi Pembelian</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            var buyModal = document.getElementById('buyModal');
            buyModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var id = button.getAttribute('data-id');
                var name = button.getAttribute('data-name');
                var price = button.getAttribute('data-price');

                document.getElementById('productId').value = id;
                document.getElementById('productName').value = name;
                document.getElementById('productPrice').value = 'Rp ' + new Intl.NumberFormat('id-ID').format(price);
                document.getElementById('hiddenProductPrice').value = price;
            });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>        <nav>
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                        <li class="page-item <?= ($page == $i) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?= $i; ?>"> <?= $i; ?> </a>
                        </li>
                    <?php } ?>
                </ul>
            </nav>
        </div>
    </body>
    </html>
