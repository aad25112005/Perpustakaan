<?php
session_start();
require_once 'koneksi.php';
if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="index.php"</script>';
}

// Menghapus peminjaman
if (isset($_GET['hapus'])) {
    $idPinjam = $_GET['hapus'];
    $sql_hapus = "DELETE FROM peminjaman WHERE idPinjam = $idPinjam";
    $conn->query($sql_hapus);
}

// Memeriksa apakah form peminjaman sudah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idPinjam = $_POST["idPinjam"];
    $idAnggota = $_POST["idAnggota"];
    $id = $_POST["id"];
    $tanggal_peminjaman = $_POST["tanggal_peminjaman"];
    $tanggal_pengembalian = $_POST["tanggal_pengembalian"];
    // Validasi format ID Pinjam (hanya angka diizinkan)
    if (!preg_match("/^[0-9]+$/", $idPinjam)) {
        echo '<script>alert("Format ID Pinjam tidak valid. Hanya angka diizinkan.")</script>';
        echo '<script>window.location="tambah_peminjaman.php?act=peminjaman"</script>';
        exit();
    }
    // Cek apakah ID Pinjam sudah ada dalam database
    $sql_check = "SELECT idPinjam FROM peminjaman WHERE idPinjam= '$idPinjam'";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        echo '<script>alert("ID Pinjam sudah ada dalam database. Mohon gunakan ID Pinjam lain.")</script>';
        echo '<script>window.location="tambah_peminjaman.php?act=Peminjaman"</script>';
        exit();
    }
    // Cek apakah buku dengan ID buku yang dipinjam tersedia
    $sql_check_book_status = "SELECT status FROM buku WHERE id = $id";
    $result_book_status = $conn->query($sql_check_book_status);

    if ($result_book_status->num_rows > 0) {
        $row_book_status = $result_book_status->fetch_assoc();
        $bookStatus = $row_book_status["status"];

        if ($bookStatus !== "Tersedia") {
            echo '<script>alert("Buku tidak tersedia untuk dipinjam.")</script>';
            echo '<script>window.location="tambah_peminjaman.php?act=Peminjaman"</script>';
            exit();
        }
    } else {
        echo '<script>alert("Buku dengan Kode Buku ini tidak ditemukan.")</script>';
        echo '<script>window.location="tambah_peminjaman.php?act=Peminjaman"</script>';
        exit();
    }

    // Memasukkan data peminjaman ke database
    $sql = "INSERT INTO peminjaman (idPinjam, idAnggota, id, tanggal_peminjaman, tanggal_pengembalian) VALUES ('$idPinjam', '$idAnggota', '$id', '$tanggal_peminjaman', '$tanggal_pengembalian')";

    if ($conn->query($sql) === TRUE) {
        // Setelah berhasil memasukkan data peminjaman, perbarui status buku menjadi "sedang dipinjam"
        $sql_update_status = "UPDATE buku SET status = 'Sedang dipinjam' WHERE id = $id";
        $conn->query($sql_update_status);

        header("Location: daftar_Peminjaman.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Mengambil daftar buku dari database
$sql = "SELECT * FROM buku";
$result = $conn->query($sql);

// Mengambil daftar anggota dari database
$sql2 = "SELECT * FROM anggota";
$result2 = $conn->query($sql2);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <title>Tambah Peminjaman Buku</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>

<body>
    <div class="container">
        <h2 align="center">Tambah Peminjaman</h2><br>
        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="idPinjam">ID Pinjam:</label>
                <input type="text" name="idPinjam" id="idPinjam" class="form-control" required><br>

                <label for="idAnggota">NISN:</label>
                <select name="idAnggota" id="idAnggota" class="form-control">
                    <?php
                    if ($result2->num_rows > 0) {
                        while ($row2 = $result2->fetch_assoc()) {
                            echo "<option value='" . $row2["idAnggota"] . "'>" . $row2["idAnggota"] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="id">Kode Buku:</label>
                <select name="id" id="id" class="form-control">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row["id"] . "'>" . $row["id"] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="tanggal_peminjaman">Tanggal Pinjam:</label>
                <input type="date" name="tanggal_peminjaman" id="tanggal_peminjaman" class="form-control" required><br>
            </div>
            <div class="form-group">
                <label for="tanggal_pengembalian">Tanggal Kembali:</label>
                <input type="date" name="tanggal_pengembalian" id="tanggal_pengembalian" class="form-control" required><br>
            </div>
            <div class="container">
                <input type="submit" value="Tambah" class="btn btn-primary">
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

<?php
$conn->close();
?>