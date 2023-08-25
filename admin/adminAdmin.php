<?php
// Koneksi ke database

require_once 'database/crud.php';

$database = new crud();

// Memproses form update, delete, dan add
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_admin'])) {
        $selectedRows = $_POST['selectedRows'];
        foreach ($selectedRows as $id_admin) {
            // Hapus data dari database
            $condition = [
                'id_admin' => $id_admin
            ];
            $database->deleteData('admin',$condition);
        }
    } elseif (isset($_POST['update_admin'])) {
        $selectedRows = $_POST['selectedRows'];
        foreach ($selectedRows as $id_admin) {
            // Ambil data dari form
            $nama_admin = $_POST['edit_nama_admin'][$id_admin];
            $username = $_POST['edit_username'][$id_admin];
            $password = $_POST['edit_password'][$id_admin];
            // Update data di database
            $data = [
                'nama_admin' => $nama_admin,
                'username' => $username,
                'password' => $password
            ];
            $condition = [
                'id_admin' => $id_admin
            ];
            $database->updateData('admin', $data, $condition);
        }
    } elseif (isset($_POST['add_admin'])) {
        $nama_admin = $_POST['new_nama_admin'];
        $username = $_POST['new_username'];
        $password = $_POST['new_password'];
        // Tambahkan data ke database

        $data = [
            'nama_admin' => $nama_admin,
            'username' => $username,
            'password' => $password
        ];
        $database->insertData('admin', $data);
    }
}
?>
<h2>Tampilan Database</h2>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    <div class="">
        <div class="form-group">
            <label for="nama_admin">Nama Admin :</label>
            <input type="text" class="form-control" name="new_nama_admin">
        </div>
        <div class="form-group">
            <label for="username">Username :</label>
            <input type="text" class="form-control" name="new_username">
        </div>
        <div class="form-group">
            <label for="password">Password :</label>
            <input type="text" class="form-control" name="new_password">
        </div>
        <button type="submit" class="btn btn-success" name="add_admin">Tambah</button>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>ID Admin</th>
                <th>Nama Admin</th>
                <th>Username</th>
                <th>Password</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $result = $database->readData('admin','*');
                foreach ($result as $row) {
                ?>
                <tr>
                    <td>
                        <?php echo $row['id_admin']; ?>
                    </td>
                    <td><input type="text" name="edit_nama_admin[<?php echo $row['id_admin']; ?>]"
                            value="<?php echo $row['nama_admin']; ?>"></td>
                    <td><input type="text" name="edit_username[<?php echo $row['id_admin']; ?>]"
                            value="<?php echo $row['username']; ?>"></td>
                    <td><input type="text" name="edit_password[<?php echo $row['id_admin']; ?>]"
                            value="<?php echo $row['password']; ?>"></td>
                    <td>
                        <input type="checkbox" name="selectedRows[]" value="<?php echo $row['id_admin']; ?>">
                    </td>
                </tr>
                <?php } ?>
        </tbody>
    </table>
    <div>
        <button type="submit" class="btn btn-primary" name="update_admin">Update</button>
        <button type="submit" class="btn btn-danger" name="delete_admin">Delete</button>
    </div>
</form>