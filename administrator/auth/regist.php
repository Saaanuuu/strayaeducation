<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../assets/img/straya.png" rel="icon" />
    <title>Registrasi - STRAYA LANGUAGE INSTITUTE</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row border rounded-5 p-3 bg-white shadow box-area text-center">
            <div class="col-12 d-flex flex-column justify-content-center align-items-center">
                <img src="../../assets/img/straya.png" class="img-fluid mb-3" style="width: 200px;">
                <form method="POST" action="check_login.php" class="w-100 px-4">
                    <div class="mb-3">
                        <input type="text" name="fullName" class="form-control form-control-lg bg-light" placeholder="Nama Lengkap" required>
                    </div>
                    <div class="mb-3">
                        <input type="email" name="email" class="form-control form-control-lg bg-light" placeholder="Email address" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="password" class="form-control form-control-lg bg-light" placeholder="Password" required>
                    </div>
                    <input type="hidden" name="role" value="2">
                    <button type="submit" name="register" class="btn btn-lg btn-primary w-100">Register</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
