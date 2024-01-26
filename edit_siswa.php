<?php
session_start();
require_once 'koneksi.php';
if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="index.php"</script>';
}

// Memeriksa apakah parameter ID anggota diberikan
if (isset($_GET["idAnggota"])) {
    $idAnggota = $_GET["idAnggota"];

    // Mengambil data anggota dari database
    $sql = "SELECT * FROM anggota WHERE idAnggota = $idAnggota";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    // Memeriksa apakah form edit anggota sudah disubmit
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nama = $_POST["nama"];
        $kelas = $_POST["kelas"];
        $telepon = $_POST["telepon"];

        // Validasi format telepon (hanya angka diizinkan)
        if (!preg_match("/^[0-9]+$/", $telepon)) {
            echo '<script>alert("Format HP tidak valid. Hanya angka diizinkan.")</script>';
            echo '<script>window.location="daftar_siswa.php?act=anggota"</script>';
            exit();
        }
        // Mengupdate data anggota di database
        $sql_update = "UPDATE anggota SET nama = '$nama', kelas = '$kelas', telepon = '$telepon' WHERE idAnggota = $idAnggota";

        if ($conn->query($sql_update) === TRUE) {
            header("Location: daftar_siswa.php");
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }

    $conn->close();
} else {
    echo "ID anggota tidak diberikan.";
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
    <title>Edit Siswa</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>

<body>
    <div class="container">
        <h2 align="center">Edit Siswa</h2>
        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"] . "?idAnggota=" . $idAnggota; ?>">
            <div class="form-group">
                <label for="nama">Nama Siswa:</label>
                <input type="text" name="nama" id="nama" placeholder="Masukkan Nama Siswa" value="<?php echo $row["nama"]; ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="kelas">Kelas:</label>
                <input type="text" name="kelas" id="kelas" placeholder="Masukkan Kelas" value="<?php echo $row["kelas"]; ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="telepon">HP:</label>
                <input type="text" name="telepon" id="telepon" placeholder="Masukkan No Hp" value="<?php echo $row["telepon"]; ?>" class="form-control" required>
            </div>
            <input type="submit" value="Simpan" class="btn btn-primary">
            <a class="btn btn-primary" href="daftar_siswa.php">Kembali</a> <!-- Tambahkan tautan ini -->
        </form>
    </div>
</body>

</html>