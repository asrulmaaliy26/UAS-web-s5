<?php
require_once 'database/crud.php';

$database = new crud();


// Memproses form update, delete, dan add
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_akun'])) {
        if (isset($_POST['selectedRows'])) {
            $selectedRows = $_POST['selectedRows'];
            foreach ($selectedRows as $id_akun) {
                // Hapus data dari database
                $condition = [
                    'id_akun' => $id_akun
                ];
                $database->deleteData('akun', $condition);
            }
        }
    } elseif (isset($_POST['update_akun'])) {
        if (isset($_POST['selectedRows'])) {
            $selectedRows = $_POST['selectedRows'];
            foreach ($selectedRows as $id_akun) {
                // Ambil data dari form
                $id_pasien = $_POST['edit_id_pasien'][$id_akun];
                $username = $_POST['edit_username'][$id_akun];
                $password = $_POST['edit_password'][$id_akun];
                // Update data di database
                $data = [
                    'nama_akun' => $nama_akun,
                    'username' => $username,
                    'password' => $password
                ];
                $condition = [
                    'id_akun' => $id_akun
                ];
                $database->updateData('admin', $data, $condition);
            }
        }
    } elseif (isset($_POST['tambah_akun'])) {
        $id_pasien = $_POST['new_id_pasien'];
        $username = $_POST['new_username'];
        $password = $_POST['new_password'];
        // Tambahkan data ke database
        $data = [
            'nama_akun' => $nama_akun,
            'username' => $username,
            'password' => $password
        ];
        $database->insertData('akun', $data);
    }
}

?>
<h2>Form Tambah Akun</h2>
<form method="POST" action="">
    <div class="">
        <div class="form-group">
            <label for="id_pasien">ID Pasien:</label>
            <input type="text" class="form-control" id="id_pasien" name="new_id_pasien">
        </div>
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" id="username" name="new_username">
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="new_password">
        </div>
        <button type="submit" class="btn btn-success" name="tambah_akun">Tambah Akun</button>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>ID Akun</th>
                <th>ID Pasien</th>
                <th>Username</th>
                <th>Password</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $result = $database->readData('akun','*');
                foreach ($result as $row) {
                    ?>
                <tr>
                    <td>
                        <?php echo $row['id_akun'] ?>
                    </td>
                    <td><input type="text" name="edit_id_pasien[<?php echo $row['id_akun']; ?>]"
                            value="<?php echo $row['id_pasien'] ?>">
                    </td>
                    <td><input type="text" name="edit_username[<?php echo $row['id_akun']; ?>]"
                            value="<?php echo $row['username'] ?>">
                    </td>
                    <td><input type="text" name="edit_password[<?php echo $row['id_akun']; ?>]"
                            value="<?php echo $row['password']; ?>">
                    </td>
                    <td>
                        <input type="checkbox" name="selectedRows[]" value="<?php echo $row['id_akun']; ?>">
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <div>
        <button type="submit" class="btn btn-primary" name="update_akun">Update</button>
        <button type="submit" class="btn btn-danger" name="delete_akun">Delete</button>
    </div>
</form>