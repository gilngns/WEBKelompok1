-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 08 Feb 2025 pada 11.13
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `suelvi`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `memberships`
--

CREATE TABLE `memberships` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `type` enum('product_promo','subscription') DEFAULT NULL,
  `status` enum('batal','selesai','pending') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `memberships`
--

INSERT INTO `memberships` (`id`, `user_id`, `start_date`, `end_date`, `type`, `status`) VALUES
(1, 5, '2025-02-08', '2025-03-08', 'product_promo', 'pending'),
(3, 2, '2025-02-08', '2025-03-08', 'product_promo', 'pending');

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `status` enum('pending','success','canceled') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `name`, `gambar`, `price`, `description`) VALUES
(3, 'Pupuk Organik Cair 12', '1738930993_feey-ZnK3vfLzUg4-unsplash.jpg', 150000.00, 'Pupuk Organik Cair 1'),
(4, 'Pupuk Organik Cair 2', '1738931125_feey-0xoF2R50GOA-unsplash.jpg', 200000.00, 'Pupuk Organik Cair 23'),
(5, 'Pupuk Organik Cair 13', '1738931557_gowtham-agm-QF83-E-bAfY-unsplash.jpg', 120000.00, 'oakwoko'),
(6, 'Pupuk Organik Cair 14', '1738931586_charmander.png', 250000.00, 'oakwokakwkoka'),
(7, 'Pupuk Organik Cair 15', '1738931611_pikachu.png', 1000000.00, 'asdasdasdasdasd'),
(8, 'Pupuk Organik Cair 17', '1738931742_squirtle.png', 150000.00, 'aokwkaowko');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','customer') DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$n8dbTE6TgbHG.LRqlldth.T54B6BIJ6MZ5jWk8kaAvzx6rnUyv2ba', 'admin'),
(2, 'budi', 'budi@gmail.com', '$2y$10$wyfpO7DulLAqE5z4/C5wnucxVexkshWiDmrE8BXBxRARCwVHr7ERW', 'customer'),
(3, 'Zaqi ', 'zaqizidan27@gmail.com', '$2y$10$PvjPeYrLULgcimwHI.DIFOplhjXYQStt0PbJ.XOUOCpTM8ISnKhMi', 'customer'),
(4, 'gilang uciha', 'gilanguciha09@gmail.com', '$2y$10$D7zYqEupn4vp8HYkXplaeO3SWbCwo5GSyCDWQ9pqe1s8RT5Hjfn7q', 'customer'),
(5, 'aqsal senju', 'aqsalsenju03@gmail.com', '$2y$10$FpkR.B2JrWWwxg3BUbW1Mud5lVckR7CVH23Rmh15bXBeBIzlIDxh.', 'customer');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `memberships`
--
ALTER TABLE `memberships`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `memberships`
--
ALTER TABLE `memberships`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `memberships`
--
ALTER TABLE `memberships`
  ADD CONSTRAINT `memberships_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
