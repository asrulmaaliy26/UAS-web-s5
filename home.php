<?php
// Buat koneksi ke database
require_once 'database/crud.php';

$database = new crud();

session_start();
$id_akun = $_SESSION['id_akun'];

if (!isset($id_akun))
    header('location:pasien_lama.php');


$query = "SELECT akun.id_pasien, akun.username, pasien.nama_pasien FROM akun JOIN pasien ON akun.id_pasien = Pasien.id_pasien WHERE id_akun = '$id_akun'";
$resultAkun = $database->selectQuery($query);

if (!empty($resultAkun))
    $fetch_user = $resultAkun[0];

if (isset($_GET['logout'])) {
    // Menghapus data session
    session_destroy();

    // Mengarahkan pengguna ke halaman login atau halaman lain yang sesuai
    header('location: index.php');
    exit();
}

$id_pasien = $fetch_user['id_pasien'];


// Menambahkan data ke tabel Antrian
if (isset($_POST['tambah_antrian'])) {
    $id__poli = $_POST['id_poli'];
    $nomor__antrian = 0; // Anda perlu mengganti ini dengan logika penghitungan nomor antrian
    $pesan = $_POST['pesan'];

    $dataAntrian = [
        'id_pasien' => $id_pasien,
        'id_jadwal' => $id__poli,
        'nomor_antrian' => $nomor__antrian,
        'pesan' => $pesan
    ];

    $database->insertDataToDatabase('antrian', $dataAntrian);
}

?>



<!DOCTYPE html>
<html>

<head>
    <title>Home</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Konten tab */
        .tabcontent {
            display: none;
            padding: 20px;
            background-color: white;
        }

        .tablinks {
            cursor: pointer;
        }

        .tabb {
            overflow: hidden;
            background-color: #f1f1f1;
        }

        /* Gaya untuk tombol tab */
        .tabb button {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 10px 20px;
            transition: 0.3s;
        }

        /* Gaya saat tombol tab aktif */
        .tabb button.active {
            background-color: #ccc;
        }
    </style>

</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark navbar-custom">
        <a class="navbar-brand " href="#">Home</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <!-- <li class="nav-item">
                    <a class="nav-link tablinks active" onclick="openTab(event, 'Tab1')">Jadwal</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link tablinks" onclick="openTab(event, 'Tab2')">Janji</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link tablinks" onclick="openTab(event, 'Tab3')">Dokter</a>
                </li> -->
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="btn btn-primary" href="?logout">Logout</a>
                </li>
            </ul>
        </div>
    </nav>



    <!-- Daftar Poli -->
    <?php

    ?>

    <div class="text-center container border border-5 border-success" style="font-family:optima; font-weight:bold;">
        <h3 style=" font-weight:bold;"> ID Pasien : <span>
                <?php echo $fetch_user['id_pasien']; ?>
            </span> </h3>
        <h3> nama : <span>
                <?php echo $fetch_user['nama_pasien']; ?>
            </span> </h3>
    </div>
    <div class="container">
        <form action="" method="POST">
            <h2>Form Tambah Antrian</h2>
            <div class="form-group">
                <label for="id_poli">ID Poli:</label>
                <select class="form-control" id="id_poli" name="id_poli" required>
                    <option value="">Pilih Poli</option>
                    <?php
                    // Menggunakan metode readData dari objek $crudObject
                    $selectColumns = ['id_poli', 'nama_poli']; // Kolom yang ingin diambil
                    $tableName = 'poli'; // Nama tabel
                    
                    $poliData = $database->readData($tableName, $selectColumns);

                    // Menampilkan hasil dalam bentuk opsi dropdown
                    if (!empty($poliData)) {
                        foreach ($poliData as $row_poli) {
                            echo "<option value='" . $row_poli['id_poli'] . "'>" . $row_poli['nama_poli'] . "</option>";
                        }
                    }

                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="tgl_antrian">Tanggal Antrian:</label>
                <input type="date" class="form-control" id="tgl_antrian" name="tgl_antrian" required>
            </div>
            <div class="form-group">
                <label for="pesan">Pesan :</label>
                <textarea type="date" class="form-control" id="pesan" name="pesan" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" name="tambah_antrian">Tambah Antrian</button>
            <a href="antrian.php" class="btn btn-primary" target="blank">Lihat Antrian</a>
        </form>
    </div>
    <div class="tabb m-4">
        <button class="tablinks tabb active" onclick="openTab(event, 'Tab1')">Data Jadwal</button>
        <button class="tablinks tabb" onclick="openTab(event, 'Tab3')">Dokter</button>
        <button class="tablinks tabb" onclick="openTab(event, 'Tab4')">Dokumenter</button>
    </div>
    <div id="Tab1" class="tabcontent">
        <?php include "jadwal.php"; ?>
    </div>

    <div id="Tab3" class="tabcontent">
        <h3>Dokter</h3>
        <?php
        $resultDokter = $database->readData('dokter');
        if ($resultDokter > 0) {
            ?>
            <table class="mt-3 table">
                <tr>
                    <th>ID Dokter</th>
                    <th>Nama Dokter</th>
                    <th>Spesialisasi</th>
                    <th>Alamat</th>
                    <th>Nomor Telepeon</th>
                </tr>
                <?php
                foreach ($resultDokter as $row) {
                    ?>
                    <tr>
                        <td>
                            <?php echo $row['id_dokter'] ?>
                        </td>
                        <td>
                            <?php echo $row['nama_dokter'] ?>
                        </td>
                        <td>
                            <?php echo $row['spesialisasi'] ?>
                        </td>
                        <td>
                            <?php echo $row['alamat_dokter'] ?>
                        </td>
                        <td>
                            <?php echo $row['nomor_telepon_dokter'] ?>
                        </td>
                    </tr>
                    <?php
                } ?>
            </table>
            <?php
        } else {
            echo "<div class='container'>Tidak ada data Dokter.</div>";
        }
        ?>
    </div>
    <div id="Tab4" class="tabcontent">
        <?php
        $resultFiles = $database->readData('files');
        if ($resultFiles > 0) {
            echo '<div class="row">';
            foreach ($resultFiles as $row) {
                echo '<div class="col-md-4 mb-4">';
                echo '<div class="card">';
                // Tampilkan gambar
                echo '<img src="data:image/jpeg;base64,' . base64_encode($row['image']) . '" class="card-img-top" alt="File Image">';
                echo '<div class="card-body">';
                // Tampilkan deskripsi
                echo '<p class="card-text">' . $row['deskripsi'] . '</p>';
                echo '</div>';
                echo '</div>';

                echo '</div>';
            }
            echo '</div>';
        } else {
            echo 'Tidak ada data file.';
        }
        ?>
    </div>


    <script>
        // Fungsi untuk membuka tab
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>