<?php
session_start();
require_once 'koneksi.php';
if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="index.php"</script>';
}

// Memeriksa apakah parameter ID peminjaman diberikan
if (isset($_GET["idPinjam"])) {
    $idPinjam = $_GET["idPinjam"];

    // Mengambil data peminjaman dari database
    $sql = "SELECT * FROM peminjaman WHERE idPinjam = $idPinjam";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    // Tambahkan ini untuk mendefinisikan variabel
    $newIdBuku = $row['id'];
    $idBukuSaatIni = $row['id'];

    // Memeriksa apakah form edit peminjaman sudah disubmit
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $idAnggota = $_POST["idAnggota"];
        $id = $_POST["id"];
        $tanggal_peminjaman = $_POST["tanggal_peminjaman"];
        $tanggal_pengembalian = $_POST["tanggal_pengembalian"];
        // Validasi ID buku dan ID anggota (hanya angka)
        if (!preg_match("/^[0-9]+$/", $id) || !preg_match("/^[0-9]+$/", $idAnggota)) {

            echo '<script>alert("Format Kode Buku atau NISN tidak valid. Hanya angka diizinkan")</script>';
            echo '<script>window.location="edit_peminjaman.php?act=peminjaman"</script>';
            exit();
        }

        // Query untuk memastikan ID buku dan ID anggota yang diubah sudah ada dalam tabel buku dan anggota
        $sql_check_buku = "SELECT * FROM buku WHERE id = '$id'";
        $result_check_buku = $conn->query($sql_check_buku);

        $sql_check_anggota = "SELECT * FROM anggota WHERE idAnggota = '$idAnggota'";
        $result_check_anggota = $conn->query($sql_check_anggota);

        if ($result_check_buku->num_rows === 0) {
            echo '<script>alert("Kode buku tidak ditemukan dalam tabel buku.")</script>';
            echo '<script>window.location="edit_peminjaman.php?act=peminjaman"</script>';
            exit();
        }

        if ($result_check_anggota->num_rows === 0) {
            echo '<script>alert("NISN tidak ditemukan dalam tabel siswa.")</script>';
            echo '<script>window.location="edit_peminjaman.php?act=peminjaman"</script>';
            exit();
        }
        // Memeriksa apakah kode buku berubah
        if ($newIdBuku !== $idBukuSaatIni) {
            // Kode buku berubah, periksa status buku yang baru
            $sql_check_book_status = "SELECT status FROM buku WHERE id = $newIdBuku";
            $result_book_status = $conn->query($sql_check_book_status);

            if ($result_book_status->num_rows > 0) {
                $row_book_status = $result_book_status->fetch_assoc();
                $bookStatus = $row_book_status["status"];

                if ($bookStatus === "Sedang dipinjam") {
                    echo '<script>alert("Buku dengan Kode Buku ini tidak tersedia untuk dipinjam.")</script>';
                    echo '<script>window.location="edit_peminjaman.php?act=peminjaman"</script>';
                    exit();
                }
            } else {
                echo '<script>alert("Buku dengan Kode Buku ini tidak ditemukan.")</script>';
                echo '<script>window.location="edit_peminjaman.php?act=peminjaman"</script>';
                exit();
            }
        }

        // Mengupdate data peminjaman di database
        $sql_update = "UPDATE peminjaman SET id = '$newIdBuku', idAnggota = '$idAnggota', tanggal_peminjaman = '$tanggal_peminjaman', tanggal_pengembalian = '$tanggal_pengembalian' WHERE idPinjam = $idPinjam";

        if ($conn->query($sql_update) === TRUE) {
            header("Location: daftar_peminjaman.php");
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }

    // Mengambil daftar buku dari database
    $sql1 = "SELECT * FROM buku";
    $result1 = $conn->query($sql1);

    // Mengambil daftar anggota dari database
    $sql2 = "SELECT * FROM anggota";
    $result2 = $conn->query($sql2);

    $conn->close();
} else {
    echo "ID Pinjam tidak diberikan.";
    header("Location: daftar_peminjaman.php");
    exit();
}
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <title>Edit Peminjaman</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>

<body>
    <div class="container">
        <h2 align="center">Edit Peminjaman</h2><br>
        <form method="POST" onsubmit="return validateForm()" action="<?php echo $_SERVER["PHP_SELF"] . "?idPinjam=" . $idPinjam; ?>">
            <div class="form-group">
                <label for="idAnggota">NISN:</label>
                <select name="idAnggota" id="idAnggota" class="form-control">
                    <?php

                    if ($result2->num_rows > 0) {
                        while ($row2 = $result2->fetch_assoc()) {
                            // Menentukan di anggota yang saat ini dipilih
                            $selected_anggota = $row['idAnggota'];
                            // Memeriksa jika ID anggota yang saat ini diiterasi adalah yang dipilih, dan tampilkan sebagai opsi pertama
                            if ($row2["idAnggota"] === $selected_anggota) {
                                echo "<option value='" . $row2["idAnggota"] . "' selected>" . $row2["idAnggota"] . "</option>";
                            } else {
                                echo "<option value='" . $row2["idAnggota"] . "'>" . $row2["idAnggota"] . "</option>";
                            }
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="id">Kode Buku:</label>
                <select name="id" id="id" class="form-control">
                    <?php

                    if ($result1->num_rows > 0) {
                        while ($row1 = $result1->fetch_assoc()) {
                            // Menentukan id buku yang saat ini dipilih
                            $selected_buku = $row['id'];
                            // Memeriksa jika idbuku yang saat ini diiterasi adalah yang dipilih, dan tampilkan sebagai opsi pertama
                            if ($row1["id"] === $selected_buku) {
                                echo "<option value='" . $row1["id"] . "' selected>" . $row1["id"] . "</option>";
                            } else {
                                echo "<option value='" . $row1["id"] . "'>" . $row1["id"] . "</option>";
                            }
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="tanggal_peminjaman">Tanggal Pinjam:</label>
                <input type="date" name="tanggal_peminjaman" id="tanggal_peminjaman" value="<?php echo $row["tanggal_peminjaman"]; ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="tanggal_pengembalian">Tanggal Kembali:</label>
                <input type="date" name="tanggal_pengembalian" id="tanggal_pengembalian" value="<?php echo $row["tanggal_pengembalian"]; ?>" class="form-control">
            </div>
            <div class="container">
                <input type="submit" value="Simpan" class="btn btn-primary">
                <a class="btn btn-primary" href="daftar_peminjaman.php">Kembali</a> <!-- Tambahkan tautan ini -->
            </div>
        </form>
    </div>
    <script>
        function validateForm() {
            // Validasi tanggal pengembalian harus sesudah tanggal peminjaman
            var tanggalPeminjaman = new Date(document.getElementById('tanggal_peminjaman').value);
            var tanggalPengembalian = new Date(document.getElementById('tanggal_pengembalian').value);

            if (tanggalPengembalian <= tanggalPeminjaman) {
                alert('Tanggal pengembalian harus sesudah tanggal peminjaman.');
                return false;
            }

        }
    </script>
</body>

</html>