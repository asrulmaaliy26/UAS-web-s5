<?php
include "konek.php";

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

function getDataAntrianFromDatabase()
{
    global $conn;
    $sql = "SELECT * FROM antrian";
    $result = mysqli_query($conn, $sql);
    return $result;
}

function deleteDataAntrianFromDatabase($id_antrian)
{
    global $conn;
    $sql = "DELETE FROM antrian WHERE id_antrian = $id_antrian";
    mysqli_query($conn, $sql);
    resetAutoIncrementAntrian();
}

function resetAutoIncrementAntrian()
{
    global $conn;
    $sql = "SELECT MAX(id_antrian) as max_id_antrian FROM antrian";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $maxIdAntrian = $row['max_id_antrian'];
    $sql = "ALTER TABLE antrian AUTO_INCREMENT = $maxIdAntrian";
    mysqli_query($conn, $sql);
}

function addDataAntrianToDatabase($id_pasien, $id_jadwal, $nomor_antrian)
{
    global $conn;
    $sql = "INSERT INTO antrian (id_pasien, id_jadwal, nomor_antrian) VALUES ('$id_pasien', '$id_jadwal', '$nomor_antrian')";
    mysqli_query($conn, $sql);
}

function updateDataAntrianInDatabase($id_antrian, $id_pasien, $id_jadwal, $nomor_antrian)
{
    global $conn;
    $sql = "UPDATE antrian SET id_pasien = '$id_pasien', id_jadwal = '$id_jadwal', nomor_antrian = '$nomor_antrian' WHERE id_antrian = $id_antrian";
    mysqli_query($conn, $sql);
}

$data = getDataAntrianFromDatabase();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_antrian'])) {
        if (isset($_POST['selectedRows'])) {
            $selectedRows = $_POST['selectedRows'];
            foreach ($selectedRows as $id_antrian) {
                deleteDataAntrianFromDatabase($id_antrian);
            }
        }
    } elseif (isset($_POST['update_antrian'])) {
        foreach ($_POST['selectedRows'] as $id_antrian) {
            $id_pasien = $_POST['edit_id_pasien'][$id_antrian];
            $id_jadwal = $_POST['edit_id_poli'][$id_antrian];
            $nomor_antrian = $_POST['edit_nomor_antrian'][$id_antrian];
            updateDataAntrianInDatabase($id_antrian, $id_pasien, $id_jadwal, $nomor_antrian);
        }
    } elseif (isset($_POST['tambah_antrian'])) {
        $id_pasien = $_POST['new_id_pasien'];
        $id_jadwal = $_POST['new_id_poli'];
        $nomor_antrian = 1;
        addDataAntrianToDatabase($id_pasien, $id_jadwal, $nomor_antrian);
    }
}
?>

<h2>Form Tambah Antrian</h2>
<form action="" method="POST">
    <div>
        <div class="form-group">
            <label for="id_pasien">ID Pasien:</label>
            <input type="text" class="form-control" id="id_pasien" name="new_id_pasien">
        </div>
        <div class="form-group">
            <label for="id_poli">ID Poli:</label>
            <select class="form-control" id="id_poli" name="new_id_poli">
                <option value="">Pilih Poli</option>
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
        <div class="form-group">
            <label for="tanggal_antrian">Tanggal Antrian:</label>
            <input type="date" class="form-control" id="tanggal_antrian" name="new_tanggal_antrian">
        </div>
        <button type="submit" class="btn btn-success" name="tambah_antrian">Tambah Antrian</button>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>ID Antrian</th>
                <th>ID Pasien</th>
                <th>ID Jadwal</th>
                <th>Nomor Antrian</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($data)): ?>
                <tr>
                    <td>
                        <?php echo $row['id_antrian'] ?>
                    </td>
                    <td><input type="text" name="edit_id_pasien[<?php echo $row['id_antrian']; ?>]"
                            value="<?php echo $row['id_pasien'] ?>">
                    </td>
                    <td><input type="text" name="edit_id_jadwal[<?php echo $row['id_antrian']; ?>]"
                            value="<?php echo $row['id_jadwal'] ?>">
                    </td>
                    <td><input type="text" name="edit_nomor_antrian[<?php echo $row['id_antrian']; ?>]"
                            value="<?php echo $row['nomor_antrian'] ?>">
                    </td>
                    <td>
                        <input type="checkbox" name="selectedRows[]" value="<?php echo $row['id_antrian']; ?>">
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <div>
        <button type="submit" class="btn btn-primary" name="update_antrian">Update</button>
        <button type="submit" class="btn btn-danger" name="delete_antrian">Delete</button>
    </div>
</form>
