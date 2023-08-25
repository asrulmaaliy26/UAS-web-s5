<?php
// conn ke database
include "konek.php";

if (!$conn) {
    die('conn gagal: ' . mysqli_connect_error());
}

// Fungsi untuk mendapatkan data dari database
function getDataFromDatabase()
{
    global $conn;
    $sql = "SELECT * FROM nama_tabel";
    $result = mysqli_query($conn, $sql);
    return $result;
}


// Fungsi untuk mengupdate data di database
function updateDataInDatabase($id, $kolom1, $kolom2, $kolom3)
{
    global $conn;
    $sql = "UPDATE nama_tabel SET kolom1 = '$kolom1', kolom2 = '$kolom2', kolom3 = '$kolom3' WHERE id = $id";
    mysqli_query($conn, $sql);
}

// Fungsi untuk menambahkan data ke database
function addDataToDatabase($kolom1, $kolom2, $kolom3)
{
    global $conn;
    $sql = "INSERT INTO nama_tabel (kolom1, kolom2, kolom3) VALUES ('$kolom1', '$kolom2', '$kolom3')";
    mysqli_query($conn, $sql);
}
// Fungsi untuk mereset auto-increment value pada kolom ID
function resetAutoIncrement()
{
    global $koneksi;
    $sql = "SELECT MAX(id) as max_id FROM nama_tabel";
    $result = mysqli_query($koneksi, $sql);
    $row = mysqli_fetch_assoc($result);
    $maxId = $row['max_id'];
    $sql = "ALTER TABLE nama_tabel AUTO_INCREMENT = $maxId";
    mysqli_query($koneksi, $sql);
}

// Fungsi untuk menghapus data dari database
function deleteDataFromDatabase($id)
{
    global $koneksi;
    $sql = "DELETE FROM nama_tabel WHERE id = $id";
    mysqli_query($koneksi, $sql);
    // Mereset auto-increment value
    resetAutoIncrement();
}


// Mendapatkan data dari database
$data = getDataFromDatabase();

// Memproses form update, delete, dan add
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $selectedRows = $_POST['selectedRows'];
        foreach ($selectedRows as $id) {
            // Hapus data dari database
            deleteDataFromDatabase($id);
        }
    } elseif (isset($_POST['update'])) {
        $selectedRows = $_POST['selectedRows'];
        foreach ($selectedRows as $id) {
            // Ambil data dari form
            $kolom1 = $_POST['kolom1'][$id];
            $kolom2 = $_POST['kolom2'][$id];
            $kolom3 = $_POST['kolom3'][$id];
            // Update data di database
            updateDataInDatabase($id, $kolom1, $kolom2, $kolom3);
        }
    } elseif (isset($_POST['add'])) {
        $kolom1 = $_POST['new_kolom1'];
        $kolom2 = $_POST['new_kolom2'];
        $kolom3 = $_POST['new_kolom3'];
        // Tambahkan data ke database
        addDataToDatabase($kolom1, $kolom2, $kolom3);
    }
}
?>

<h2>Tampilan Database</h2>
<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <div class="form-group">
        <div>
            <div></div>
            <div><input type="text" name="new_kolom1"></div>
            <div><input type="text" name="new_kolom2"></div>
            <div><input type="text" name="new_kolom3"></div>
            <div>
                <button type="submit" class="btn btn-success" name="add">Tambah</button>
            </div>
        </div>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Password</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($data)): ?>
                <tr>
                    <td>
                        <?php echo $row['id']; ?>
                    </td>
                    <td><input type="text" name="kolom1[<?php echo $row['id']; ?>]" value="<?php echo $row['kolom1']; ?>">
                    </td>
                    <td><input type="text" name="kolom2[<?php echo $row['id']; ?>]" value="<?php echo $row['kolom2']; ?>">
                    </td>
                    <td><input type="text" name="kolom3[<?php echo $row['id']; ?>]" value="<?php echo $row['kolom3']; ?>">
                    </td>
                    <td>
                        <input type="checkbox" name="selectedRows[]" value="<?php echo $row['id']; ?>">
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <div>
        <button type="submit" class="btn btn-primary" name="update">Update</button>
        <button type="submit" class="btn btn-danger" name="delete">Delete</button>
    </div>
</form>