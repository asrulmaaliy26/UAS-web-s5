<?php
require_once 'database/crud.php';

$crudObject = new crud(); // Menginstansiasi objek CRUD

session_start();
// Cek apakah tombol login sudah ditekan
if (isset($_POST['login'])) {
    // Simpan data inputan dari form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Lakukan validasi login
    $valid_login = false;

    $condition = [
        'username' => $username,
        'password' => $password
    ];

    // Menggunakan objek CRUD untuk mendapatkan data dari tabel 'akun'
    $getDataAkun = $crudObject->readData('akun', '*', $condition);
    $getDataAdmin = $crudObject->readData('admin', '*', $condition);

    if (!empty($getDataAkun)) {
        $row = $getDataAkun[0];
        $_SESSION['id_akun'] = $row['id_akun'];
        header("Location: home.php");
        exit;
    } elseif (!empty($getDataAdmin)) {
        $row = $getDataAdmin[0];
        $_SESSION['id_admin'] = $row['id_admin'];
        header("Location: admin.php");
        exit;
    } else {
        echo '<script>alert("Error ges, enek seng salah mestine. Jal lebok no meneh!");</script>';
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Form Kunjungan Pasien Lama</title>
    <!-- Tambahkan link CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400;1,600;1,700&display=swap"
        rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        #login .card,
        #login .btn {
            border-radius: 15px;
        }

        #login p {
            margin: 0;
        }

        #login .row,
        .form-wrapper,
        .side-section {
            min-height: 100vh;
        }

        #login .side-section {
            background-image: url(./assets/img/login.jpeg);
            background-size: cover;
            background-position: center;
            min-height: 100vh;
        }

        #login .side-section h4 {
            line-height: var(--bs-body-line-height);
        }

        #login input {
            border-radius: 0;
            border: none;
            border-bottom: 1px solid var(--bs-dark);
        }

        #login input:focus {
            box-shadow: unset;
        }
    </style>
    <script>
        function showPopup() {
            alert("Error ges, enek seng salah mestine. Jal lebok no meneh!");
        }
    </script>
</head>

<body>
    <section id="login">
        <div class="container-fluid">
            <div class="row align-items-center flex-row-reverse">
                <div class="col-12 col-lg-6 px-lg-5 d-flex flex-column justify-content-between form-wrapper py-5">
                    <form action="" method="POST">
                        <h1>Form Kunjungan Pasien Lama</h1>
                        <p class="text-muted">Konfirmasi data pasien lama</p>

                        <div class="my-5">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="username" name="username"
                                    placeholder="Nomor Pasien" required>
                                <label for="username">Nomor Pasien</label>
                            </div>
                            <div class="form-floating">
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Kata Sandi" required>
                                <label for="password">Kata Sandi</label>
                            </div>
                            <div class="d-grid gap-2 m-3">
                                <button class="btn btn-dark btn-lg" name="login">Log In</button>
                                <a href="antrian.php" class="btn btn-dark btn-lg">Lihat Antrian</a>
                                <a href="index.php" class="btn btn-outline-dark btn-lg">Kembali ke Website</a>
                            </div>
                        </div>
                    </form>
                    <p class="text-center">Belum memiliki nomor pasien? <a href="pasien_baru.php">Daftar</a></p>
                </div>
                <div class="d-none d-lg-flex col-lg-6 side-section p-5 text-white flex-column justify-content-between">
                    <h1>ngobat.com</h1>
                    <h4>Take Care.</h4>
                    <div class="source">
                        <p>Asrul Maaliy</p>
                        <p>Direktur Rumah Sakit</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
</body>

</html>