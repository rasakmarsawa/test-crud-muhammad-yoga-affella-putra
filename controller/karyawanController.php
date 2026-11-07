<?php
require_once __DIR__ . '/../connection.php';

class KaryawanController
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function all()
    {
        $sql = "SELECT k.*, d.division_name
                FROM karyawan k
                LEFT JOIN division d ON d.id = k.division_id
                ORDER BY d.division_name ASC";
        $result = mysql_query($sql, $this->conn);
        if (!$result) {
            return false;
        }

        $rows = array();
        while ($row = mysql_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function find($id)
    {
        $id  = (int) $id;
        $sql = "SELECT k.*, d.division_name
                FROM karyawan k
                LEFT JOIN division d ON d.id = k.division_id
                WHERE k.id = {$id}
                LIMIT 1";
        $result = mysql_query($sql, $this->conn);
        if (!$result) {
            return false;
        }
        return mysql_fetch_assoc($result);
    }

    public function create($data)
    {
        $division_id = (int) $data['division_id'];
        $username    = mysql_real_escape_string($data['username'], $this->conn);
        $password    = mysql_real_escape_string($data['password'], $this->conn);
        $email       = mysql_real_escape_string($data['email'], $this->conn);
        $fullname    = mysql_real_escape_string($data['fullname'], $this->conn);
        $nik         = mysql_real_escape_string($data['NIK'], $this->conn);
        $address     = isset($data['address']) ? mysql_real_escape_string($data['address'], $this->conn) : '';
        $gender      = mysql_real_escape_string($data['gender'], $this->conn);

        $sql = "INSERT INTO karyawan
                    (division_id, username, password, email, fullname, NIK, address, gender)
                VALUES
                    ({$division_id}, '{$username}', '{$password}', '{$email}', '{$fullname}', '{$nik}', '{$address}', '{$gender}')";
        $result = mysql_query($sql, $this->conn);
        if (!$result) {
            return false;
        }
        return mysql_insert_id($this->conn);
    }

    public function update($id, $data)
    {
        $id          = (int) $id;
        $division_id = (int) $data['division_id'];
        $username    = mysql_real_escape_string($data['username'], $this->conn);
        $email       = mysql_real_escape_string($data['email'], $this->conn);
        $fullname    = mysql_real_escape_string($data['fullname'], $this->conn);
        $nik         = mysql_real_escape_string($data['NIK'], $this->conn);
        $address     = isset($data['address']) ? mysql_real_escape_string($data['address'], $this->conn) : '';
        $gender      = mysql_real_escape_string($data['gender'], $this->conn);

        $setPassword = '';
        if (isset($data['password']) && $data['password'] !== '') {
            $password    = mysql_real_escape_string($data['password'], $this->conn);
            $setPassword = ", password = '{$password}'";
        }

        $sql = "UPDATE karyawan
                SET division_id = {$division_id},
                    username = '{$username}',
                    email = '{$email}',
                    fullname = '{$fullname}',
                    NIK = '{$nik}',
                    address = '{$address}',
                    gender = '{$gender}'{$setPassword}
                WHERE id = {$id}";
        return mysql_query($sql, $this->conn) ? true : false;
    }

    public function delete($id)
    {
        $id  = (int) $id;
        $sql = "DELETE FROM karyawan WHERE id = {$id}";
        return mysql_query($sql, $this->conn) ? true : false;
    }
}
?>