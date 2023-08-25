<?php
include "konek.php";
// Memeriksa koneksi
if (!$conn) {
    die('Koneksi gagal: ' . mysqli_connect_error());
}

// Fungsi untuk mendapatkan data dari database
function getDataJanjiFromDatabase()
{
    global $conn;
    $sql = "SELECT * FROM janji";
    $result = mysqli_query($conn, $sql);
    return $result;
}

// Fungsi untuk menghapus data dari database
function deleteDataJanjiFromDatabase($id_janji)
{
    global $conn;
    $sql = "DELETE FROM janji WHERE id_janji = $id_janji";
    mysqli_query($conn, $sql);
    // Mereset auto-increment value
    resetAutoIncrementJanji();
}

// Fungsi untuk mereset auto-increment value pada kolom ID
function resetAutoIncrementjanji()
{
    global $conn;
    $sql = "SELECT MAX(id_janji) as max_id_janji FROM janji";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $maxIdjanji = $row['max_id_janji'];
    $sql = "ALTER TABLE janji AUTO_INCREMENT = $maxIdjanji";
    mysqli_query($conn, $sql);
}

// Fungsi untuk mengupdate data di database
function updateDataJanjiInDatabase($id_janji, $id_pasien, $tanggal_kunjungan, $id_poli)
{
    global $conn;
    $sql = "UPDATE janji SET id_pasien = '$id_pasien', tanggal_kunjungan = '$tanggal_kunjungan', id_poli = '$id_poli' WHERE id_janji = $id_janji";
    mysqli_query($conn, $sql);
}

// Fungsi untuk menambahkan data ke database
function addDataJanjiToDatabase($id_pasien, $tanggal_kunjungan, $id_poli)
{
    global $conn;
    $sql = "INSERT INTO janji (id_pasien, tanggal_kunjungan, id_poli) VALUES ('$id_pasien', '$tanggal_kunjungan', '$id_poli')";
    mysqli_query($conn, $sql);
}

// Mendapatkan data dari database
$data = getDataJanjiFromDatabase();

// Memproses form update, delete, dan add
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_janji'])) {
        if (isset($_POST['selectedRows'])) {
            $selectedRows = $_POST['selectedRows'];
            foreach ($selectedRows as $id_janji) {
                // Hapus data dari database
                deleteDataJanjiFromDatabase($id_janji);
            }
        }
    } elseif (isset($_POST['update_janji'])) {
        if (isset($_POST['selectedRows'])) {
            $selectedRows = $_POST['selectedRows'];
            foreach ($selectedRows as $id_janji) {
                // Ambil data dari form
                $id_pasien = $_POST['edit_id_pasien'][$id_janji];
                $tanggal_kunjungan = $_POST['edit_tanggal_kunjungan'][$id_janji];
                $id_poli = $_POST['edit_id_poli'][$id_janji];
                // Update data di database
                updateDataJanjiInDatabase($id_janji, $id_pasien, $tanggal_kunjungan, $id_poli);
            }
        }
    } elseif (isset($_POST['tambah_janji'])) {
        $id_pasien = $_POST['new_id_pasien'];
        $tanggal_kunjungan = $_POST['new_tanggal_kunjungan'];
        $id_poli = $_POST['new_id_poli'];
        // Tambahkan data ke database
        addDataJanjiToDatabase($id_pasien, $tanggal_kunjungan, $id_poli);
    }
}

?>
<h2>Form Tambah Janji</h2>
<form action="" method="POST">
    <div class="">
        <div class="form-group">
            <label for="id_pasien">ID Pasien:</label>
            <input type="text" class="form-control" id="id_pasien" name="new_id_pasien">
        </div>
        <div class="form-group">
            <label for="tanggal_kunjungan">Tanggal Kunjungan:</label>
            <input type="date" class="form-control" id="tanggal_kunjungan" name="new_tanggal_kunjungan">
        </div>
        <div class="form-group">
            <label for="id_poli">Poli:</label>
            <select class="form-control" id="id_poli" name="new_id_poli">
                <option value="">Pilih Poli</option>
                <?php
                $sql_poli = "SELECT * FROM poli";
                $result_poli = $conn->query($sql_poli);
                if ($result_poli->num_rows > 0) {
                    while ($row_poli = $result_poli->fetch_assoc()) {
                        echo "<option value='" . $row_poli['id_poli'] . "'>" . $row_poli['nama_poli'] . "</option>";
                    }
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success" name="tambah_janji">Tambah Janji</button>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>ID Pasien Lama</th>
                <th>ID Pasien</th>
                <th>Tanggal Kunjungan</th>
                <th>ID Poli</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($data)): ?>
                <tr>
                    <td>
                        <?php echo $row['id_janji'] ?>
                    </td>
                    <td><input type="text" name="edit_id_pasien[<?php echo $row['id_janji']; ?>]"
                            value="<?php echo $row['id_pasien'] ?>">
                    </td>
                    <td><input type="text" name="edit_tanggal_kunjungan[<?php echo $row['id_janji']; ?>]"
                            value="<?php echo $row['tanggal_kunjungan'] ?>">
                    </td>
                    <td><input type="text" name="edit_id_poli[<?php echo $row['id_janji']; ?>]"
                            value="<?php echo $row['id_poli'] ?>">
                    </td>

                    <td>
                        <input type="checkbox" name="selectedRows[]" value="<?php echo $row['id_janji']; ?>">
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <div>
        <button type="submit" class="btn btn-primary" name="update_pasien_lama">Update</button>
        <button type="submit" class="btn btn-danger" name="delete_pasien_lama">Delete</button>
    </div>
</form>