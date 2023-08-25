<?php
// Buat koneksi ke database
require_once 'database/crud.php';

$database = new crud();

if (isset($_POST['mengantri'])) {
    $nama_pasien = $_POST['nama_pasien'] . " (baru)";
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $no_telepon = $_POST['no_telepon'];
    $pesan = 'daftar';

    // Membuat data pasien baru
    $pasienData = array(
        'nama_pasien' => $nama_pasien,
        'jenis_kelamin' => $jenis_kelamin,
        'tanggal_lahir' => $tanggal_lahir,
        'alamat' => $alamat,
        'no_telepon' => $no_telepon
    );

    // Menggunakan metode insertData dari objek $database
    $tableName = 'pasien';
    $insertResult = $database->insertData($tableName, $pasienData);

    if ($insertResult) {
        // Ambil ID pasien yang baru saja ditambahkan
        $id_pasien = $insertResult;

        // Ambil nomor antrian terakhir di poli umum pada tanggal saat ini
        $select_query = "SELECT MAX(nomor_antrian) AS max_nomor_antrian FROM antrian WHERE id_jadwal = '1'";
        $antrianData = $database->selectQuery($select_query);

        if (!empty($antrianData)) {
            $nomor_antrian = $antrianData[0]['max_nomor_antrian'] + 1;
        } else {
            $nomor_antrian = 1;
        }

        // Membuat data antrian baru
        $antrianData = array(
            'id_pasien' => $id_pasien,
            'id_jadwal' => 1,
            'nomor_antrian' => $nomor_antrian,
            'pesan' => $pesan
        );

        // Menggunakan metode insertData dari objek $database
        $tableName = 'antrian';
        $insertAntrianResult = $database->insertData($tableName, $antrianData);

        if ($insertAntrianResult) {
            echo '<script> alert("Data Antrian berhasil ditambahkan.");</script>';
        } else {
            echo '<script> alert("Error: Gagal menambahkan data antrian.");</script>';
        }
    } else {
        echo '<script> alert("Error: Gagal menambahkan data pasien.");</script>';
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Form Pendaftaran Pasien</title>
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
</head>

<body>
    <section id="login">
        <div class="container-fluid">
            <div class="row align-items-center flex-row-reverse">
                <div class="col-12 col-lg-6 px-lg-5 d-flex flex-column justify-content-between form-wrapper py-5">
                    <form action="" method="POST">
                        <h1>Pendaftaran Pasien</h1>
                        <p class="text-muted">Pembuatan nomor pasien baru termasuk akun situs jaringan</p>

                        <div class="my-5">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="nama_pasien" name="nama_pasien"
                                    placeholder="Nama" required>
                                <label for="nama_pasien">Nama Pasien:</label>
                            </div>
                            <div class="form-floating mb-3">
                                <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                                <label for="jenis_kelamin">Jenis Kelamin:</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir"
                                    required>
                                <label for="tanggal_lahir">Tanggal Lahir:</label>
                            </div>
                            <div class="form-floating mb-3">
                                <textarea class="form-control" id="alamat" name="alamat" placeholder="Alamat"
                                    required></textarea>
                                <label for="alamat">Alamat:</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="no_telepon" name="no_telepon"
                                    placeholder="Nomor Telepon" required>
                                <label for="no_telepon">No. Telepon:</label>
                            </div>
                            <button type="submit" class="btn btn-dark btn-lg" name="mengantri">Mengantri</button>
                            <a href="antrian.php" class="btn btn-dark btn-lg">Lihat Antrian</a>
                        </div>
                    </form>
                    <p class="text-center">Sudah memiliki nomor pasien? <a href="pasien_lama.php">Login</a></p>
                </div>
                <div class="d-none d-lg-flex col-lg-6 side-section p-5 text-white flex-column justify-content-between">
                    <h1>ngobat.com</h1>
                    <h4>Take Care.</h4>
                    <div class="source">
                        <p>Asrul Maaliy</p>
                        <p>Direktur Rumah Sakit</p>
                        <?php
                        $select_query = "SELECT MAX(nomor_antrian) AS max_nomor_antrian FROM antrian WHERE id_jadwal = '1'";
                        $antrianData = $database->selectQuery($select_query);
                        
                        if (!empty($antrianData)) {
                            $nomor_antrian = $antrianData[0]['max_nomor_antrian'] + 1;
                        } else {
                            $nomor_antrian = 1;
                        }
                        
                        // Menampilkan hasil
                        echo "<br>";
                        echo "max_nomor_antrian: " . $antrianData[0]['max_nomor_antrian'] . "<br>";
                        echo "Nomor Antrian Berikutnya: " . $nomor_antrian;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tambahkan script JS Bootstrap -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>