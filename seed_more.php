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
mysql_select_db($database, $conn);

$map = array();
$res = mysql_query("SELECT id, division_name FROM division", $conn);
while ($r = mysql_fetch_assoc($res)) {
    $map[$r['division_name']] = (int) $r['id'];
}

$rows = array(
    array($map['IT'], 'rina.k', 'password123', 'rina.kusuma@example.com', 'Rina Kusuma', '3201010101010005', 'Bogor', 'P'),
    array($map['HRD'], 'dedi.p', 'password123', 'dedi.pratama@example.com', 'Dedi Pratama', '3201010101010006', 'Bandung', 'L'),
    array($map['Finance'], 'maya.s', 'password123', 'maya.sari@example.com', 'Maya Sari', '3201010101010007', 'Surabaya', 'P'),
    array($map['Marketing'], 'eko.n', 'password123', 'eko.nugroho@example.com', 'Eko Nugroho', '3201010101010008', 'Semarang', 'L'),
    array($map['IT'], 'fitri.h', 'password123', 'fitri.handayani@example.com', 'Fitri Handayani', '3201010101010009', 'Yogyakarta', 'P'),
);
foreach ($rows as $k) {
    $division_id = (int) $k[0];
    $username    = mysql_real_escape_string($k[1], $conn);
    $pass        = mysql_real_escape_string($k[2], $conn);
    $email       = mysql_real_escape_string($k[3], $conn);
    $fullname    = mysql_real_escape_string($k[4], $conn);
    $nik         = mysql_real_escape_string($k[5], $conn);
    $address     = mysql_real_escape_string($k[6], $conn);
    $gender      = mysql_real_escape_string($k[7], $conn);
    $sql = "INSERT IGNORE INTO karyawan (division_id, username, password, email, fullname, NIK, address, gender)
            VALUES ({$division_id}, '{$username}', '{$pass}', '{$email}', '{$fullname}', '{$nik}', '{$address}', '{$gender}')";
    if (!mysql_query($sql, $conn)) {
        die('Insert failed: ' . mysql_error() . PHP_EOL);
    }
}
echo "Inserted 5 karyawan." . PHP_EOL;
mysql_close($conn);
