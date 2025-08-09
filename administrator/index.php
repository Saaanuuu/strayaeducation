<?php
include '../koneksi.php';

// Aktifkan error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] != "1") {
    header("Location: login.php?alert=not_logged_in");
    exit;
}

// Handle Admin CRUD Operations
if (isset($_POST['save'])) {
    $email = htmlspecialchars($_POST['email']);
    $fullName = htmlspecialchars($_POST['fullName']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = "1";

    $SQL = "INSERT INTO t_user (email, fullName, password, role) 
            VALUES ('$email', '$fullName', '$password', '$role')";

    if (mysqli_query($conn, $SQL)) {
        header("Location: index.php#manage");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $SQL . "<br>" . mysqli_error($conn);
    }
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $email = htmlspecialchars($_POST['email']);
    $fullName = htmlspecialchars($_POST['fullName']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $SQL = "UPDATE t_user SET 
            email='$email', 
            fullName='$fullName', 
            password='$password'
            WHERE id='$id'";

    if (mysqli_query($conn, $SQL)) {
        header("Location: index.php#manage");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $SQL . "<br>" . mysqli_error($conn);
    }
}

if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $SQL = "DELETE FROM t_user WHERE id='$id'";

    if (mysqli_query($conn, $SQL)) {
        header("Location: index.php#manage");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $SQL . "<br>" . mysqli_error($conn);
    }
}

// Handle Status Pendaftaran Update
if (isset($_GET['action']) && $_GET['action'] == 'update_status') {
    $idPendaftaran = intval($_GET['id']);
    $status = mysqli_real_escape_string($conn, $_GET['status']);

    $updateQuery = "UPDATE pendaftaran SET statusPendaftaran = '$status' WHERE idPendaftaran = $idPendaftaran";

    if (mysqli_query($conn, $updateQuery)) {
        $_SESSION['success'] = "Status pendaftaran diupdate!";
    } else {
        $_SESSION['error'] = "Gagal update status: " . mysqli_error($conn);
    }

    header("Location: index.php#pendaftaran");
    exit();
}

// Handle Delete Pendaftaran
if (isset($_GET['action']) && $_GET['action'] == 'delete_pendaftaran') {
    $idPendaftaran = intval($_GET['id']);

    $deleteQuery = "DELETE FROM pendaftaran WHERE idPendaftaran = $idPendaftaran";

    if (mysqli_query($conn, $deleteQuery)) {
        $_SESSION['success'] = "Data pendaftaran dihapus!";
    } else {
        $_SESSION['error'] = "Gagal menghapus data: " . mysqli_error($conn);
    }

    header("Location: index.php#pendaftaran");
    exit();
}

// Handle Score Update
if (isset($_POST['update_score'])) {
    $idPendaftaran = intval($_POST['idPendaftaran']);
    $score = $_POST['score'] ?? '-';
    $scoreBefore = $_POST['scoreBefore'] ?? '-';

    // Get program name for this registration
    $programQuery = mysqli_query($conn, "
        SELECT pr.namaProgram 
        FROM pendaftaran p 
        JOIN t_program pr ON p.idProgram = pr.idProgram
        WHERE p.idPendaftaran = $idPendaftaran
    ");
    $programData = mysqli_fetch_assoc($programQuery);
    $programName = $programData['namaProgram'] ?? '';

    // Validate scoreBefore if it's not '-'
    if ($scoreBefore !== '-') {
        $isToefl = stripos($programName, 'TOEFL') !== false;
        $isIelts = stripos($programName, 'IELTS') !== false;

        if ($isToefl) {
            $scoreBefore = intval($scoreBefore);
            if ($scoreBefore < 0 || $scoreBefore > 600) {
                $_SESSION['error'] = "TOEFL score awal harus diantara 0 - 600";
                header("Location: index.php#pendaftaran");
                exit;
            }
        } elseif ($isIelts) {
            $scoreBefore = floatval($scoreBefore);
            if ($scoreBefore < 0 || $scoreBefore > 9.0) {
                $_SESSION['error'] = "IELTS score awal harus diantara 0.0 - 9.0";
                header("Location: index.php#pendaftaran");
                exit;
            }
            $scoreBefore = round($scoreBefore, 1);
        } else {
            $scoreBefore = floatval($scoreBefore);
            if ($scoreBefore < 0 || $scoreBefore > 100) {
                $_SESSION['error'] = "Score awal harus diantara 0 - 100";
                header("Location: index.php#pendaftaran");
                exit;
            }
        }
    }

    // Validate score if it's not '-'
    if ($score !== '-') {
        $isToefl = stripos($programName, 'TOEFL') !== false;
        $isIelts = stripos($programName, 'IELTS') !== false;

        if ($isToefl) {
            $score = intval($score);
            if ($score < 0 || $score > 600) {
                $_SESSION['error'] = "TOEFL score harus diantara 0 - 600";
                header("Location: index.php#pendaftaran");
                exit;
            }
        } elseif ($isIelts) {
            $score = floatval($score);
            if ($score < 0 || $score > 9.0) {
                $_SESSION['error'] = "IELTS score harus diantara 0.0 - 9.0";
                header("Location: index.php#pendaftaran");
                exit;
            }
            $score = round($score, 1);
        } else {
            $score = floatval($score);
            if ($score < 0 || $score > 100) {
                $_SESSION['error'] = "Score harus diantara 0 - 100";
                header("Location: index.php#pendaftaran");
                exit;
            }
        }
    }

    $updateQuery = "UPDATE pendaftaran SET score = '$score', scoreBefore = '$scoreBefore' WHERE idPendaftaran = $idPendaftaran";

    if (mysqli_query($conn, $updateQuery)) {
        $_SESSION['success'] = "Score diupdate!";
    } else {
        $_SESSION['error'] = "Gagal update score: " . mysqli_error($conn);
    }

    header("Location: index.php#pendaftaran");
    exit();
}

// Query data
$queryAdmin = mysqli_query($conn, "SELECT * FROM t_user WHERE role = '1'");
$queryPendaftaran = mysqli_query($conn, "
    SELECT p.*, pr.namaProgram 
    FROM pendaftaran p 
    JOIN t_program pr ON p.idProgram = pr.idProgram
    ORDER BY p.tanggalDaftar DESC
");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>STRAYA LANGUAGE INSTITUTE</title>
    <meta name="description" content="" />
    <meta name="keywords" content="" />

    <!-- Favicons -->
    <link href="../assets/img/straya.png" rel="icon" />
    <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon" />

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Vendor CSS Files -->
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet" />
    <link href="../assets/vendor/aos/aos.css" rel="stylesheet" />
    <link href="../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet" />
    <link href="../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet" />

    <!-- Main CSS File -->
    <link href="../assets/css/main.css" rel="stylesheet" />
</head>

<body class="index-page">
    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="container-fluid container-xl position-relative d-flex align-items-center">
            <a href="../administrator/index.php" class="logo d-flex align-items-center me-auto">
                <img src="../assets/img/straya.png" alt="" />
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="index.php#hero">Home</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="manageDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Manage
                        </a>
                        <ul class="dropdown-menu-end" aria-labelledby="manageDropdown">
                            <li><a class="dropdown-item" href="index.php#manage">Manage User</a></li>
                            <li><a class="dropdown-item" href="index.php#pendaftaran">Pendaftaran</a></li>
                            <li><a class="dropdown-item" href="index.php#presensi">Presensi</a></li>
                        </ul>
                    </li>
                    <li><a href="../administrator/beasiswa/viewBeasiswa.php">Beasiswa</a></li>
                    <li><a href="../administrator/program/viewProgram.php">Program</a></li>
                    <li class="nav-item dropdown d-flex align-items-center">
                        <span class="me-2 fw-bold text-white">Hello, <?= $_SESSION["fullName"] ?></span>
                        <div class="profile-picture bg-light" id="userDropdown">
                            <i class="fas fa-user"></i>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end" id="dropdownMenu">
                            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>
                                    Logout</a></li>
                        </ul>
                    </li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>
        </div>
    </header>

    <main class="main">

        <!-- Hero Section -->
        <section id="hero" class="hero section dark-background">
            <img src="../assets/img/hero-bg.jpg" alt="" data-aos="fade-in" />

            <div class="container d-flex flex-column align-items-center">
                <h2 data-aos="fade-up" data-aos-delay="100">STRAYA LANGUAGE INSTITUTE</h2>
                <p data-aos="fade-up" data-aos-delay="200">
                    Everyone Can Get Scholarship
                </p>
            </div>
        </section>
        <!-- /Hero Section -->

        <!-- Manage User Section -->
        <section id="manage" class="manage section">
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <?php
                                    echo '<div class="col-md-8">';
                                    echo '<h4 class="card-title">List Admin</h4>';
                                    echo '</div>';
                                    echo '<div class="col-md-4 d-flex justify-content-end">';
                                    echo '<button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalAddAdmin">';
                                    echo '<i class="fa fa-plus"></i>';
                                    echo '&nbsp';
                                    echo 'Add New Admin';
                                    echo '</button>';
                                    echo '</div>';
                                    ?>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="add-row" class="display table table-striped table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;" width="5%">No</th>
                                            <th style="text-align: center;">Full Name</th>
                                            <th style="text-align: center;">Email</th>
                                            <th style="text-align: center;" width="8.3%">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $query = mysqli_query($conn, "SELECT * FROM t_user WHERE role = '1'");

                                        while ($t_user = mysqli_fetch_array($query)) {
                                            ?>
                                            <tr>
                                                <td style="text-align: center;"><?php echo $no++; ?></td>
                                                <td><?php echo $t_user['fullName']; ?></td>
                                                <td><?php echo $t_user['email']; ?></td>
                                                <td>
                                                    <a href="#modalEditAdmin<?php echo $t_user['id']; ?>"
                                                        data-bs-toggle="modal" title="Edit"
                                                        class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a>
                                                    <a href="#modalDeleteAdmin<?php echo $t_user['id']; ?>"
                                                        data-bs-toggle="modal" title="Hapus"
                                                        class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /Manage User Section -->

        <!-- Pendaftaran Section -->
        <section id="pendaftaran" class="pendaftaran section">
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <!-- Alert Messages -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <?= $_SESSION['success'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <?= $_SESSION['error'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Data Pendaftaran User</h4>
                            </div>
                            <div class="card-body">
                                <table class="display table table-striped table-hover table-bordered">
                                    <thead class="text-center">
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>Telepon</th>
                                            <th>Alamat</th>
                                            <th>Program</th>
                                            <th>Waktu</th>
                                            <th>Payment</th>
                                            <th>Tanggal Daftar</th>
                                            <th>Bukti Bayar</th>
                                            <th width="1%">Score Awal</th>
                                            <th width="1%">Score Akhir</th>
                                            <th width="0.5%">Status</th>
                                            <th width="12%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        while ($row = mysqli_fetch_assoc($queryPendaftaran)): ?>
                                            <tr>
                                                <td class="text-center"><?= $no++ ?></td>
                                                <td><?= htmlspecialchars($row['fullName']) ?></td>
                                                <td><?= htmlspecialchars($row['email']) ?></td>
                                                <td><?= htmlspecialchars($row['phone']) ?></td>
                                                <td><?= htmlspecialchars($row['address']) ?></td>
                                                <td><?= htmlspecialchars($row['namaProgram']) ?></td>
                                                <td><?= htmlspecialchars($row['waktuProgram']) ?></td>
                                                <td><?= htmlspecialchars($row['paymentMethod']) ?></td>
                                                <td><?= htmlspecialchars($row['tanggalDaftar']) ?></td>
                                                <td class="text-center">
                                                    <?php if (!empty($row['buktiBayar'])):
                                                        $filePath = "../assets/img/bukti_bayar/" . htmlspecialchars($row['buktiBayar']);
                                                        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

                                                        if (file_exists($filePath) && in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                                            <a href="#" data-bs-toggle="modal"
                                                                data-bs-target="#imageModal<?= $row['idPendaftaran'] ?>">
                                                                <img src="<?= $filePath ?>" style="max-height:50px;"
                                                                    class="img-thumbnail">
                                                            </a>

                                                            <!-- Modal for image preview -->
                                                            <div class="modal fade" id="imageModal<?= $row['idPendaftaran'] ?>"
                                                                tabindex="-1" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">Bukti Pembayaran</h5>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body text-center">
                                                                            <img src="<?= $filePath ?>" class="img-fluid">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php elseif (file_exists($filePath)): ?>
                                                            <a href="<?= $filePath ?>" target="_blank"
                                                                class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-file-download"></i> Lihat
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="badge bg-secondary">Tidak ada</span>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Tidak ada</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <div
                                                        class="d-flex flex-column align-items-center justify-content-center gap-1">
                                                        <?php
                                                        $scoreBefore = $row['scoreBefore'] ?? '-';
                                                        $scoreBeforeClass = 'secondary';

                                                        if ($scoreBefore !== '-') {
                                                            $isToefl = stripos($row['namaProgram'], 'TOEFL') !== false;
                                                            $isIelts = stripos($row['namaProgram'], 'IELTS') !== false;

                                                            if ($isToefl) {
                                                                if ($scoreBefore >= 500) {
                                                                    $scoreBeforeClass = 'success';
                                                                } elseif ($scoreBefore >= 400) {
                                                                    $scoreBeforeClass = 'warning';
                                                                } else {
                                                                    $scoreBeforeClass = 'danger';
                                                                }
                                                            } elseif ($isIelts) {
                                                                if ($scoreBefore >= 6.5) {
                                                                    $scoreBeforeClass = 'success';
                                                                } elseif ($scoreBefore >= 5.5) {
                                                                    $scoreBeforeClass = 'warning';
                                                                } else {
                                                                    $scoreBeforeClass = 'danger';
                                                                }
                                                            } else {
                                                                if ($scoreBefore >= 80) {
                                                                    $scoreBeforeClass = 'success';
                                                                } elseif ($scoreBefore >= 60) {
                                                                    $scoreBeforeClass = 'warning';
                                                                } else {
                                                                    $scoreBeforeClass = 'danger';
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        <span class="badge bg-<?= $scoreBeforeClass ?>">
                                                            <?= htmlspecialchars($scoreBefore) ?>
                                                        </span>
                                                        <a href="#modalEditScoreBefore<?= htmlspecialchars($row['idPendaftaran']) ?>"
                                                            data-bs-toggle="modal" class="btn btn-sm btn-primary"
                                                            title="Edit Score Awal">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div
                                                        class="d-flex flex-column align-items-center justify-content-center gap-1">
                                                        <?php
                                                        $score = $row['score'] ?? '-';
                                                        $scoreClass = 'secondary';

                                                        if ($score !== '-') {
                                                            $isToefl = stripos($row['namaProgram'], 'TOEFL') !== false;
                                                            $isIelts = stripos($row['namaProgram'], 'IELTS') !== false;

                                                            if ($isToefl) {
                                                                if ($score >= 500) {
                                                                    $scoreClass = 'success';
                                                                } elseif ($score >= 400) {
                                                                    $scoreClass = 'warning';
                                                                } else {
                                                                    $scoreClass = 'danger';
                                                                }
                                                            } elseif ($isIelts) {
                                                                if ($score >= 6.5) {
                                                                    $scoreClass = 'success';
                                                                } elseif ($score >= 5.5) {
                                                                    $scoreClass = 'warning';
                                                                } else {
                                                                    $scoreClass = 'danger';
                                                                }
                                                            } else {
                                                                if ($score >= 80) {
                                                                    $scoreClass = 'success';
                                                                } elseif ($score >= 60) {
                                                                    $scoreClass = 'warning';
                                                                } else {
                                                                    $scoreClass = 'danger';
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        <span class="badge bg-<?= $scoreClass ?>">
                                                            <?= htmlspecialchars($score) ?>
                                                        </span>
                                                        <a href="#modalEditScore<?= htmlspecialchars($row['idPendaftaran']) ?>"
                                                            data-bs-toggle="modal" class="btn btn-sm btn-primary"
                                                            title="Edit Score">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-<?=
                                                        ($row['statusPendaftaran'] == 'Diterima') ? 'success' :
                                                        (($row['statusPendaftaran'] == 'Ditolak') ? 'danger' : 'warning')
                                                        ?>">
                                                        <?= htmlspecialchars($row['statusPendaftaran'] ?? 'Belum Diproses') ?>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group" role="group">
                                                        <a href="?action=update_status&id=<?= $row['idPendaftaran'] ?>&status=Diterima"
                                                            class="btn btn-success btn-sm"
                                                            onclick="return confirm('Setujui pendaftaran?')">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                        <a href="?action=update_status&id=<?= $row['idPendaftaran'] ?>&status=Ditolak"
                                                            class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Tolak pendaftaran?')">
                                                            <i class="fas fa-times"></i>
                                                        </a>
                                                        <a href="?action=delete_pendaftaran&id=<?= $row['idPendaftaran'] ?>"
                                                            class="btn btn-dark btn-sm"
                                                            onclick="return confirm('Hapus data pendaftaran?')">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Presensi Section -->
        <section id="presensi" class="presensi section">
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h4 class="card-title">Presensi</h4>
                                    </div>
                                    <div class="col-md-4 d-flex justify-content-end">
                                        <!-- Filter Dropdown -->
                                        <form method="GET" action="#presensi">
                                            <select class="form-select" name="filter_program"
                                                onchange="this.form.submit()">
                                                <option value="">Pilih Program</option>
                                                <?php
                                                // Query untuk mendapatkan semua program yang memiliki pendaftar
                                                $programQuery = mysqli_query($conn, "
                                                    SELECT DISTINCT pr.namaProgram, 
                                                        CASE 
                                                            WHEN pd.waktuProgram IN ('15.00 WITA', '17.00 WITA', '14.00 WITA', '16.00 WITA') THEN '24' 
                                                            ELSE '8' 
                                                        END AS paket
                                                    FROM pendaftaran pd
                                                    JOIN t_program pr ON pd.idProgram = pr.idProgram
                                                    WHERE pd.statusPendaftaran = 'Diterima'
                                                    GROUP BY pr.namaProgram, paket
                                                    ORDER BY pr.namaProgram, paket
                                                ");

                                                $currentProgram = "";
                                                while ($program = mysqli_fetch_assoc($programQuery)) {
                                                    $paket = $program['paket'];

                                                    if ($program['namaProgram'] != $currentProgram) {
                                                        if ($currentProgram != "") {
                                                            echo '</optgroup>';
                                                        }
                                                        echo '<optgroup label="' . htmlspecialchars($program['namaProgram']) . '">';
                                                        $currentProgram = $program['namaProgram'];
                                                    }

                                                    $value = htmlspecialchars($program['namaProgram']) . '_' . $paket;
                                                    $selected = isset($_GET['filter_program']) && $_GET['filter_program'] == $value ? 'selected' : '';
                                                    $label = htmlspecialchars($program['namaProgram']) . ' (' . $paket . ' Pertemuan)';

                                                    echo '<option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
                                                }
                                                if ($currentProgram != "") {
                                                    echo '</optgroup>';
                                                }
                                                ?>
                                            </select>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($_GET['filter_program'])): ?>
                                    <div style="overflow-x: auto;">
                                        <table class="table table-striped table-hover table-bordered">
                                            <thead class="text-center">
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th>Nama</th>
                                                    <th>Program</th>
                                                    <th>Paket</th>
                                                    <th>Waktu</th>
                                                    <th>Cetak</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Query to fetch presensi data
                                                if (!empty($_GET['filter_program'])) {
                                                    $filterParts = explode('_', $_GET['filter_program']);
                                                    $programName = $filterParts[0];
                                                    $meetingCount = $filterParts[1];

                                                    $isExclusiveFilter = $meetingCount == '24';

                                                    $programCondition = "AND pr.namaProgram = '" . mysqli_real_escape_string($conn, $programName) . "'";

                                                    if ($isExclusiveFilter) {
                                                        $programCondition .= " AND pd.waktuProgram IN ('15.00 WITA', '17.00 WITA', '14.00 WITA', '16.00 WITA')";
                                                    } else {
                                                        $programCondition .= " AND pd.waktuProgram NOT IN ('15.00 WITA', '17.00 WITA', '14.00 WITA', '16.00 WITA')";
                                                    }
                                                }

                                                $query = "
                                            SELECT DISTINCT pd.idPendaftaran, pd.fullName, pr.namaProgram, pd.waktuProgram, pr.paketProgram
                                            FROM pendaftaran pd
                                            JOIN t_program pr ON pd.idProgram = pr.idProgram
                                            WHERE pd.statusPendaftaran = 'Diterima' $programCondition
                                            ORDER BY pd.fullName
                                        ";
                                                $result = mysqli_query($conn, $query);
                                                $no = 1;
                                                $users = []; // Store users for bulk print
                                            
                                                while ($row = mysqli_fetch_assoc($result)):
                                                    // Determine package type and number of meetings
                                                    $isExclusive = in_array($row['waktuProgram'], ['15.00 WITA', '17.00 WITA', '14.00 WITA', '16.00 WITA']);
                                                    $paket = $isExclusive ? 'Exclusive (24 Pertemuan)' : 'Regular (8 Pertemuan)';
                                                    $jumlahPertemuan = $isExclusive ? 24 : 8;

                                                    // Store user data for bulk print
                                                    $users[] = [
                                                        'nama' => $row['fullName'],
                                                        'program' => $row['namaProgram'],
                                                        'paket' => $paket,
                                                        'waktu' => $row['waktuProgram'],
                                                        'jumlahPertemuan' => $jumlahPertemuan
                                                    ];
                                                    ?>
                                                    <tr>
                                                        <td class="text-center"><?= $no++ ?></td>
                                                        <td><?= htmlspecialchars($row['fullName']) ?></td>
                                                        <td><?= htmlspecialchars($row['namaProgram']) ?></td>
                                                        <td><?= $paket ?></td>
                                                        <td><?= htmlspecialchars($row['waktuProgram']) ?></td>
                                                        <td class="text-center">
                                                            <button class="btn btn-sm btn-primary cetak-presensi" onclick="cetakPresensi(
                                                                    '<?= htmlspecialchars($row['fullName']) ?>',
                                                                    '<?= htmlspecialchars($row['namaProgram']) ?>',
                                                                    '<?= $paket ?>',
                                                                    '<?= htmlspecialchars($row['waktuProgram']) ?>',
                                                                    <?= $jumlahPertemuan ?>
                                                                )">
                                                                <i class="fas fa-print"></i> Cetak
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                                <?php if (mysqli_num_rows($result) === 0): ?>
                                                    <tr>
                                                        <td colspan="6" class="text-center">Tidak ada data presensi untuk
                                                            program ini.
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>

                                        <?php if (!empty($users)): ?>
                                            <div class="text-center mt-3">
                                                <button class="btn btn-success"
                                                    onclick="cetakKeseluruhan(<?= htmlspecialchars(json_encode($users)) ?>)">
                                                    <i class="fas fa-print"></i> Cetak Keseluruhan
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-info text-center">
                                        Silahkan filter program untuk melihat data presensi.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Modal Add New Admin -->
    <div class="modal fade" id="modalAddAdmin" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header no-bd">
                    <h5 class="modal-title">Add New Admin</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="fullName" id="fullName" class="form-control"
                                placeholder="Full Name" autocomplete="off" required="">
                        </div>
                        <br>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" name="email" id="email" class="form-control" placeholder="Email"
                                autocomplete="off" required="">
                        </div>
                        <br>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Password" autocomplete="off" required="">
                        </div>
                    </div>
                    <div class="modal-footer no-bd">
                        <button type="submit" name="save" class="btn btn-primary"><i
                                class="fa fa-save"></i>Save</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-undo"></i>
                            Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal Add New Admin -->

    <!-- Modal Edit Admin -->
    <?php
    $p = mysqli_query($conn, 'SELECT * from t_user');
    while ($d = mysqli_fetch_array($p)) {
        ?>
        <div class="modal fade" id="modalEditAdmin<?php echo $d['id'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header no-bd">
                        <h5 class="modal-title">Edit Admin</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                            <input type="hidden" name="id" value="<?php echo $d['id'] ?>">

                            <div class="form-group">
                                <label>Full Name</label>
                                <input value="<?php echo $d['fullName'] ?>" type="text" name="fullName"
                                    id="fullName_<?php echo $d['id'] ?>" class="form-control" placeholder="Full Name"
                                    autocomplete="off" required="">
                            </div>
                            <br>
                            <div class="form-group">
                                <label>Email</label>
                                <input value="<?php echo $d['email'] ?>" type="text" name="email"
                                    id="email_<?php echo $d['id'] ?>" class="form-control" placeholder="Email"
                                    autocomplete="off" required="">
                            </div>
                        </div>
                        <div class="modal-footer no-bd">
                            <button type="submit" name="update" class="btn btn-primary"><i
                                    class="fa fa-save"></i>Simpan</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-undo"></i>
                                Tutup</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>
    <!-- End Modal Edit Admin -->

    <!-- Modal Delete Admin -->
    <?php
    $c = mysqli_query($conn, 'SELECT * from t_user');
    while ($row = mysqli_fetch_array($c)) {
        ?>
        <div class="modal fade" id="modalDeleteAdmin<?php echo $row['id'] ?>" tabindex="-1" role="dialog"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header no-bd">
                        <h5 class="modal-title">
                            <span class="fw-mediumbold">Delete Admin</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" enctype="multipart/form-data" action="">
                        <div class="modal-body">
                            <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                            <h4>Are you sure to remove this Admin ?</h4>
                        </div>
                        <div class="modal-footer no-bd">
                            <button type="submit" name="delete" class="btn btn-danger"><i class="fa fa-trash"></i>
                                Delete</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-undo"></i>
                                Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>
    <!-- End Modal Delete Admin -->

    <!-- Modal Edit Score -->
    <?php
    $scoreQuery = mysqli_query($conn, "
    SELECT p.*, pr.namaProgram 
    FROM pendaftaran p 
    JOIN t_program pr ON p.idProgram = pr.idProgram
");
    while ($scoreRow = mysqli_fetch_array($scoreQuery)) {
        $isToefl = stripos($scoreRow['namaProgram'], 'TOEFL') !== false;
        $isIelts = stripos($scoreRow['namaProgram'], 'IELTS') !== false;
        ?>
        <!-- Modal Edit Score Awal -->
        <div class="modal fade" id="modalEditScoreBefore<?= $scoreRow['idPendaftaran'] ?>" tabindex="-1" role="dialog"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header no-bd">
                        <h5 class="modal-title">Edit Score Awal (<?= htmlspecialchars($scoreRow['namaProgram']) ?>)</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST">
                        <div class="modal-body">
                            <input type="hidden" name="idPendaftaran" value="<?= $scoreRow['idPendaftaran'] ?>">
                            <input type="hidden" name="score" value="<?= htmlspecialchars($scoreRow['score'] ?? '-') ?>">

                            <div class="form-group">
                                <label>Score Awal</label>
                                <?php if ($isToefl): ?>
                                    <input type="number" name="scoreBefore" class="form-control"
                                        value="<?= htmlspecialchars($scoreRow['scoreBefore'] ?? '-') ?>" min="0" max="600"
                                        step="1" title="Masukkan angka antara 0-600 (tanpa desimal)">
                                    <small class="text-muted">TOEFL score harus diantara 0 - 600</small>
                                <?php elseif ($isIelts): ?>
                                    <input type="number" name="scoreBefore" class="form-control"
                                        value="<?= htmlspecialchars($scoreRow['scoreBefore'] ?? '-') ?>" min="0" max="9"
                                        step="0.1" title="Masukkan angka antara 0.0-9.0 (dengan 1 desimal)">
                                    <small class="text-muted">IELTS score harus diantara 0.0 - 9.0</small>
                                <?php else: ?>
                                    <input type="number" name="scoreBefore" class="form-control"
                                        value="<?= htmlspecialchars($scoreRow['scoreBefore'] ?? '-') ?>" min="0" max="100"
                                        step="0.1" title="Masukkan angka antara 0-100">
                                    <small class="text-muted">Score range: 0-100</small>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="modal-footer no-bd">
                            <button type="submit" name="update_score" class="btn btn-primary">
                                <i class="fa fa-save"></i> Save
                            </button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                <i class="fa fa-times"></i> Close
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Edit Score Akhir -->
        <div class="modal fade" id="modalEditScore<?= $scoreRow['idPendaftaran'] ?>" tabindex="-1" role="dialog"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header no-bd">
                        <h5 class="modal-title">Edit Score Akhir (<?= htmlspecialchars($scoreRow['namaProgram']) ?>)</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST">
                        <div class="modal-body">
                            <input type="hidden" name="idPendaftaran" value="<?= $scoreRow['idPendaftaran'] ?>">
                            <input type="hidden" name="scoreBefore"
                                value="<?= htmlspecialchars($scoreRow['scoreBefore'] ?? '-') ?>">

                            <div class="form-group">
                                <label>Score Akhir</label>
                                <?php if ($isToefl): ?>
                                    <input type="number" name="score" class="form-control"
                                        value="<?= htmlspecialchars($scoreRow['score'] ?? '-') ?>" min="0" max="600" step="1"
                                        title="Masukkan angka antara 0-600 (tanpa desimal)" required>
                                    <small class="text-muted">TOEFL score harus diantara 0 - 600</small>
                                <?php elseif ($isIelts): ?>
                                    <input type="number" name="score" class="form-control"
                                        value="<?= htmlspecialchars($scoreRow['score'] ?? '-') ?>" min="0" max="9" step="0.1"
                                        title="Masukkan angka antara 0.0-9.0 (dengan 1 desimal)" required>
                                    <small class="text-muted">IELTS score harus diantara 0.0 - 9.0</small>
                                <?php else: ?>
                                    <input type="number" name="score" class="form-control"
                                        value="<?= htmlspecialchars($scoreRow['score'] ?? '-') ?>" min="0" max="100" step="0.1"
                                        title="Masukkan angka antara 0-100" required>
                                    <small class="text-muted">Score range: 0-100</small>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="modal-footer no-bd">
                            <button type="submit" name="update_score" class="btn btn-primary">
                                <i class="fa fa-save"></i> Save
                            </button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                <i class="fa fa-times"></i> Close
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>
    <!-- End Modal Edit Score -->

    <footer id="footer" class="footer dark-background">
        <div class="container footer-top">
            <div class="row gy-4">
                <div class="col-lg-6 col-md-6 footer-about">
                    <a href="index.php" class="logo d-flex align-items-center">
                        <span class="sitename">STRAYA LANGUAGE INSTITUTE</span>
                    </a>
                    <div class="footer-contact pt-1">
                        <i>
                            <p>Everyone Can Get Scholarship</p>
                        </i>
                        <p>Pusat Informasi dan Konsultasi Beasiswa di NTB</p>
                        <p class="mt-3">
                            <strong>Phone:</strong> <span>+62 878-7269-0246</span>
                        </p>
                        <p><strong>Email:</strong> <span>straya.institute@gmail.com</span></p>
                    </div>
                    <div class="social-links d-flex mt-4">
                        <a href="https://www.facebook.com/straya.institute" target="_blank"><i
                                class="bi bi-facebook"></i></a>
                        <a href="https://www.instagram.com/straya.institute" target="_blank"><i
                                class="bi bi-instagram"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Menu</h4>
                    <ul>
                        <li><i class="bi bi-chevron-right"></i> <a href="../administrator/index.php#hero">Home</a></li>
                        <li>
                            <i class="bi bi-chevron-right"></i> <a href="../administrator/index.php#manage">Manage
                                User</a>
                        </li>
                        <li>
                            <i class="bi bi-chevron-right"></i> <a
                                href="../administrator/beasiswa/viewBeasiswa.php">Beasiswa</a>
                        </li>
                        <li>
                            <i class="bi bi-chevron-right"></i> <a
                                href="../administrator/program/viewProgram.php">Program</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="container copyright text-center mt-4">
            <p>
                © <span>Copyright</span> <strong class="px-1 sitename">STRAYA LANGUAGE INSTITUTE</strong>
                <span>All Rights Reserved</span>
            </p>
        </div>
    </footer>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/php-email-form/validate.js"></script>
    <script src="../assets/vendor/aos/aos.js"></script>
    <script src="../assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="../assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="../assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="../assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="../assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>

    <!-- Main JS File -->
    <script src="../assets/js/main.js"></script>

    <script>
        // Fungsi untuk cetak presensi individu
        function cetakPresensi(nama, program, paket, waktu, jumlahPertemuan) {
            const printWindow = window.open('', '_blank');

            let htmlContent = `
        <html>
            <head>
                <title>Absensi Individu</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; font-size: 12px; }
                    h2 { text-align: center; margin-bottom: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
                    .info { margin-bottom: 17px; }
                    .info p { margin: 3px 0; font-weight: bold; }
                    @media print {
                        body { margin: 0; padding: 10px; }
                        table { font-size: 10px; }
                    }
                </style>
            </head>
            <body>
                <h2>Presensi Individu</h2>
                
                <div class="info">
                    <p><strong>Nama:</strong> ${nama}</p>
                    <p><strong>Program:</strong> ${program}</p>
                    <p><strong>Paket:</strong> ${paket}</p>
                    <p><strong>Waktu:</strong> ${waktu}</p>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th class="text-center">Pertemuan</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Paraf</th>
                        </tr>
                    </thead>
                    </thead>
                    <tbody>
        `;

            // Add rows for each meeting
            for (let i = 1; i <= jumlahPertemuan; i++) {
                htmlContent += `
                <tr>
                    <td>Pertemuan ${i}</td>
                    <td></td>
                    <td></td>
                </tr>
            `;
            }

            htmlContent += `
                    </tbody>
                </table>
                
                <div style="margin-top: 69px; text-align: right;">
                    <div style="display: inline-block; text-align: center; width: 200px;">
                        <p>_________________________</p>
                        <p>Straya Language Institute</p>
                    </div>
                </div>
                
                <script>
                    window.onload = function() {
                        window.print();
                        setTimeout(function() {
                            window.close();
                        }, 1000);
                    };
                <\/script>
            </body>
        </html>
        `;

            printWindow.document.write(htmlContent);
            printWindow.document.close();
        }

        // Fungsi untuk cetak keseluruhan
        function cetakKeseluruhan(users) {
            const printWindow = window.open('', '_blank');
            const isExclusive = users[0]?.jumlahPertemuan === 24;
            const maxMeetings = isExclusive ? 24 : 8;

            let htmlContent = `
        <html>
            <head>
                <title>Absensi Keseluruhan</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    h2 { text-align: center; margin-bottom: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                    th, td { border: 1px solid #ddd; padding: 5px; text-align: center; }
                    th { background-color: #f2f2f2; }
                    .header-info { margin-bottom: 20px; text-align: center; }
                    .header-info p { margin: 3px 0; font-weight: bold; }
                    @media print {
                        body { margin: 0; padding: 10px; }
                        table { font-size: 12px; }
                    }
                </style>
            </head>
            <body>
                <h2>LAPORAN PRESENSI KESELURUHAN</h2>
                <div class="header-info">
                    <p>Program: ${users[0]?.program || ''}</p>
                    <p>Paket: ${users[0]?.paket || ''}</p>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Nama</th>
        `;

            // Add meeting number headers
            for (let i = 1; i <= maxMeetings; i++) {
                htmlContent += `<th width="3%">${i}</th>`;
            }

            htmlContent += `
                            <th width="10%">Paraf</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

            // Add rows for each user
            users.forEach((user, index) => {
                htmlContent += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${user.nama}</td>
            `;

                // Add empty cells for each meeting
                for (let i = 1; i <= maxMeetings; i++) {
                    htmlContent += `<td></td>`;
                }

                htmlContent += `
                    <td></td>
                </tr>
            `;
            });

            htmlContent += `
                    </tbody>
                </table>
                
                <div style="margin-top: 70px; text-align: right;">
                    <div style="display: inline-block; text-align: center; width: 200px;">
                        <p>_________________________</p>
                        <p>Straya Language Institute</p>
                    </div>
                </div>
                
                <script>
                    window.onload = function() {
                        window.print();
                        setTimeout(function() {
                            window.close();
                        }, 1000);
                    };
                <\/script>
            </body>
        </html>
        `;

            printWindow.document.write(htmlContent);
            printWindow.document.close();
        }
    </script>

</body>

</html>