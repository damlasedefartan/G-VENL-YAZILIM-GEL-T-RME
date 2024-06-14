<?php
$servername = "localhost";
$username = "root";
$password = "abc123";
$dbname = "ayakkabici";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
}
?>
