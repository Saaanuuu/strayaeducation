<?php
require 'koneksi.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Mulai session
session_start();

// Ambil data dari POST
$fullName = mysqli_real_escape_string($conn, $_POST['fullName']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
$address = mysqli_real_escape_string($conn, $_POST['address']);
$waktuProgram = mysqli_real_escape_string($conn, $_POST['waktuProgram']);
$paymentMethod = mysqli_real_escape_string($conn, $_POST['paymentMethod']);
$idProgram = intval($_POST['idProgram']);

// Validasi data
if (empty($fullName) || empty($email) || empty($phone) || empty($address) || empty($waktuProgram) || empty($paymentMethod)) {
    die("Semua field harus diisi!");
}

// Mulai transaksi
mysqli_begin_transaction($conn);

try {
    // 1. Cek atau buat user
    $checkUserQuery = "SELECT id FROM t_user WHERE email = '$email'";
    $userResult = mysqli_query($conn, $checkUserQuery);

    if (mysqli_num_rows($userResult) > 0) {
        $userData = mysqli_fetch_assoc($userResult);
        $idUser = $userData['id'];
    } else {
        $insertUserQuery = "INSERT INTO t_user (fullName, email, role) VALUES ('$fullName', '$email', '2')";
        if (mysqli_query($conn, $insertUserQuery)) {
            $idUser = mysqli_insert_id($conn);
        } else {
            throw new Exception("Gagal membuat user baru: " . mysqli_error($conn));
        }
    }

    // 2. Ambil data program
    $programQuery = "SELECT paketProgram, kuotaProgram, waktuProgram FROM t_program WHERE idProgram = $idProgram FOR UPDATE";
    $programResult = mysqli_query($conn, $programQuery);

    if (!$programResult || mysqli_num_rows($programResult) == 0) {
        throw new Exception("Program tidak ditemukan");
    }

    $programData = mysqli_fetch_assoc($programResult);
    $pakets = json_decode($programData['paketProgram'], true);
    $kuotas = json_decode($programData['kuotaProgram'], true);
    $waktus = json_decode($programData['waktuProgram'], true);

    // 3. Cari indeks paket yang sesuai
    $paketIndex = null;
    foreach ($waktus as $index => $waktuOptions) {
        if (in_array($waktuProgram, $waktuOptions)) {
            $paketIndex = $index;
            break;
        }
    }

    if ($paketIndex === null) {
        throw new Exception("Waktu program tidak valid");
    }

    // 4. Validasi kuota
    if ($kuotas[$paketIndex] <= 0) {
        throw new Exception("Maaf, kuota untuk program ini sudah habis");
    }

    // 5. Kurangi kuota
    $kuotas[$paketIndex]--;
    $newKuota = json_encode($kuotas);

    // 6. Update kuota
    $updateKuotaQuery = "UPDATE t_program SET kuotaProgram = '" . mysqli_real_escape_string($conn, $newKuota) . "' WHERE idProgram = $idProgram";
    if (!mysqli_query($conn, $updateKuotaQuery)) {
        throw new Exception("Gagal update kuota: " . mysqli_error($conn));
    }

    // 7. Simpan pendaftaran
    $query = "INSERT INTO pendaftaran 
              (idProgram, fullName, email, phone, address, waktuProgram, paymentMethod, tanggalDaftar, idUser) 
              VALUES 
              ('$idProgram', '$fullName', '$email', '$phone', '$address', '$waktuProgram', '$paymentMethod', NOW(), '$idUser')";

    if (!mysqli_query($conn, $query)) {
        throw new Exception("Gagal mendaftar: " . mysqli_error($conn));
    }

    // Commit transaksi jika semua berhasil
    mysqli_commit($conn);

    // Set session
    $_SESSION['id'] = $idUser;
    $_SESSION['email'] = $email;
    $_SESSION['fullName'] = $fullName;
    $_SESSION['role'] = '2';
    $_SESSION['login'] = true;

    echo "Pendaftaran diproses! \nSilahkan lanjutkan ke menu Pembayaran\n\nMetode pembayaran: $paymentMethod";

} catch (Exception $e) {
    // Rollback jika ada error
    mysqli_rollback($conn);
    echo $e->getMessage();
}

mysqli_close($conn);
?>