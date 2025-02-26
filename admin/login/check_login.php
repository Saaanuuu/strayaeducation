<?php
session_start();
include "../../koneksi.php";

if (empty($_POST['email']) || empty($_POST['password'])) {
    header("location: login.php?alert=empty");
    exit;
}

$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = $_POST['password'];

$query = "SELECT * FROM t_user WHERE email='$email'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if ($data) {
    if (strlen($data['password']) < 60) {
        $hashed_password = password_hash($data['password'], PASSWORD_BCRYPT);
        $update_query = "UPDATE t_user SET password='$hashed_password' WHERE email='$email'";
        mysqli_query($conn, $update_query);
        $data['password'] = $hashed_password;
    }
    if (password_verify($password, $data['password'])) {
        $_SESSION['email'] = $data['email'];
        $_SESSION['fullName'] = $data['fullName'];
        $_SESSION['login'] = true;
        header("location: ../index.php");
        exit;
    } else {
        header("location: login.php?alert=gagal");
        exit;
    }
} else {
    header("location: login.php?alert=gagal");
    exit;
}
