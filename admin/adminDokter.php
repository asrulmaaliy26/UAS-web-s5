<?php
// Buat koneksi ke database
require_once "database/crud.php";

$database = new crud();

// Memproses form update, delete, dan add
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_dokter'])) {
        if (isset($_POST['selectedRows'])) {
            $selectedRows = $_POST['selectedRows'];
            foreach ($selectedRows as $id_dokter) {
                // Hapus data dari database
                $condition = [
                    'id_dokter' => $id_dokter
                ];
                $database->deleteData($condition);
            }
        }
    } elseif (isset($_POST['update_dokter'])) {
        if (isset($_POST['selectedRows'])) {
            $selectedRows = $_POST['selectedRows'];
            foreach ($selectedRows as $id_dokter) {
                // Ambil data dari form
                $nama_dokter = $_POST['edit_nama_dokter'][$id_dokter];
                $spesialisasi = $_POST['edit_spesialisasi'][$id_dokter];
                $alamat_dokter = $_POST['edit_alamat_dokter'][$id_dokter];
                $nomor_telepon_dokter = $_POST['edit_nomor_telepon_dokter'][$id_dokter];
                // Update data di database
                $data = [
                    'nama_dokter' => $nama_dokter,
                    'spesialisasi' => $spesialisasi,
                    'alamat_dokter' => $alamat_dokter,
                    'nomor_telepon_dokter' => $nomor_telepon_dokter
                ];
                $condition = [
                    'id_dokter' => $id_dokter
                ];
                $database->updateData('dokter', $data, $condition);
            }
        }
    } elseif (isset($_POST['tambah_dokter'])) {
        $nama_dokter = $_POST['new_nama_dokter'];
        $spesialisasi = $_POST['new_spesialisasi'];
        $alamat_dokter = $_POST['new_alamat_dokter'];
        $nomor_telepon_dokter = $_POST['new_nomor_telepon_dokter'];
        // Tambahkan data ke database
        $data = [
            'nama_dokter' => $nama_dokter,
            'spesialisasi' => $alamat_dokter,
            'alamat_dokter' => $alamat_dokter,
            'nomor_telepon_dokter' => $nomor_telepon_dokter
        ];

        $database->insertData('dokter', $data);
    }
}

?>

<h2>Form Tambah Dokter</h2>
<form action="" method="POST">
    <div class="">
        <div class="form-group">
            <label for="nama_dokter">Nama Dokter:</label>
            <input type="text" class="form-control" id="nama_dokter" name="new_nama_dokter">
        </div>
        <div class="form-group">
            <label for="spesialisasi">Spesialisasi:</label>
            <select class="form-control" id="spesialisasi" name="new_spesialisasi">
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
            <label for="alamat_dokter">alamat_dokter:</label>
            <input type="text" class="form-control" id="alamat_dokter" name="new_alamat_dokter">
        </div>
        <div class="form-group">
            <label for="nomor_telepon_dokter">nomor_telepon_dokter:</label>
            <input type="text" class="form-control" id="nomor_telepon_dokter" name="new_nomor_telepon_dokter">
        </div>
        <button type="submit" class="btn btn-success" name="tambah_dokter">Tambah Dokter</button>

    </div>
    <table class="mt-3 table">
        <thead>
            <tr>
                <th>ID Dokter</th>
                <th>Nama Dokter</th>
                <th>Spesialisasi</th>
                <th>Alamat</th>
                <th>Nomor Telepon</th>
                <th>Aksi</th>
            </tr>
        </thead>
        </thead>
        <tbody>
            <?php $resultDokter = $database->readData('dokter');
            if ($resultDokter > 0) {
                foreach ($resultDokter as $row) {
                    ?>
                    <tr>
                        <td>
                            <?php echo $row['id_dokter'] ?>
                        </td>
                        <td><input type="text" name="edit_nama_dokter[<?php echo $row['id_dokter']; ?>]"
                                value="<?php echo $row['nama_dokter'] ?>">
                        </td>
                        <td><input type="text" name="edit_spesialisasi[<?php echo $row['id_dokter']; ?>]"
                                value="<?php echo $row['spesialisasi'] ?>">
                        </td>
                        <td><input type="text" name="edit_alamat_dokter[<?php echo $row['id_dokter']; ?>]"
                                value="<?php echo $row['alamat_dokter'] ?>">
                        </td>
                        <td><input type="text" name="edit_nomor_telepon_dokter[<?php echo $row['id_dokter']; ?>]"
                                value="<?php echo $row['nomor_telepon_dokter'] ?>">
                        </td>
                        <td>
                            <input type="checkbox" name="selectedRows[]" value="<?php echo $row['id_dokter']; ?>">
                        </td>
                    </tr>
                <?php }
            } ?>
        </tbody>
    </table>
    <div>
        <button type="submit" class="btn btn-primary" name="update_dokter">Update</button>
        <button type="submit" class="btn btn-danger" name="delete_dokter">Delete</button>
    </div>
</form>