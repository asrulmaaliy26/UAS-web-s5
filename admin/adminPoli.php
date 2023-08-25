<?php

// Memproses form update, delete, dan add
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_poli'])) {
        if (isset($_POST['selectedRows'])) {
            $selectedRows = $_POST['selectedRows'];
            foreach ($selectedRows as $id_poli) {
                // Hapus data dari database
                $condition = [
                    'id_admin' => $id_admin
                ];
                $database->deleteData('poli', $condition);
            }
        }
    } elseif (isset($_POST['update_poli'])) {
        if (isset($_POST['selectedRows'])) {
            $selectedRows = $_POST['selectedRows'];
            foreach ($selectedRows as $id_poli) {
                // Ambil data dari form
                $nama_poli = $_POST['edit_nama_poli'][$id_poli];
                $ruang_poli = $_POST['edit_ruang_poli'][$id_poli];
                // Update data di database
                $data = [
                    'nama_poli' => $nama_poli,
                    'ruang_poli' => $ruang_poli
                ];
                $condition = [
                    'id_admin' => $id_admin
                ];
                $database->updateData('poli', $data, $condition);
            }
        }
    } elseif (isset($_POST['tambah_poli'])) {
        $nama_poli = $_POST['new_nama_poli'];
        $ruang_poli = $_POST['new_ruang_poli'];
        // Tambahkan data ke database
        $data = [
            'nama_poli' => $nama_poli,
            'ruang_poli' => $ruang_poli
        ];
        $database->insertData('poli',$data);
    }
}
?>

<h2>Tambah Data Poli</h2>
<form action='' method='POST'>
    <div class='form-group'>
        <label for='nama_poli'>Nama Poli:</label>
        <input type='text' class='form-control' id='nama_poli' name='new_nama_poli'>
    </div>
    <div class='form-group'>
        <label for='ruang_poli'>Ruang Poli:</label>
        <input type='text' class='form-control' id='ruang_poli' name='new_ruang_poli'>
    </div>
    <button type='submit' class='btn btn-success' name='tambah_poli'>Tambah Poli</button>

    <h2>Data Poli</h2>
    <table class='table'>
        <tr>
            <th>ID Poli</th>
            <th>Nama Poli</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($data)): ?>
            <tr>
                <td>
                    <?php echo $row['id_poli'] ?>
                </td>
                <td><input type="text" name="edit_nama_poli[<?php echo $row['id_poli']; ?>]"
                        value="<?php echo $row['nama_poli'] ?>">
                </td>
                <td><input type="text" name="edit_ruang_poli[<?php echo $row['id_poli']; ?>]"
                        value="<?php echo $row['ruang_poli'] ?>">
                </td>
                <td>
                    <input type="checkbox" name="selectedRows[]" value="<?php echo $row['id_poli']; ?>">
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <div>
        <button type="submit" class="btn btn-primary" name="update_poli">Update</button>
        <button type="submit" class="btn btn-danger" name="delete_poli">Delete</button>
    </div>
</form>