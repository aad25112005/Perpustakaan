<?php
session_start();
require_once 'koneksi.php';
if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="index.php"</script>';
}

// Memeriksa apakah form tambah anggota sudah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idAnggota = $_POST["idAnggota"];
    $nama = $_POST["nama"];
    $kelas = $_POST["kelas"];
    $telepon = $_POST["telepon"];
    // Validasi format ID Anggota (hanya angka diizinkan)
    if (!preg_match("/^[0-9]+$/", $idAnggota)) {
        echo '<script>alert("Format NISN tidak valid. Hanya angka diizinkan.")</script>';
        echo '<script>window.location="tambah_siswa.php?act=anggota"</script>';
        exit();
    }
    // Validasi format telepon (hanya angka diizinkan)
    if (!preg_match("/^[0-9]+$/", $telepon)) {
        echo '<script>alert("Format HP tidak valid. Hanya angka diizinkan.")</script>';
        echo '<script>window.location="tambah_siswa.php?act=anggota"</script>';
        exit();
    }
    // Cek apakah ID siswa sudah ada dalam database
    $sql_check = "SELECT idAnggota FROM anggota WHERE idAnggota= '$idAnggota'";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        echo '<script>alert("NISN sudah ada dalam database. Mohon gunakan NISN lain.")</script>';
        echo '<script>window.location="tambah_siswa.php?act=anggota"</script>';
        exit();
    }
    // Menambahkan anggota ke database
    $sql = "INSERT INTO anggota (idAnggota, nama, kelas, telepon) VALUES ('$idAnggota', '$nama', '$kelas', '$telepon')";

    if ($conn->query($sql) === TRUE) {
        header("Location: daftar_siswa.php");
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
    <title>Tambah Siswa</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>

<body>
    <div class="container">
        <h2 align="center">Tambah Siswa</h2>
        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" onsubmit="return validateInput()">
            <div class="form-group">
                <label for="idAnggota">NISN:</label>
                <input type="text" name="idAnggota" id="idAnggota" maxlength="10" placeholder="Masukkan NISN" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="nama">Nama Siswa:</label>
                <input type="text" name="nama" id="nama" placeholder="Masukkan Nama Siswa" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="kelas">Kelas:</label>
                <input type="text" name="kelas" id="kelas" placeholder="Masukkan Kelas" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="telepon">HP:</label>
                <input type="text" name="telepon" id="telepon" placeholder="Masukkan Nomor HP" class="form-control" required>
            </div>
            <input type="submit" value="Tambah" class="btn btn-primary">
            <a class="btn btn-primary" href="daftar_siswa.php">Kembali</a>
        </form>
    </div>
    <script>
        function validateInput() {
            var idAnggota = document.getElementById("idAnggota").value;

            if (!/^\d{10}$/.test(idAnggota)) {
                alert("NISN harus terdiri dari 10 angka.");
                return false;
            }

            return true;
        }
    </script>
</body>

</html>