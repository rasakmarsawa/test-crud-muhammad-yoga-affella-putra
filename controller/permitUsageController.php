<?php
require_once __DIR__ . '/../connection.php';

class PermitUsageController
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function all()
    {
        $sql = "SELECT pu.*, k.fullname AS karyawan_name, p.permit_name, kp.year
                FROM permit_usage pu
                LEFT JOIN karyawan_permit kp ON kp.id = pu.karyawan_permit_id
                LEFT JOIN karyawan k ON k.id = kp.karyawan_id
                LEFT JOIN permit p ON p.id = kp.permit_id
                ORDER BY pu.start_date DESC, pu.id DESC";
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

    public function forKaryawanPermit($karyawanPermitId)
    {
        $karyawanPermitId = (int) $karyawanPermitId;
        $sql = "SELECT pu.*, k.fullname AS karyawan_name, p.permit_name, kp.year
                FROM permit_usage pu
                LEFT JOIN karyawan_permit kp ON kp.id = pu.karyawan_permit_id
                LEFT JOIN karyawan k ON k.id = kp.karyawan_id
                LEFT JOIN permit p ON p.id = kp.permit_id
                WHERE pu.karyawan_permit_id = {$karyawanPermitId}
                ORDER BY pu.start_date DESC, pu.id DESC";
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

    public function rollback($id)
    {
        $id = (int) $id;

        if (!mysql_query("START TRANSACTION", $this->conn)) {
            return 'Gagal memulai transaksi: ' . mysql_error($this->conn);
        }

        $res = mysql_query("SELECT karyawan_permit_id, amount_date, is_false_entry FROM permit_usage WHERE id = {$id} FOR UPDATE", $this->conn);
        if (!$res) {
            $err = mysql_error($this->conn);
            mysql_query("ROLLBACK", $this->conn);
            return 'Gagal mengunci data: ' . $err;
        }
        $row = mysql_fetch_assoc($res);
        if (!$row) {
            mysql_query("ROLLBACK", $this->conn);
            return 'Data penggunaan tidak ditemukan.';
        }
        if ((int) $row['is_false_entry'] === 1) {
            mysql_query("ROLLBACK", $this->conn);
            return 'Data ini sudah di-rollback.';
        }

        $kpId   = (int) $row['karyawan_permit_id'];
        $amount = (int) $row['amount_date'];

        if (!mysql_query("UPDATE permit_usage SET is_false_entry = 1 WHERE id = {$id}", $this->conn)) {
            $err = mysql_error($this->conn);
            mysql_query("ROLLBACK", $this->conn);
            return 'Gagal memperbarui penggunaan: ' . $err;
        }

        if (!mysql_query("UPDATE karyawan_permit SET days_amount = days_amount + {$amount} WHERE id = {$kpId}", $this->conn)) {
            $err = mysql_error($this->conn);
            mysql_query("ROLLBACK", $this->conn);
            return 'Gagal mengembalikan kuota: ' . $err;
        }

        if (!mysql_query("COMMIT", $this->conn)) {
            $err = mysql_error($this->conn);
            mysql_query("ROLLBACK", $this->conn);
            return 'Gagal commit transaksi: ' . $err;
        }

        return true;
    }
}
