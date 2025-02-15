<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #5d4037, #3e2723);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
            text-align: center;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            background: rgba(255, 255, 255, 0.15);
            padding: 40px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            max-width: 500px;
            animation: fadeIn 1s ease-in-out;
        }
        img {
            max-width: 250px;
            animation: float 3s ease-in-out infinite;
        }
        h1 {
            font-size: 2rem;
            font-weight: 700;
        }
        p {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }
        .btn {
            background: #8d6e63;
            color: #fff;
            border: none;
            padding: 12px 20px;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 25px;
            transition: 0.3s;
        }
        .btn:hover {
            background: #6d4c41;
            color: #fff;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
    </style>
</head>
<body>

<div class="container">
    <img src="https://img.freepik.com/free-vector/403-error-forbidden-concept-illustration_114360-1924.jpg?w=826" alt="Akses Ditolak">
    <h1 class="mt-3">Oops! Akses Ditolak</h1>
    <p>Sepertinya Anda tidak memiliki izin untuk mengakses halaman ini.<br>Silakan kembali atau hubungi administrator.</p>
    <a href="../home/index.php" class="btn">Kembali ke Beranda</a>
</div>

</body>
</html>
