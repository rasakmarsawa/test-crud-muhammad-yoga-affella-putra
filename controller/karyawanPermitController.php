<?php
require_once __DIR__ . '/../connection.php';

class KaryawanPermitController
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function all()
    {
        $sql = "SELECT kp.*, k.fullname AS karyawan_name, p.permit_name
                FROM karyawan_permit kp
                LEFT JOIN karyawan k ON k.id = kp.karyawan_id
                LEFT JOIN permit p ON p.id = kp.permit_id
                ORDER BY kp.year DESC, k.fullname ASC, p.permit_name ASC";
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
        $sql = "SELECT kp.*, k.fullname AS karyawan_name, p.permit_name
                FROM karyawan_permit kp
                LEFT JOIN karyawan k ON k.id = kp.karyawan_id
                LEFT JOIN permit p ON p.id = kp.permit_id
                WHERE kp.id = {$id}
                LIMIT 1";
        $result = mysql_query($sql, $this->conn);
        if (!$result) {
            return false;
        }
        return mysql_fetch_assoc($result);
    }

    public function create($data)
    {
        $karyawan_id = (int) $data['karyawan_id'];
        $permit_id   = (int) $data['permit_id'];
        $year        = (int) $data['year'];
        $days_amount = (int) $data['days_amount'];

        $sql = "INSERT INTO karyawan_permit (karyawan_id, permit_id, year, days_amount)
                VALUES ({$karyawan_id}, {$permit_id}, {$year}, {$days_amount})";
        $result = mysql_query($sql, $this->conn);
        if (!$result) {
            return false;
        }
        return mysql_insert_id($this->conn);
    }

    public function update($id, $data)
    {
        $id          = (int) $id;
        $karyawan_id = (int) $data['karyawan_id'];
        $permit_id   = (int) $data['permit_id'];
        $year        = (int) $data['year'];
        $days_amount = (int) $data['days_amount'];

        $sql = "UPDATE karyawan_permit
                SET karyawan_id = {$karyawan_id},
                    permit_id = {$permit_id},
                    year = {$year},
                    days_amount = {$days_amount}
                WHERE id = {$id}";
        return mysql_query($sql, $this->conn) ? true : false;
    }

    public function useQuota($id, $startDate, $endDate, $amountDate)
    {
        $id          = (int) $id;
        $startDate   = mysql_real_escape_string($startDate, $this->conn);
        $endDate     = mysql_real_escape_string($endDate, $this->conn);
        $amountDate  = (int) $amountDate;

        if (!mysql_query("START TRANSACTION", $this->conn)) {
            return 'Gagal memulai transaksi: ' . mysql_error($this->conn);
        }

        $lock = mysql_query("SELECT days_amount FROM karyawan_permit WHERE id = {$id} FOR UPDATE", $this->conn);
        if (!$lock) {
            $err = mysql_error($this->conn);
            mysql_query("ROLLBACK", $this->conn);
            return 'Gagal mengunci data: ' . $err;
        }
        $current = mysql_fetch_assoc($lock);
        if (!$current) {
            mysql_query("ROLLBACK", $this->conn);
            return 'Data permit karyawan tidak ditemukan.';
        }
        if ($amountDate > (int) $current['days_amount']) {
            mysql_query("ROLLBACK", $this->conn);
            return 'Jumlah hari melebihi sisa kuota.';
        }

        $sql = "INSERT INTO permit_usage (karyawan_permit_id, start_date, end_date, amount_date, is_false_entry)
                VALUES ({$id}, '{$startDate}', '{$endDate}', {$amountDate}, 0)";
        if (!mysql_query($sql, $this->conn)) {
            $err = mysql_error($this->conn);
            mysql_query("ROLLBACK", $this->conn);
            return 'Gagal menyimpan penggunaan: ' . $err;
        }

        $sql = "UPDATE karyawan_permit SET days_amount = days_amount - {$amountDate} WHERE id = {$id}";
        if (!mysql_query($sql, $this->conn)) {
            $err = mysql_error($this->conn);
            mysql_query("ROLLBACK", $this->conn);
            return 'Gagal memperbarui kuota: ' . $err;
        }

        if (!mysql_query("COMMIT", $this->conn)) {
            $err = mysql_error($this->conn);
            mysql_query("ROLLBACK", $this->conn);
            return 'Gagal commit transaksi: ' . $err;
        }

        return true;
    }

    public function delete($id)
    {
        $id  = (int) $id;
        $sql = "DELETE FROM karyawan_permit WHERE id = {$id}";
        return mysql_query($sql, $this->conn) ? true : false;
    }
}
