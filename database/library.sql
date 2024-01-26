-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 26 Jan 2024 pada 04.03
-- Versi server: 10.4.24-MariaDB
-- Versi PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `anggota`
--

CREATE TABLE `anggota` (
  `idAnggota` varchar(15) NOT NULL,
  `nisn` varchar(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `kelas` varchar(50) NOT NULL,
  `telepon` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `anggota`
--

INSERT INTO `anggota` (`idAnggota`, `nisn`, `nama`, `kelas`, `telepon`) VALUES
('0051964383', '', 'Arjuna Tri Kurniawan', 'XII PPLG', '085271243245'),
('0065731149', '', 'Rangga Yuda', 'XII PPLG', '085837220733'),
('0067967348', '', 'Athariq Ahmad Day', 'XII PPLG', '0895602588130'),
('0069140570', '', 'Abil Duanda Nugraha', 'XII PPLG', '089606781716');

-- --------------------------------------------------------

--
-- Struktur dari tabel `buku`
--

CREATE TABLE `buku` (
  `id` varchar(15) NOT NULL,
  `judul` text NOT NULL,
  `pengarang` varchar(50) NOT NULL,
  `kategori` text NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `buku`
--

INSERT INTO `buku` (`id`, `judul`, `pengarang`, `kategori`, `status`) VALUES
('001', 'Cantik Itu Luka', 'Eka Kurniawan', 'Novel', 'Sedang dipinjam'),
('003', 'Laskar Pelangi', 'Andrea Hirata', 'Novel', 'Tersedia'),
('004', 'Educated', 'Tara Westofer', 'Biografi', 'Tersedia'),
('005', 'Dongeng Sang Kancil', 'Asdi S. Dipodjojo', 'Dongeng', 'Tersedia'),
('006', 'Negeri 5 Menara', 'Ahmad Fuadi', 'Novel', 'Tersedia'),
('007', ' Laut Bercerita', 'Leila S. Chudori', 'Novel', 'Tersedia'),
('008', 'Bumi Manusia', 'Pramoedya Ananta Toer', 'Novel', 'Tersedia'),
('009', 'Saman', 'Ayu Utami', 'Novel', 'Tersedia');

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman`
--

CREATE TABLE `peminjaman` (
  `idPinjam` varchar(15) NOT NULL,
  `idAnggota` varchar(15) NOT NULL,
  `id` varchar(15) NOT NULL,
  `tanggal_peminjaman` date NOT NULL,
  `tanggal_pengembalian` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `peminjaman`
--

INSERT INTO `peminjaman` (`idPinjam`, `idAnggota`, `id`, `tanggal_peminjaman`, `tanggal_pengembalian`) VALUES
('01', '0065731149', '001', '2024-01-17', '2024-01-26');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_admin`
--

CREATE TABLE `tb_admin` (
  `admin_id` int(11) NOT NULL,
  `admin_name` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `admin_telp` varchar(20) NOT NULL,
  `admin_email` varchar(50) NOT NULL,
  `admin_address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tb_admin`
--

INSERT INTO `tb_admin` (`admin_id`, `admin_name`, `username`, `password`, `admin_telp`, `admin_email`, `admin_address`) VALUES
(1, 'Athariq Ahmad Day', 'admin', '21232f297a57a5a743894a0e4a801fc3', '0895602588130', 'athariqahmadday@gmail.com', 'parak kopi\r\n');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `anggota`
--
ALTER TABLE `anggota`
  ADD PRIMARY KEY (`idAnggota`);

--
-- Indeks untuk tabel `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`idPinjam`),
  ADD KEY `idAnggota` (`idAnggota`),
  ADD KEY `id` (`id`);

--
-- Indeks untuk tabel `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tb_admin`
--
ALTER TABLE `tb_admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`idAnggota`) REFERENCES `anggota` (`idAnggota`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `peminjaman_ibfk_2` FOREIGN KEY (`id`) REFERENCES `buku` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
