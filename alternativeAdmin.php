<?php
// Buat koneksi ke database
require_once 'database/crud.php';

$database = new crud();

$id_admin = 1;

?>

<!DOCTYPE html>
<html>

<head>
    <title>Data Antrian</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">

        <?php
        $condition = array(
            'id_admin' => $id_admin
        );

        $result = $database->readData('admin', '*', $condition);
        foreach ($result as $row) {
            ?>
            <div class="d-flex justify-content-between">
                <div>
                    <h3> Admin : <span>
                            <?php echo $row['username']; ?>
                        </span> </h3>
                    <h3> Nama : <span>
                            <?php echo $row['nama_admin']; ?>
                        </span> </h3>
                </div>
                <div>
                    <a class="btn btn-success m-3" href="a.php/">refresh</a>
                    <a class="btn btn-primary m-3" href="?logout">Logout</a>
                </div>
            </div>
        <?php } ?>

        <div class="tab">
            <?php
            $tables = $database->getTablesList();
            foreach ($tables as $index => $table) {
                ?>
                <button class="tablinks <?php echo ($index === 0) ? 'active' : ''; ?>"
                    onclick="openTab(event, 'Tab<?php echo $index + 1; ?>')">
                    <?php echo $table; ?>
                </button>
            <?php } ?>
        </div>

        <?php
        foreach ($tables as $index => $table) {
            ?>
            <div id="Tab<?php echo $index + 1; ?>" class="tabcontent">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <div class="">
                        <?php
                        echo "<h2>Table: $table</h2>";

                        $fields = $database->getTableFields($table);

                        // 
                        foreach ($fields as $field) {
                            $type = 'text';
                            ?>
                            <div class="form-group">
                                <label for="">
                                    <?php echo $field ?>
                                </label>
                                <?php
                                if (stripos($field, 'tanggal') !== false) {
                                    $type = 'date';
                                } ?>
                                <input type="<?php echo $type ?>" class="form-control" name="new_<?php echo $field ?>">
                            </div>
                            <?php
                        }
                        ?>
                        <button type="submit" class="btn btn-success" name="add_admin">Tambah</button>'
                        <?php
                        $data = $database->readData($table);


                        echo "<h3>Data:</h3>";
                        echo "<table>";
                        echo "<tr>";
                        
                        // 
                        foreach ($fields as $field) {
                            echo "<th>$field</th>";
                        }
                        echo "</tr>";

                        // 
                        foreach ($data as $row) {
                            echo "<tr>";
                            foreach ($fields as $field) {
                                if ($field === 'image') {
                                    ?>
                                    <td>
                                        <img src="data:image/jpeg;base64,<?php echo base64_encode($row[$field]) ?>" class="card-img-top"
                                            alt="File Image">
                                    </td>
                                    <?php
                                } else {
                                    ?>
                                    <td>
                                        <input type="text" name="edit_username[<?php echo $row[$field]; ?>]"
                                            value="<?php echo $row[$field] ?>">
                                    </td>
                                    <?php
                                }
                            }
                            ?>
                            <td>
                                <input type="checkbox" name="selectedRows[]" value="<?php echo $row[0]['id_'.$field]; ?>">
                            </td>
                            </tr>
                            <?php
                        }
                        ?>
                        </table>
                        <div class="my-3">
                            <button type="submit" class="btn btn-primary" name="update_files">Update</button>
                            <button type="submit" class="btn btn-danger" name="delete_files">Delete</button>
                        </div>
                    </div>
                </form>
            </div>
            <?php
        }
        ?>
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

        // Fungsi untuk menambahkan form
        function addForm() {
            var table = document.getElementById("data-table");
            var row = table.insertRow(-1);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);

            cell1.innerHTML =
                "<div class='d-flex justify-content-between mb-3'><div><label>Select File</label></div><div><input class='btn btn-secondary' type='file' name='f1[]'></div></div>";
            cell2.innerHTML =
                "<div class='d-flex justify-content-between'><div><label for=''>Deskripsi</label></div><div><textarea name='deskripsi[]'></textarea></div></div>";
            cell3.innerHTML =
                "<div class='d-flex justify-content-between'><div><label for=''>Tanggal</label></div><div><input type='date' name='tanggal[]'></div></div>";
        }
    </script>
</body>

</html>