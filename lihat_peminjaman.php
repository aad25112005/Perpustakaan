<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <title>Perpustakaan</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <?php
                        require_once "koneksi.php";

                        if (isset($_GET['idPinjam'])) {
                            $idPinjam = $_GET['idPinjam'];

                            // Query untuk mengambil detail peminjaman berdasarkan $idPinjam
                            // $sql = "SELECT * FROM peminjaman WHERE idPinjam = $idPinjam";
                            $sql = "SELECT peminjaman.*, buku.judul AS judul_buku, anggota.nama AS nama_siswa FROM peminjaman 
                            LEFT JOIN buku ON peminjaman.id = buku.id 
                            LEFT JOIN anggota ON peminjaman.idAnggota = anggota.idAnggota 
                            WHERE idPinjam = $idPinjam";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                // Tampilkan detail peminjaman
                                echo "<h1>Detail Peminjaman</h1>";
                                echo "<p>ID Pinjam: " . $row["idPinjam"] . "</p>";
                                echo "<p>NISN: " . $row["idAnggota"] . "</p>";
                                echo "<p>Nama Siswa: " . $row["nama_siswa"] . "</p>";
                                echo "<p>Kode Buku: " . $row["id"] . "</p>";
                                echo "<p>Judul Buku: " . $row["judul_buku"] . "</p>";
                                echo "<p>Tanggal Pinjam: " . $row["tanggal_peminjaman"] . "</p>";
                                echo "<p>Tanggal Kembali: " . $row["tanggal_pengembalian"] . "</p>";
                                echo "<a class='btn btn-primary' href='daftar_peminjaman.php'>Kembali</a>";
                                // Tambahkan lebih banyak kolom jika diperlukan
                            } else {
                                echo "Data peminjaman tidak ditemukan.";
                            }
                        } else {
                            echo "Permintaan tidak valid.";
                        }
                        $conn->close();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<!-- Tambahkan HTML untuk navigasi atau konten lainnya -->