<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
    <style>
        body {
            background-image: url(images/library.jpg);
            background-size: cover;
        }

        tbody tr {
            color: white;
        }
    </style>
</head>

<body id="bg-login">
    <div class="box-login">
        <h2>Login Perpustakaan</h2>
        <form action="" method="POST">
            <input type="text" name="user" placeholder="Username" class="input-control" autofocus required>
            <input type="password" name="pass" placeholder="Password" class="input-control" required>
            <input type="submit" name="submit" value="Login" class="btn2" required>
        </form>
        <?php
        if (isset($_POST['submit'])) {
            session_start();
            include 'koneksi.php';

            $user = mysqli_real_escape_string($conn, $_POST['user']);
            $pass = mysqli_real_escape_string($conn, $_POST['pass']);

            $cek = mysqli_query($conn, "SELECT * FROM tb_admin WHERE username = '" . $user . "' AND password = '" . MD5($pass) . "'");
            if (mysqli_num_rows($cek) > 0) {
                $d = mysqli_fetch_object($cek);
                $_SESSION['status_login'] = true;
                $_SESSION['a_global'] = $d;
                $_SESSION['id'] = $d->admin_id;
                echo '<script>window.location="main.php"</script>';
            } else {
                echo '<script>alert("Username atau password Anda salah!")</script>';
            }
        }
        ?>
    </div>
</body>

</html>