<?php
require_once __DIR__ . '/../connection.php';

class PermitController
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function all()
    {
        $sql = "SELECT * FROM permit ORDER BY permit_name ASC";
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
        $sql = "SELECT * FROM permit WHERE id = {$id} LIMIT 1";
        $result = mysql_query($sql, $this->conn);
        if (!$result) {
            return false;
        }
        return mysql_fetch_assoc($result);
    }

    public function create($data)
    {
        $permit_name = mysql_real_escape_string($data['permit_name'], $this->conn);
        $sql = "INSERT INTO permit (permit_name) VALUES ('{$permit_name}')";
        $result = mysql_query($sql, $this->conn);
        if (!$result) {
            return false;
        }
        return mysql_insert_id($this->conn);
    }

    public function update($id, $data)
    {
        $id          = (int) $id;
        $permit_name = mysql_real_escape_string($data['permit_name'], $this->conn);
        $sql = "UPDATE permit SET permit_name = '{$permit_name}' WHERE id = {$id}";
        return mysql_query($sql, $this->conn) ? true : false;
    }

    public function delete($id)
    {
        $id  = (int) $id;
        $sql = "DELETE FROM permit WHERE id = {$id}";
        return mysql_query($sql, $this->conn) ? true : false;
    }
}
