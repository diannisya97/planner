<?php
$host = "localhost";
$user = "data"; // ganti jika perlu
$pass = "kaskus2727";     // ganti jika ada password
$db   = "content_planner_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>