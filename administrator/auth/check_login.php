<?php
session_start();
include "../../koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        if (empty($_POST['email']) || empty($_POST['password'])) {
            header("location: login.php?alert=empty");
            exit();
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
                $_SESSION['role'] = $data['role'];
                $_SESSION['login'] = true;

                if ($data['role'] == "1") {
                    header("location: ../index.php");
                } else {
                    header("location: ../../index.php");
                }
                exit();
            } else {
                header("location: login.php?alert=gagal");
                exit();
            }
        } else {
            header("location: login.php?alert=gagal");
            exit();
        }
    } elseif (isset($_POST['register'])) {
        $fullName = mysqli_real_escape_string($conn, $_POST['fullName']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $role = "2";

        $check_email = mysqli_query($conn, "SELECT * FROM t_user WHERE email='$email'");
        if (mysqli_num_rows($check_email) > 0) {
            header("Location: regist.php?alert=email_exists");
            exit();
        }

        $query = "INSERT INTO t_user (fullName, email, password, role) VALUES ('$fullName', '$email', '$password', '$role')";
        if (mysqli_query($conn, $query)) {
            header("Location: login.php?alert=registered");
            exit();
        } else {
            header("Location: regist.php?alert=error");
            exit();
        }
    }
}
