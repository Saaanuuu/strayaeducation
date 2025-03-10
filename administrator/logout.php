<?php
session_start();

$role = $_SESSION['role'] ?? null;

session_unset();
session_destroy();

if ($role == 1) {
    header("Location: auth/login.php");
} else {
    header("Location: ../index.php");
}
exit;
