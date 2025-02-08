<?php
// Include database connection file
include ('db.php');

// Check if user is logged in
if (isset($_SESSION['username'])) {
  echo '<div class="nav-akun">';
  echo 'Selamat datang, ' . $_SESSION['username'];
  echo '<a href="logout.php">Logout</a>';
  echo '</div>';
} else {
  // Tampilkan tombol login dan signup
  echo '<button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#myModal">';
  echo 'Login';
  echo '</button>';
  echo '<a href="#membership" class="btn btn-primary">Sign Up</a>';
}
?>