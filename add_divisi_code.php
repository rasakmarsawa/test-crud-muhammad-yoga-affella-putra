<?php
$host     = getenv('DB_HOST') ? getenv('DB_HOST') : 'db';
$port     = getenv('DB_PORT') ? getenv('DB_PORT') : '3306';
$username = getenv('DB_USER') ? getenv('DB_USER') : 'app';
$password = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : 'app';
$database = getenv('DB_NAME') ? getenv('DB_NAME') : 'test_crud';

$server = $host . ':' . $port;
$conn = mysql_connect($server, $username, $password);
if (!$conn) {
    die('Connection failed: ' . mysql_error() . PHP_EOL);
}
mysql_query("SET NAMES 'utf8'", $conn);
mysql_set_charset('utf8', $conn);

if (!mysql_select_db($database, $conn)) {
    die('Database selection failed: ' . mysql_error() . PHP_EOL);
}

$db  = mysql_real_escape_string($database, $conn);
$chk = mysql_query(
    "SELECT COUNT(*) AS c FROM information_schema.COLUMNS
     WHERE TABLE_SCHEMA = '{$db}' AND TABLE_NAME = 'division' AND COLUMN_NAME = 'divisi_code'",
    $conn
);
if (!$chk) {
    die('Check failed: ' . mysql_error() . PHP_EOL);
}
$row = mysql_fetch_assoc($chk);
if ((int) $row['c'] > 0) {
    echo "Column `divisi_code` already exists in `division`." . PHP_EOL;
    mysql_close($conn);
    exit(0);
}

$sql = "ALTER TABLE division ADD COLUMN divisi_code VARCHAR(5) NULL AFTER division_name";
if (!mysql_query($sql, $conn)) {
    die('Alter failed: ' . mysql_error() . PHP_EOL);
}

echo "Column `divisi_code` VARCHAR(5) added to `division`." . PHP_EOL;
mysql_close($conn);
