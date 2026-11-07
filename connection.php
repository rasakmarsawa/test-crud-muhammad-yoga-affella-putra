<?php
$host     = getenv('DB_HOST') ? getenv('DB_HOST') : 'db';
$port     = getenv('DB_PORT') ? getenv('DB_PORT') : '3306';
$username = getenv('DB_USER') ? getenv('DB_USER') : 'app';
$password = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : 'app';
$database = getenv('DB_NAME') ? getenv('DB_NAME') : 'test_crud';

$server = $host . ':' . $port;
$conn = mysql_connect($server, $username, $password);
if (!$conn) {
    die('Connection failed: ' . mysql_error());
}

mysql_query("SET NAMES 'utf8'", $conn);

$db_selected = mysql_select_db($database, $conn);
if (!$db_selected) {
    die('Database selection failed: ' . mysql_error());
}

mysql_set_charset('utf8', $conn);
?>