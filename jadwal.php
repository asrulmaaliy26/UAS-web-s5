<?php
// Buat koneksi ke database
require_once 'database/crud.php';

$database = new crud();

// Query untuk mendapatkan data jadwal dengan nama poli dan nama dokter
$selectColumns = [
    'Jadwal.id_jadwal',
    'Poli.nama_poli',
    'Dokter.nama_dokter',
    'Jadwal.hari',
    'Jadwal.jam_mulai',
    'Jadwal.jam_selesai'
];

$tableJoins = [
    'Jadwal',
    'Poli',   // Tabel pertama dalam klausa JOIN
    'Dokter'  // Tabel kedua dalam klausa JOIN
];

$joinConditions = [
    'Jadwal.id_poli',   // Kondisi JOIN antara tabel Jadwal dan Poli
    'Poli.id_poli',     // Kondisi JOIN antara tabel Poli dan Dokter
    'Dokter.id_dokter'  // Kondisi JOIN antara tabel Dokter dan Jadwal
];

$resultJadwal = $database->readMultiJoinedData($selectColumns, $tableJoins, $joinConditions);

?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<div class="" id="Tab1" class="tabcontent">
    <h3>Data Jadwal</h3>
    <?php
    if (!empty($resultJadwal)) {
        echo "<table class='table'>
                    <thead>
                        <tr>
                            <th>ID Jadwal</th>
                            <th>Nama Poli</th>
                            <th>Nama Dokter</th>
                            <th>Hari</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                        </tr>
                    </thead>
                    <tbody>";

        foreach ($resultJadwal as $row) {
            echo "<tr>
                        <td>" . $row['id_jadwal'] . "</td>
                        <td>" . $row['nama_poli'] . "</td>
                        <td>" . $row['nama_dokter'] . "</td>
                        <td>" . $row['hari'] . "</td>
                        <td>" . $row['jam_mulai'] . "</td>
                        <td>" . $row['jam_selesai'] . "</td>
                    </tr>";
        }

        echo "</tbody>
                </table>";
    } else {
        echo "<p>Tidak ada data jadwal.</p>";
    }
    ?>
</div>
