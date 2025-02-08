<?php
// Konfigurasi database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'sue_lvi';

// Koneksi ke database
$koneksi = mysqli_connect($host, $username, $password, $database);

// Cek apakah koneksi berhasil
if (!$koneksi) {
  die("Koneksi gagal: " . mysqli_connect_error());
}
?>