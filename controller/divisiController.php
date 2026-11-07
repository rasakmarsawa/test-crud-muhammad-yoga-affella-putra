<?php
require_once __DIR__ . '/../connection.php';

class DivisiController
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function all()
    {
        $sql = "SELECT * FROM division ORDER BY id DESC";
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
        $id = (int) $id;
        $sql = "SELECT * FROM division WHERE id = {$id} LIMIT 1";
        $result = mysql_query($sql, $this->conn);
        if (!$result) {
            return false;
        }
        return mysql_fetch_assoc($result);
    }

    public function create($data)
    {
        $division_name = mysql_real_escape_string($data['division_name'], $this->conn);
        $description   = isset($data['description']) ? mysql_real_escape_string($data['description'], $this->conn) : '';

        $sql = "INSERT INTO division (division_name, description)
                VALUES ('{$division_name}', '{$description}')";
        $result = mysql_query($sql, $this->conn);
        if (!$result) {
            return false;
        }
        return mysql_insert_id($this->conn);
    }

    public function update($id, $data)
    {
        $id            = (int) $id;
        $division_name = mysql_real_escape_string($data['division_name'], $this->conn);
        $description   = isset($data['description']) ? mysql_real_escape_string($data['description'], $this->conn) : '';

        $sql = "UPDATE division
                SET division_name = '{$division_name}',
                    description = '{$description}'
                WHERE id = {$id}";
        return mysql_query($sql, $this->conn) ? true : false;
    }

    public function delete($id)
    {
        $id  = (int) $id;
        $sql = "DELETE FROM division WHERE id = {$id}";
        return mysql_query($sql, $this->conn) ? true : false;
    }
}
?>