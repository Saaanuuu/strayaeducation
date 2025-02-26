<?php
$conn = mysqli_connect('localhost','root','','straya');

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>