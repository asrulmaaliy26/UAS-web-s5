<?php
// Koneksi ke database
include "konek.php";
if (!$conn) {
    die('Koneksi gagal: ' . mysqli_connect_error());
}

// Fungsi untuk mendapatkan data dari database
function getDatapasienFromDatabase()
{
    global $conn;
    $sql = "SELECT * FROM pasien";
    $result = mysqli_query($conn, $sql);
    return $result;
}

// Fungsi untuk menghapus data dari database
function deleteDatapasienFromDatabase($id_pasien)
{
    global $conn;
    $sql = "DELETE FROM pasien WHERE id_pasien = '$id_pasien'";
    mysqli_query($conn, $sql);
    // Mereset auto-increment value
    resetAutoIncrementpasien();
}

// Fungsi untuk mereset auto-increment value pada kolom ID
function resetAutoIncrementpasien()
{
    global $conn;
    $sql = "SELECT MAX(id_pasien) as max_id_pasien FROM pasien";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $maxIdpasien = $row['max_id_pasien'];
    $sql = "ALTER TABLE pasien AUTO_INCREMENT = $maxIdpasien";
    mysqli_query($conn, $sql);
}

// Fungsi untuk mengupdate data di database
function updateDatapasienInDatabase($id_pasien, $nama_pasien, $jenis_kelamin, $tanggal_lahir, $alamat, $no_telepon)
{
    global $conn;
    $sql = "UPDATE pasien SET nama_pasien = '$nama_pasien', jenis_kelamin = '$jenis_kelamin', tanggal_lahir = '$tanggal_lahir', alamat = '$alamat', no_telepon = '$no_telepon' WHERE id_pasien = $id_pasien";
    mysqli_query($conn, $sql);
}

// Fungsi untuk menambahkan data ke database
function addDatapasienToDatabase($nama_pasien, $jenis_kelamin, $tanggal_lahir, $alamat, $no_telepon)
{
    global $conn;
    $sql = "INSERT INTO pasien (nama_pasien, jenis_kelamin, tanggal_lahir, alamat, no_telepon) VALUES ('$nama_pasien', '$jenis_kelamin', '$tanggal_lahir', '$alamat', '$no_telepon')";
    mysqli_query($conn, $sql);
}

// Mendapatkan data dari database
$data = getDatapasienFromDatabase();

// Memproses form update, delete, dan add
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_pasien'])) {
        $selectedRows = $_POST['selectedRows'];
        foreach ($selectedRows as $id_pasien) {
            // Hapus data dari database
            deleteDatapasienFromDatabase($id_pasien);
        }
    } elseif (isset($_POST['update_pasien'])) {
        $selectedRows = $_POST['selectedRows'];
        foreach ($selectedRows as $id_pasien) {
            // Ambil data dari form
            $nama_pasien = $_POST['edit_nama_pasien'][$id_pasien];
            $jenis_kelamin = $_POST['edit_jenis_kelamin'][$id_pasien];
            $tanggal_lahir = $_POST['edit_tanggal_lahir'][$id_pasien];
            $alamat = $_POST['edit_alamat'][$id_pasien];
            $no_telepon = $_POST['edit_no_telepon'][$id_pasien];
            // Update data di database
            updateDatapasienInDatabase($id_pasien, $nama_pasien, $jenis_kelamin, $tanggal_lahir, $alamat, $no_telepon);
        }
    } elseif (isset($_POST['add_pasien'])) {
        $nama_pasien = $_POST['new_nama_pasien'];
        $jenis_kelamin = $_POST['new_jenis_kelamin'];
        $tanggal_lahir = $_POST['new_tanggal_lahir'];
        $alamat = $_POST['new_alamat'];
        $no_telepon = $_POST['new_no_telepon'];
        // Tambahkan data ke database
        addDatapasienToDatabase($nama_pasien, $jenis_kelamin, $tanggal_lahir, $alamat, $no_telepon);
    }
}

?>

<h2>Tambah Data Pasien</h2>
<form action='' method='POST'>
    <div class='form-group'>
        <label for='nama_pasien'>Nama Pasien:</label>
        <input type='text' class='form-control' id='nama_pasien' name='new_nama_pasien'>
    </div>
    <div class='form-group'>
        <label for='jenis_kelamin'>Jenis Kelamin:</label>
        <select class='form-control' id='jenis_kelamin' name='new_jenis_kelamin'>
            <option value='Laki-laki'>Laki-laki</option>
            <option value='Perempuan'>Perempuan</option>
        </select>
    </div>
    <div class='form-group'>
        <label for='tanggal_lahir'>Tanggal Lahir:</label>
        <input type='date' class='form-control' id='tanggal_lahir' name='new_tanggal_lahir'>
    </div>
    <div class='form-group'>
        <label for='alamat'>Alamat:</label>
        <textarea class='form-control' id='alamat' name='new_alamat'></textarea>
    </div>
    <div class='form-group'>
        <label for='no_telepon'>No. Telepon:</label>
        <input type='text' class='form-control' id='no_telepon' name='new_no_telepon'>
    </div>
    <button type='submit' class='btn btn-success' name='add_pasien'>Tambah Data</button>
    <h2>Data Pasien</h2>
    <table class='table'>
        <thead>
            <tr>
                <th>ID Pasien</th>
                <th>Nama Pasien</th>
                <th>Jenis Kelamin</th>
                <th>Tanggal Lahir</th>
                <th>Alamat</th>
                <th>No. Telepon</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($data)): ?>
                <tr>
                    <td>
                        <?php echo $row['id_pasien'] ?>
                    </td>
                    <td><input type="text" name="edit_nama_pasien[<?php echo $row['id_pasien']; ?>]" value="<?php echo $row['nama_pasien']; ?>"></td>
                    <td><input type="text" name="edit_jenis_kelamin[<?php echo $row['id_pasien']; ?>]" value="<?php echo $row['jenis_kelamin']; ?>"></td>
                    <td><input type="text" name="edit_tanggal_lahir[<?php echo $row['id_pasien']; ?>]" value="<?php echo $row['tanggal_lahir']; ?>"></td>
                    <td><input type="text" name="edit_alamat[<?php echo $row['id_pasien']; ?>]" value="<?php echo $row['alamat']; ?>"></td>
                    <td><input type="text" name="edit_no_telepon[<?php echo $row['id_pasien']; ?>]" value="<?php echo $row['no_telepon']; ?>"></td>
                    <td>
                        <input type="checkbox" name="selectedRows[]" value="<?php echo $row['id_pasien']; ?>">
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <div>
        <button type="submit" class="btn btn-primary" name="update_pasien">Update</button>
        <button type="submit" class="btn btn-danger" name="delete_pasien">Delete</button>
    </div>
</form>
