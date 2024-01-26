<?php
session_start();
require_once 'koneksi.php';
if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="index.php"</script>';
}

// Memeriksa apakah parameter ID peminjaman diberikan
if (isset($_GET["idPinjam"])) {
    $idPinjam = $_GET["idPinjam"];

    // Mendapatkan ID buku dari peminjaman
    $sql_get_id_buku = "SELECT id FROM peminjaman WHERE idPinjam = $idPinjam";
    $result_get_id_buku = $conn->query($sql_get_id_buku);

    if ($result_get_id_buku->num_rows === 1) {
        $row_get_id_buku = $result_get_id_buku->fetch_assoc();
        $idBuku = $row_get_id_buku['id'];

        // Query untuk memperbarui status buku dalam tabel buku
        $sql_update_status_buku = "UPDATE buku SET status = 'Tersedia' WHERE id = $idBuku";

        if ($conn->query($sql_update_status_buku) === TRUE) {
            // Setelah mengubah status buku, kita melanjutkan dengan menghapus peminjaman
            $sql_hapus_peminjaman = "DELETE FROM peminjaman WHERE idPinjam = $idPinjam";
            if ($conn->query($sql_hapus_peminjaman) === TRUE) {
                header("Location: daftar_peminjaman.php");
                exit();
            } else {
                echo "Error menghapus peminjaman: " . $conn->error;
            }
        } else {
            echo "Error mengubah status buku: " . $conn->error;
        }
    } else {
        echo "Peminjaman tidak ditemukan.";
    }
} else {
    echo "ID Pinjam tidak diberikan.";
    header("Location: daftar_peminjaman.php");
    exit();
}
