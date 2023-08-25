<?php

require_once 'konek.php';
class crud extends Database
{
    public function __construct()
    {
        // Memanggil konstruktor dari kelas induk (Database)
        parent::__construct();

        // Cek koneksi ke database
        if (!$this->checkConnection()) {
            die("Koneksi ke database gagal: " . $this->conn->connect_error);
        }
    }

    public function checkConnection()
    {
        return $this->getConnection() !== null;
    }

    public function resetAutoIncrement($tableName)
    {
        $conn = $this->getConnection();
        $sql = "SELECT MAX(id) as max_id FROM $tableName";
        $result = $conn->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $maxId = $row['max_id'];

        if (!empty($maxId)) {
            $sql = "ALTER TABLE $tableName AUTO_INCREMENT = " . ($maxId + 1);
            $conn->exec($sql);
        }
    }

    public function getTableFields($tableName)
    {
        try {
            $conn = $this->getConnection();

            // Dapatkan informasi mengenai kolom dalam tabel
            $query = "DESCRIBE $tableName";
            $result = $conn->query($query);

            $fields = [];
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $fields[] = $row['Field'];
            }

            return $fields;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    public function getTablesList()
    {
        try {
            $conn = $this->getConnection();

            $query = "SHOW TABLES";
            $result = $conn->query($query);

            $tables = [];
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $tables[] = $row[0];
            }

            return $tables;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    public function insertData($tableName, $data)
    {
        resetAutoIncrement();
        try {
            $conn = $this->getConnection();
            $columns = implode(", ", array_keys($data));
            $placeholders = ":" . implode(", :", array_keys($data));
            $sql = "INSERT INTO $tableName ($columns) VALUES ($placeholders)";
            $stmt = $conn->prepare($sql);
            $stmt->execute($data);
            return true;
        } catch (PDOException $e) {
            echo "Insert failed: " . $e->getMessage();
            return false;
        }
    }

    public function updateData($tableName, $data, $condition)
    {
        try {
            $conn = $this->getConnection();
            $setValues = '';
            foreach ($data as $column => $value) {
                $setValues .= "$column = :$column, ";
            }
            $setValues = rtrim($setValues, ', ');

            $whereCondition = '';
            foreach ($condition as $column => $value) {
                $whereCondition .= "$column = :$column AND ";
            }
            $whereCondition = rtrim($whereCondition, 'AND ');

            $sql = "UPDATE $tableName SET $setValues WHERE $whereCondition";
            $stmt = $conn->prepare($sql);
            $stmt->execute(array_merge($data, $condition));

            echo "Data updated successfully.";
        } catch (PDOException $e) {
            echo "Update failed: " . $e->getMessage();
        }
    }

    public function deleteData($tableName, $condition)
    {
        try {
            $conn = $this->getConnection();

            $whereCondition = '';
            foreach ($condition as $column => $value) {
                $whereCondition .= "$column = :$column AND ";
            }
            $whereCondition = rtrim($whereCondition, 'AND ');

            $sql = "DELETE FROM $tableName WHERE $whereCondition";

            $stmt = $conn->prepare($sql);
            $stmt->execute($condition);

            echo "Data deleted successfully.";
        } catch (PDOException $e) {
            echo "Delete failed: " . $e->getMessage();
        }
        resetAutoIncrement();
    }

    public function selectQuery($query)
    {
        try {
            $conn = $this->getConnection();
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "Query failed: " . $e->getMessage();
            return array();
        }
    }

    public function readData($tableName, $columns = '*', $condition = array())
    {
        try {
            $conn = $this->getConnection();
            $columnList = is_array($columns) ? implode(', ', $columns) : $columns;

            $whereCondition = '';
            foreach ($condition as $column => $value) {
                $whereCondition .= "$column = :$column AND ";
            }
            $whereCondition = rtrim($whereCondition, 'AND ');

            $sql = "SELECT $columnList FROM $tableName";
            if (!empty($whereCondition)) {
                $sql .= " WHERE $whereCondition";
            }

            $stmt = $conn->prepare($sql);
            $stmt->execute($condition);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            echo "Read failed: " . $e->getMessage();
            return array();
        }
    }

    public function readJoinedData($selectColumns, $tableName1, $tableName2, $joinColumn1, $joinColumn2, $condition = array())
    {
        try {
            $conn = $this->getConnection();
            $columnList = is_array($selectColumns) ? implode(', ', $selectColumns) : $selectColumns;

            $whereCondition = '';
            foreach ($condition as $column => $value) {
                $whereCondition .= "$column = :$column AND ";
            }
            $whereCondition = rtrim($whereCondition, 'AND ');

            $sql = "SELECT $columnList FROM $tableName1 
                JOIN $tableName2 ON $tableName1.$joinColumn1 = $tableName2.$joinColumn2";

            if (!empty($whereCondition)) {
                $sql .= " WHERE $whereCondition";
            }

            $stmt = $conn->prepare($sql);
            $stmt->execute($condition);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            echo "Read failed: " . $e->getMessage();
            return array();
        }
    }

    public function readMultiJoinedData($selectColumns, $tableJoins, $joinConditions, $condition = array())
    {
        try {
            $conn = $this->getConnection();
            $columnList = is_array($selectColumns) ? implode(', ', $selectColumns) : $selectColumns;

            $whereCondition = '';
            foreach ($condition as $column => $value) {
                $whereCondition .= "$column = :$column AND ";
            }
            $whereCondition = rtrim($whereCondition, 'AND ');

            $sql = "SELECT $columnList FROM ";

            $tablesCount = count($tableJoins);
            for ($i = 0; $i < $tablesCount; $i++) {
                $sql .= $tableJoins[$i];
                if ($i < $tablesCount - 1) {
                    $sql .= " JOIN ";
                }
            }

            $sql .= " ON ";
            for ($i = 0; $i < $tablesCount - 1; $i++) {
                $sql .= $joinConditions[$i] . " = " . $joinConditions[$i + 1];
                if ($i < $tablesCount - 2) {
                    $sql .= " AND ";
                }
            }

            if (!empty($whereCondition)) {
                $sql .= " WHERE $whereCondition";
            }

            $stmt = $conn->prepare($sql);
            $stmt->execute($condition);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            echo "Read failed: " . $e->getMessage();
            return array();
        }
    }

    // $result1 = $crudObject->readData('users')
    // print_r($result1)

    // $columns = array('username', 'email')
    // $condition = array(
    //     'age' => 25
    // )
    // $result2 = $crudObject->readData('users', $columns, $condition);
    // print_r($result2)

    // $condition = array(
    //     'email' => 'user2@example.com'
    // )
    // $result3 = $crudObject->readData('users', '*', $condition)
    // print_r($result3)
}

?>