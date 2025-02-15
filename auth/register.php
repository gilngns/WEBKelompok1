<?php
include ('../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($koneksi, $_POST['name']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $role = ($email == 'admin@gmail.com') ? 'admin' : 'customer';

    $query = "INSERT INTO users (name, email, password, role) 
              VALUES ('$name', '$email', '$hashed_password', '$role')";
    
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Registrasi berhasil!'); window.location='login.php';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
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
    <title>Daftar</title>
</head>
<body>
    <div class="container">
        <h2 class="poppins-semibold">Daftar</h2>
        <form action="" method="POST"> <!-- Form mengarah ke halaman yang sama -->
            <input type="text" id="name" name="name" placeholder="Name" required>
            <br><br>
            <input type="email" id="email" name="email" placeholder="Email" required>
            <br><br>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <br><br><br>
            <button type="submit">Daftar</button>
        </form>
        <p class="register-link">Saya sudah memiliki akun <a href="login.php">Masuk</a></p>
    </div>
</body>
</html>
