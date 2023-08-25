<?php
// Koneksi ke database
include "konek.php";
if (!$conn) {
    die('Koneksi gagal: ' . mysqli_connect_error());
}

// Fungsi untuk mendapatkan data dari database
function getDatajadwalFromDatabase()
{
    global $conn;
    $sql = "SELECT * FROM jadwal";
    $result = mysqli_query($conn, $sql);
    return $result;
}

// Fungsi untuk menghapus data dari database
function deleteDatajadwalFromDatabase($id_jadwal)
{
    global $conn;
    $sql = "DELETE FROM jadwal WHERE id_jadwal = $id_jadwal";
    mysqli_query($conn, $sql);
    // Mereset auto-increment value
    resetAutoIncrementjadwal();
}

// Fungsi untuk mereset auto-increment value pada kolom ID
function resetAutoIncrementjadwal()
{
    global $conn;
    $sql = "SELECT MAX(id_jadwal) as max_id_jadwal FROM jadwal";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $maxIdjadwal = $row['max_id_jadwal'];
    $sql = "ALTER TABLE jadwal AUTO_INCREMENT = $maxIdjadwal";
    mysqli_query($conn, $sql);
}

// Fungsi untuk mengupdate data di database
function updateDatajadwalInDatabase($id_jadwal, $id_poli, $id_dokter, $hari, $jam_mulai, $jam_selesai)
{
    global $conn;
    $sql = "UPDATE jadwal SET id_poli = '$id_poli', id_dokter = '$id_dokter', hari = '$hari', jam_mulai = '$jam_mulai', jam_selesai = '$jam_selesai' WHERE id_jadwal = $id_jadwal";
    mysqli_query($conn, $sql);
}

// Fungsi untuk menambahkan data ke database
function addDatajadwalToDatabase($id_poli, $id_dokter, $hari, $jam_mulai, $jam_selesai)
{
    global $conn;
    $sql = "INSERT INTO jadwal (id_poli, id_dokter, hari, jam_mulai, jam_selesai) VALUES ('$id_poli', '$id_dokter', '$hari', '$jam_mulai', '$jam_selesai')";
    mysqli_query($conn, $sql);
}

// Mendapatkan data dari database
$data = getDatajadwalFromDatabase();

// Memproses form update, delete, dan add
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_jadwal'])) {
        if (isset($_POST['selectedRows'])) {
            $selectedRows = $_POST['selectedRows'];
            foreach ($selectedRows as $id_jadwal) {
                // Hapus data dari database
                deleteDatajadwalFromDatabase($id_jadwal);
            }
        }
    } elseif (isset($_POST['update_jadwal'])) {
        if (isset($_POST['selectedRows'])) {
            $selectedRows = $_POST['selectedRows'];
            foreach ($selectedRows as $id_jadwal) {
                // Ambil data dari form
                $id_poli = $_POST['edit_id_poli'][$id_jadwal];
                $id_dokter = $_POST['edit_id_dokter'][$id_jadwal];
                $hari = $_POST['edit_hari'][$id_jadwal];
                $jam_mulai = $_POST['edit_jam_mulai'][$id_jadwal];
                $jam_selesai = $_POST['edit_jam_selesai'][$id_jadwal];
                // Update data di database
                updateDatajadwalInDatabase($id_jadwal, $id_poli, $id_dokter, $hari, $jam_mulai, $jam_selesai);
            }
        }
    } elseif (isset($_POST['tambah_jadwal'])) {
        $id_poli = $_POST['new_id_poli'];
        $id_dokter = $_POST['new_id_dokter'];
        $hari = $_POST['new_hari'];
        $jam_mulai = $_POST['new_jam_mulai'];
        $jam_selesai = $_POST['new_jam_selesai'];
        // Tambahkan data ke database
        addDatajadwalToDatabase($id_poli, $id_dokter, $hari, $jam_mulai, $jam_selesai);
    }
}
?>

<h2>Tambah Data Jadwal</h2>
<form action="" method="POST">
    <div class="">
        <div class='form-group'>
            <label for='id_poli'>ID Poli:</label>
            <select class='form-control' id='id_poli' name='new_id_poli'>
                <option value=''>Pilih Poli</option>
                <?php
                $sql_poli = "SELECT * FROM Poli";
                $result_poli = $conn->query($sql_poli);
                if ($result_poli->num_rows > 0) {
                    while ($row_poli = $result_poli->fetch_assoc()) {
                        echo "<option value='" . $row_poli['id_poli'] . "'>" . $row_poli['nama_poli'] . "</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div class='form-group'>
            <label for='id_dokter'>ID Dokter:</label>
            <input type='text' class='form-control' id='id_dokter' name='new_id_dokter'>
        </div>
        <div class='form-group'>
            <label for='hari'>Hari:</label>
            <input type='text' class='form-control' id='hari' name='new_hari'>
        </div>
        <div class='form-group'>
            <label for='jam_mulai'>Jam Mulai:</label>
            <input type='text' class='form-control' id='jam_mulai' name='new_jam_mulai'>
        </div>
        <div class='form-group'>
            <label for='jam_selesai'>Jam Selesai:</label>
            <input type='text' class='form-control' id='jam_selesai' name='new_jam_selesai'>
        </div>
        <button type='submit' name='tambah_jadwal' class='btn btn-success'>Tambah</button>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>ID Jadwal</th>
                <th>ID Poli</th>
                <th>ID Dokter</th>
                <th>Hari</th>
                <th>Jam Mulai</th>
                <th>Jam Selesai</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($data)): ?>
                <tr>
                    <td>
                        <?php echo $row['id_jadwal'] ?>
                    </td>
                    <td><input type="text" name="edit_id_poli[<?php echo $row['id_jadwal']; ?>]"
                            value="<?php echo $row['id_poli'] ?>">
                    </td>
                    <td><input type="text" name="edit_id_dokter[<?php echo $row['id_jadwal']; ?>]"
                            value="<?php echo $row['id_dokter'] ?>">
                    </td>
                    <td><input type="text" name="edit_hari[<?php echo $row['id_jadwal']; ?>]"
                            value="<?php echo $row['hari'] ?>">
                    </td>
                    <td><input type="text" name="edit_jam_mulai[<?php echo $row['id_jadwal']; ?>]"
                            value="<?php echo $row['jam_mulai'] ?>">
                    </td>
                    <td><input type="text" name="edit_jam_selesai[<?php echo $row['id_jadwal']; ?>]"
                            value="<?php echo $row['jam_selesai'] ?>">
                    </td>
                    <td>
                        <input type="checkbox" name="selectedRows[]" value="<?php echo $row['id_jadwal']; ?>">
                    </td>

                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <div>
        <button type="submit" class="btn btn-primary" name="update_jadwal">Update</button>
        <button type="submit" class="btn btn-danger" name="delete_jadwal">Delete</button>
    </div>
</form>