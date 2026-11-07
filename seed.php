<?php
// Seed script - uses same credentials/defaults as connection.php
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

$divisions = array(
    array('IT', 'Information Technology'),
    array('HRD', 'Human Resources'),
    array('Finance', 'Finance and Accounting'),
    array('Marketing', 'Marketing and Sales'),
);
foreach ($divisions as $d) {
    $name = mysql_real_escape_string($d[0], $conn);
    $desc = mysql_real_escape_string($d[1], $conn);
    $sql  = "INSERT IGNORE INTO division (division_name, description) VALUES ('{$name}', '{$desc}')";
    if (!mysql_query($sql, $conn)) {
        die('Seed division failed: ' . mysql_error() . PHP_EOL);
    }
}
echo "Divisions seeded." . PHP_EOL;

$map = array();
$res = mysql_query("SELECT id, division_name FROM division", $conn);
while ($r = mysql_fetch_assoc($res)) {
    $map[$r['division_name']] = (int) $r['id'];
}

$karyawan = array(
    array($map['IT'], 'budi.s', 'password123', 'budi.santoso@example.com', 'Budi Santoso', '3201010101010001', 'Jakarta Selatan', 'L'),
    array($map['HRD'], 'siti.a', 'password123', 'siti.aminah@example.com', 'Siti Aminah', '3201010101010002', 'Depok', 'P'),
    array($map['Finance'], 'agus.w', 'password123', 'agus.wijaya@example.com', 'Agus Wijaya', '3201010101010003', 'Bekasi', 'L'),
    array($map['Marketing'], 'dewi.l', 'password123', 'dewi.lestari@example.com', 'Dewi Lestari', '3201010101010004', 'Tangerang', 'P'),
);
foreach ($karyawan as $k) {
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
        die('Seed karyawan failed: ' . mysql_error() . PHP_EOL);
    }
}
echo "Karyawan seeded." . PHP_EOL;
mysql_close($conn);
