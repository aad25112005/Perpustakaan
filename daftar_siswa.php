<?php
session_start();
require_once 'koneksi.php';
if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="index.php"</script>';
}

// Menghapus siswa
if (isset($_GET['hapus'])) {
    $idAnggota = $_GET['hapus'];
    $sql_hapus = "DELETE FROM anggota WHERE idAnggota = $idAnggota";
    $conn->query($sql_hapus);
}

// Query untuk menghitung total jumlah siswa
$sql_count_anggota = "SELECT COUNT(*) as total FROM anggota";
$result_count_anggota = $conn->query($sql_count_anggota);
$total_anggota = 0;

if ($result_count_anggota->num_rows > 0) {
    $row = $result_count_anggota->fetch_assoc();
    $total_anggota = $row['total'];
}
?>

<!DOCTYPE html>
<html>

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
    <nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href=""><svg xmlns="http://www.w3.org/2000/svg" width="50" height="40" fill="currentColor" class="bi bi-book" viewBox="0 0 16 16">
                    <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z" />
                </svg></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="main.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="daftar_siswa.php">Siswa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="daftar_buku.php">Buku</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="daftar_peminjaman.php">Peminjaman</a>
                    </li>
                </ul>
                <form class="d-flex" role="search">
                    <input class="form-control me-5" type="search" placeholder="Search" aria-label="Search" name="keyword" id="keyword" onkeyup="cariSiswa()">
                </form>
                <ul class="navbar-nav ml-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="btn btn-secondary" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <!-- Data Pendaftaran -->
        <div class="card mt-3 mb-3">
            <div class="card-header">
                <h3>Daftar Siswa (Total: <?php echo $total_anggota; ?>)</h3>
            </div>
            <a class="btn btn-primary" href="tambah_siswa.php">Tambah</a>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table table-bordered border-dark" id="tableData">
                        <tr class="border-dark table-info">
                            <th>No</th>
                            <th>NISN</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>HP</th>
                            <th>Aksi</th>
                        </tr>
                        <?php
                        require_once "koneksi.php";

                        if (isset($_GET['keyword'])) {
                            $sql = "SELECT * FROM anggota WHERE
                            idAnggota LIKE '%$_GET[keyword]%' OR
                            nama LIKE '%$_GET[keyword]%' OR
                            telepon LIKE '%$_GET[keyword]%' OR
                            kelas LIKE '%$_GET[keyword]%' ";
                        } else {
                            $sql = "SELECT * FROM anggota";
                        }
                        // $sql = "SELECT * FROM anggota";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            $no = 0;
                            while ($row = $result->fetch_assoc()) {
                                $no++;
                                echo "<tr>";
                                echo "<td>" . $no . "</td>";
                                echo "<td>" . $row["idAnggota"] . "</td>";
                                echo "<td>" . $row["nama"] . "</td>";
                                echo "<td>" . $row["kelas"] . "</td>";
                                echo "<td>" . $row["telepon"] . "</td>";
                                echo "<td>";
                                echo "<a class='btn btn-success bi bi-pencil-fill' href='edit_siswa.php?idAnggota=" . $row["idAnggota"] . "'></a> ";
                                echo "<a class='btn btn-danger bi bi-trash3-fill' href='daftar_siswa.php?hapus=" . $row["idAnggota"] . "' onclick='return confirm(\"Apakah Anda yakin ingin menghapus siswa ini?\")'></a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>Tidak ada siswa.</td></tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        function cariSiswa() {
            var input = document.getElementById("keyword").value;
            var table = document.getElementById("tableData");
            var baris = table.getElementsByTagName("tr");

            // Pertahankan baris pertama (header tabel) tetap terlihat
            var headerRow = baris[0];
            headerRow.style.display = "table-row";

            for (i = 1; i < baris.length; i++) { // Mulai dari 1 untuk menghindari header
                var dataCells = baris[i].getElementsByTagName("td");
                var matchFound = false;

                for (j = 0; j < dataCells.length; j++) {
                    var cellData = dataCells[j].textContent || dataCells[j].innerText;

                    if (cellData.toLowerCase().indexOf(input.toLowerCase()) > -1) {
                        matchFound = true;
                        break;
                    }
                }

                if (matchFound) {
                    baris[i].style.display = "table-row"; // Menampilkan baris yang cocok
                } else {
                    baris[i].style.display = "none"; // Menyembunyikan baris yang tidak cocok
                }
            }
        }
    </script>
</body>
<div class="card text-center" data-bs-theme="dark">
    <div class="card-footer text-body-secondary">
        &copy; Copyright <?= date('Y') ?> <b> Athariq Ahmad Day </b>
    </div>
</div>

</html>
<?php
$conn->close();
?>