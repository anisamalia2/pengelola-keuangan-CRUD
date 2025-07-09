<?php
$host = "localhost";
$user = "root";
$pass = "stlcf.l1ans";
$db   = "db_keuanganpribadi";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
