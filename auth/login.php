<?php
session_start();
include ('../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];

    // Ambil data user berdasarkan email
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($koneksi, $query);

    if (!$result) {
        die("Query Error: " . mysqli_error($koneksi));
    }

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Verifikasi password dengan password_hash
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['email'] = $row['email'];

            // Arahkan berdasarkan role
            if ($row['role'] == 'admin') {
                header('Location: ../dashboard/dashboard.php');
            } else {
                header('Location: ../home/index.php');
            }
            exit;
        } else {
            echo "<script>alert('Password salah!'); window.location='login.php';</script>";
        }
    } else {
        echo "<script>alert('Email tidak ditemukan!'); window.location='login.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <title>Log in</title>
    <style>
      body {
        background-image: url('bg-login.jpg');
      }
    </style>
</head>
<body> 
    <div class="container">
        <h2 class="poppins-semibold">Masuk</h2>
        <form action="login.php" method="POST">
            <input type="email" id="email" name="email" placeholder="Email" required>
            <br><br>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <br><br><br>
            <button type="submit">Masuk</button>
        </form>
        <p class="register-link">Belum mempunyai akun? <a href="register.php">Daftar</a></p>
    </div>
</body>
</html>