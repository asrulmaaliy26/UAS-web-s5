<?php
// Buat koneksi ke database
require_once 'database/crud.php';

$database = new crud();

session_start();
$id_admin = $_SESSION['id_admin'];
// $id_admin = 1;

if (!isset($id_admin)) {
    header('location:pasien_lama.php');
    exit();
}

if (isset($_GET['logout'])) {
    // Menghapus data session
    session_destroy();

    // Mengarahkan pengguna ke halaman login atau halaman lain yang sesuai
    header('location: index.php');
    exit();
}

// Memproses form update, delete, dan add
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_files'])) {
        if (isset($_POST['selectedRows'])) {
            $selectedRows = $_POST['selectedRows'];
            foreach ($selectedRows as $id) {
                // Hapus data dari database
                $database->deleteData('files', $id);
            }
        }
    } elseif (isset($_POST['update_files'])) {
        if (isset($_POST['selectedRows'])) {
            $selectedRows = $_POST['selectedRows'];
            foreach ($selectedRows as $id) {
                // Ambil data dari form
                $deskripsi_files = $_POST['edit_deskripsi_files'][$id];
                $tanggal_files = $_POST['edit_tanggal_files'][$id];
                // Update data di database
                $data = [
                    'deskripsi' => $deskripsi_files,
                    'tanggal' => $tanggal_files
                ];
                $database->updateData($id, $deskripsi_files, $tanggal_files);
            }
        }
    } elseif (isset($_POST['tambah_files'])) {
        $images = $_FILES['f1']['tmp_name'];
        $deskripsis = $_POST['deskripsi'];
        $tanggals = $_POST['tanggal'];
        $totalFiles = count($images);

        for ($i = 0; $i < $totalFiles; $i++) {
            $image = addslashes(file_get_contents($images[$i]));
            $deskripsi = $deskripsis[$i];
            $tanggal = $tanggals[$i];

            $data = [
                'image' => $image,
                'deskripsi' => $deskripsi,
                'tanggal' => $tanggal
            ];
            $database->insertData('files', $data);
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Data Antrian</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">

        <?php

        $condition = array(
            'id_admin' => $id_admin
        );

        $result = $database->readData('admin', '*', $condition);
        foreach ($result as $row) {
            ?>
            <div class="d-flex justify-content-between">
                <div>
                    <h3> Admin : <span>
                            <?php echo $row['username']; ?>
                        </span> </h3>
                    <h3> Nama : <span>
                            <?php echo $row['nama_admin']; ?>
                        </span> </h3>
                </div>
                <div>
                    <a class="btn btn-success m-3" href="admin.php">refresh</a>
                    <a class="btn btn-primary m-3" href="?logout">Logout</a>
                </div>
            </div>
        <?php } ?>

        <div class="tab">
            <button class="tablinks active" onclick="openTab(event, 'Tab1')">Antrian</button>
            <button class="tablinks" onclick="openTab(event, 'Tab2')">Pasien</button>
            <button class="tablinks" onclick="openTab(event, 'Tab3')">Janji</button>
            <button class="tablinks" onclick="openTab(event, 'Tab4')">Akun Pasien</button>
            <button class="tablinks" onclick="openTab(event, 'Tab5')">Dokter</button>
            <button class="tablinks" onclick="openTab(event, 'Tab6')">Poli</button>
            <button class="tablinks" onclick="openTab(event, 'Tab7')">Jadwal</button>
            <button class="tablinks" onclick="openTab(event, 'Tab8')">Admin</button>
            <button class="tablinks" onclick="openTab(event, 'Tab9')">Documenter</button>
        </div>

        <div id="Tab1" class="tabcontent">
            <?php include "admin/adminAntrian.php"; ?>
        </div>

        <div id="Tab2" class="tabcontent">
            <?php include "admin/adminPasien.php"; ?>
        </div>

        <div id="Tab3" class="tabcontent">
            <?php include "admin/adminJanji.php"; ?>
        </div>

        <div id="Tab4" class="tabcontent">
            <?php include "admin/adminAkun.php"; ?>
        </div>

        <div id="Tab5" class="tabcontent">
            <?php include "admin/adminDokter.php"; ?>
        </div>

        <div id="Tab6" class="tabcontent">
            <?php include "admin/adminPoli.php"; ?>
        </div>

        <div id="Tab7" class="tabcontent">
            <?php include "admin/adminJadwal.php"; ?>
        </div>

        <div id="Tab8" class="tabcontent">
            <?php include "admin/adminAdmin.php"; ?>
        </div>

        <div id="Tab9" class="tabcontent">
            <h1>Documenter</h1>
            <div class="container mt-3">
                <div class="section mb-3">
                    <form name="form1" action="" method="post" enctype="multipart/form-data">
                        <table class="table" id="data-table">
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <label>Select File</label>
                                </div>
                                <div>
                                    <input class="btn btn-secondary" type="file" name="f1[]">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div><label for="">Deskripsi</label></div>
                                <div><textarea name="deskripsi[]"></textarea></div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div><label for="">Tanggal</label></div>
                                <div><input type="date" name="tanggal[]"></div>
                            </div>
                            <tr>
                                <td><button class="btn btn-info" type="button" onclick="addForm()">Tambah Data</button>
                                </td>
                            </tr>
                            <tr>
                                <td><input class="btn btn-primary" type="submit" name="tambah_files" value="upload"
                                        required>
                                </td>
                            </tr>
                        </table>
                        <table class='table'>
                            <tr>
                                <th>ID Gambar</th>
                                <th>Gambar</th>
                                <th>Deskripsi</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                            <?php
                            $result2 = $database->readData('files', '*');
                            foreach ($result2 as $row) {
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $row['id'] ?>
                                    </td>
                                    <td>
                                        <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image']) ?>"
                                            class="card-img-top" alt="File Image">
                                    </td>
                                    <td><input type="text" name="edit_deskripsi_files[<?php echo $row['id']; ?>]"
                                            value="<?php echo $row['deskripsi'] ?>"></td>
                                    <td><input type="text" name="edit_tanggal_files[<?php echo $row['id']; ?>]"
                                            value="<?php echo $row['tanggal'] ?>"></td>
                                    <td><input type="checkbox" name="selectedRows[]" value="<?php echo $row['id']; ?>"></td>
                                </tr>
                            <?php
                            }
                            ?>

                        </table>
                        <div>
                            <button type="submit" class="btn btn-primary" name="update_files">Update</button>
                            <button type="submit" class="btn btn-danger" name="delete_files">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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

        // Fungsi untuk menambahkan form
        function addForm() {
            var table = document.getElementById("data-table");
            var row = table.insertRow(-1);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);

            cell1.innerHTML =
                "<div class='d-flex justify-content-between mb-3'><div><label>Select File</label></div><div><input class='btn btn-secondary' type='file' name='f1[]'></div></div>";
            cell2.innerHTML =
                "<div class='d-flex justify-content-between'><div><label for=''>Deskripsi</label></div><div><textarea name='deskripsi[]'></textarea></div></div>";
            cell3.innerHTML =
                "<div class='d-flex justify-content-between'><div><label for=''>Tanggal</label></div><div><input type='date' name='tanggal[]'></div></div>";
        }
    </script>
</body>

</html>