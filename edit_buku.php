<?php
session_start();
require_once 'koneksi.php';
if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="index.php"</script>';
}

// Memeriksa apakah parameter ID buku diberikan
if (isset($_GET["id"])) {
    $id = $_GET["id"];

    // Mengambil data buku dari database
    $sql = "SELECT * FROM buku WHERE id = $id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    // Memeriksa apakah form edit buku sudah disubmit
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // UPDATE `buku` SET `id` = '$id', `judul` = '$judul', `pengarang` = '$pengarang', `kategori` = '$kategori' WHERE `buku`.`id` = '0012'; 

        $judul = $_POST["judul"];
        $pengarang = $_POST["pengarang"];
        $kategori = $_POST["kategori"];
        $status = $_POST["status"];

        // Mengupdate data buku di database
        $sql_update = "UPDATE buku SET judul = '$judul', pengarang = '$pengarang', kategori = '$kategori', status = 'Tersedia' WHERE id = $id";

        if ($conn->query($sql_update) === TRUE) {
            header("Location: daftar_buku.php");
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }

    $conn->close();
} else {
    echo "ID buku tidak diberikan.";
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
    <title>Edit Buku</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>

<body>
    <div class="container">
        <h2 align="center">Edit Buku</h2>
        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"] . "?id=" . $id; ?>">
            <div class="form-group">
                <label for="judul">Judul:</label>
                <input type="text" name="judul" id="judul" value="<?php echo $row["judul"]; ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="pengarang">Pengarang:</label>
                <input type="text" name="pengarang" id="pengarang" value="<?php echo $row["pengarang"]; ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="kategori">Kategori:</label>
                <select name="kategori" id="kategori" class="form-control">
                    <?php
                    $kategori_options = array("Novel", "Biografi", "Pembelajaran", "Dongeng", "Majalah", "Kamus", "Ensiklopedia", "Naskah", "Manga");

                    // Menentukan kategori yang saat ini dipilih (dari data buku yang diedit)
                    $kategori_terpilih = $row["kategori"];

                    // Loop melalui setiap kategori
                    foreach ($kategori_options as $kategori) {
                        // Memeriksa apakah kategori saat ini sama dengan kategori yang dipilih
                        $selected_kategori = ($kategori == $kategori_terpilih) ? 'selected' : '';

                        // Tampilkan kategori sebagai opsi dengan mengatur selected jika kategori dipilih
                        echo "<option value='$kategori' $selected_kategori>$kategori</option>";
                    }
                    ?>
                </select>
            </div>
            <input type="submit" value="Simpan" class="btn btn-primary">
            <a class="btn btn-primary" href="daftar_buku.php">Kembali</a> <!-- Tambahkan tautan ini -->
        </form>
    </div>
</body>

</html>