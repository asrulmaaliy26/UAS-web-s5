<!DOCTYPE html>
<html>

<head>
    <title>Data Antrian</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">

</head>

<body>
    <div class="container">
        <div class="tab">
            <?php
            require_once 'database/crud.php';

            $crudObject = new crud(); // Menginstansiasi objek CRUD
            
            // Query untuk mendapatkan data poli
            $sql = "SELECT * FROM poli";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $activeTab = true;
                $tabContent = '';

                while ($row = $result->fetch_assoc()) {
                    $id_poli = $row['id_poli'];
                    $nama_poli = $row['nama_poli'];

                    // Mengatur tab pertama sebagai aktif
                    $activeClass = $activeTab ? 'active' : '';
                    $activeContent = $activeTab ? 'block' : 'none';

                    echo "<button class='tablinks $activeClass' onclick='openTab(event, \"Tab$id_poli\")'>$nama_poli</button>";

                    // Query untuk mendapatkan data antrian
                    $select_query = "SELECT
    Antrian.nomor_antrian,
    Antrian.pesan,
    Pasien.nama_pasien,
    Pasien.alamat,
    Dokter.nama_dokter,
    Jadwal.jam_mulai
    FROM
    Antrian
    INNER JOIN Pasien ON Antrian.id_pasien = Pasien.id_pasien
    INNER JOIN Jadwal ON Antrian.id_jadwal = Jadwal.id_jadwal
    INNER JOIN Dokter ON Jadwal.id_dokter = Dokter.id_dokter
    WHERE
    Jadwal.id_poli = '$id_poli'
    ORDER BY
    Antrian.nomor_antrian ASC";


                    // Eksekusi query
                    $result_antrian = $conn->query($select_query);

                    $tabContent .= "<div id='Tab$id_poli' class='tabcontent' style='display: $activeContent;'><br>
                        <h3>Data Antrian $nama_poli</h3>";

                    if ($result_antrian->num_rows > 0) {
                        $tabContent .= "<table class='table'>
                            <thead>
                                <tr>
                                    <th>Nomor Antrian</th>
                                    <th>Nama Pasien</th>
                                    <th>Alamat</th>
                                    <th>Nama Dokter</th>
                                    <th>Jam Mulai</th>
                                    <th>Pesan</th>
                                </tr>
                            </thead>
                            <tbody>";

                        while ($row_antrian = $result_antrian->fetch_assoc()) {
                            $tabContent .= "<tr>
                                    <td>" . $row_antrian['nomor_antrian'] . "</td>
                                    <td>" . $row_antrian['nama_pasien'] . "</td>
                                    <td>" . $row_antrian['alamat'] . "</td>
                                    <td>" . $row_antrian['nama_dokter'] . "</td>
                                    <td>" . $row_antrian['jam_mulai'] . "</td>
                                    <td>" . $row_antrian['pesan'] . "</td>
                                </tr>";
                        }

                        $tabContent .= "</tbody>
                            </table>";
                    } else {
                        $tabContent .= "<p>Tidak ada data antrian.</p>";
                    }

                    $tabContent .= "</div>";

                    $activeTab = false;
                }

                echo $tabContent;
            } else {
                echo "<p>Tidak ada data poli.</p>";
            }
            ?>
        </div>
    </div>

    <script>
        // Fungsi untuk membuka tab
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }
    </script>
</body>

</html>