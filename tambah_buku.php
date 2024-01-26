<?php
session_start();
require_once 'koneksi.php';
if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="index.php"</script>';
}

// Memeriksa apakah form tambah buku sudah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $judul = $_POST["judul"];
    $pengarang = $_POST["pengarang"];
    $kategori = $_POST["kategori"];
    $status = $_POST["status"];
    // Validasi format ID buku (hanya angka diizinkan)
    if (!preg_match("/^[0-9]+$/", $id)) {
        echo '<script>alert("Format Kode buku tidak valid. Hanya angka diizinkan.")</script>';
        echo '<script>window.location="tambah_buku.php?act=buku"</script>';
        exit();
    }
    // Cek apakah ID buku sudah ada dalam database
    $sql_check = "SELECT id FROM buku WHERE id= '$id'";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        echo '<script>alert("Kode Buku sudah ada dalam database. Mohon gunakan Kode buku lain.")</script>';
        echo '<script>window.location="tambah_buku.php?act=buku"</script>';
        exit();
    }
    // Menambahkan buku ke database
    // Menambahkan buku ke database dengan status "Tersedia"
    $sql = "INSERT INTO buku (id, judul, pengarang, kategori, status) VALUES ('$id','$judul', '$pengarang', '$kategori', 'Tersedia')";

    if ($conn->query($sql) === TRUE) {
        header("Location: daftar_buku.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

    <title>Tambah Buku</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>

<body>
    <div class="container">
        <h2 align="center">Tambah Buku</h2>
        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="id">Kode Buku:</label>
                <input type="text" name="id" id="id" placeholder="Masukkan Kode Buku" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="judul">Judul:</label>
                <input type="text" name="judul" id="judul" placeholder="Masukkan Judul Buku" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="pengarang">Pengarang:</label>
                <input type="text" name="pengarang" id="pengarang" placeholder="Masukkan Pengarang" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="kategori">Kategori:</label>
                <select name="kategori" id="kategori" class="form-control">
                    <option value="Novel">Novel</option>
                    <option value="Biografi">Biografi</option>
                    <option value="Pembelajaran">Pembelajaran</option>
                    <option value="Dongeng">Dongeng</option>
                    <option value="Majalah">Majalah</option>
                    <option value="Kamus">Kamus</option>
                    <option value="Ensiklopedia">Ensiklopedia</option>
                    <option value="Naskah">Naskah</option>
                    <option value="Manga">Manga</option>
                </select>
            </div>
            <!-- submit -->
            <input type="submit" value="Tambah" class="btn btn-primary">
            <a class="btn btn-primary" href="daftar_buku.php">Kembali</a> <!-- Tambahkan tautan ini -->
        </form>
    </div>
    <script>
        function validateForm() {
            var id = document.getElementById('id').value;
            var judul = document.getElementById('judul').value;
            var pengarang = document.getElementById('pengarang').value;
            var kategori = document.getElementById('kategori').value;

            if (id.trim() === '' || judul.trim() === '' || pengarang.trim() === '' || kategori.trim() === '') {
                alert('Mohon isi semua field sebelum menyimpan data.');
                return false;
            }

            return true;
        }
    </script>
</body>

</html>