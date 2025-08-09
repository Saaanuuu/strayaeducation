<?php
session_start();
require '../../koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php?alert=not_logged_in");
    exit;
}

if (isset($_POST['save'])) {
    $namaProgram = htmlspecialchars($_POST['namaProgram']);
    $paketProgram = json_encode($_POST['paketProgram']);
    $priceProgram = json_encode($_POST['priceProgram']);
    $kuotaProgram = json_encode($_POST['kuotaProgram']);
    $waktuProgram = json_encode($_POST['waktuProgram']);
    $benefitProgram = json_encode($_POST['benefitProgram']);
    $deskripsiProgram = htmlspecialchars($_POST['deskripsiProgram']);

    $originalName = basename($_FILES["gambar"]["name"]);
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
    $fileName = uniqid('program_', true) . '.' . $extension;
    $targetDir = "../../assets/img/fileImage/";
    $targetFilePath = $targetDir . $fileName;

    if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $targetFilePath)) {
        $query = "INSERT INTO t_program (namaProgram, paketProgram, priceProgram, kuotaProgram, waktuProgram, benefitProgram, deskripsiProgram, gambarProgram) 
                  VALUES ('$namaProgram', '$paketProgram', '$priceProgram', '$kuotaProgram', '$waktuProgram', '$benefitProgram', '$deskripsiProgram', '$fileName')";
        mysqli_query($conn, $query);
        header("Location: viewProgram.php");
        exit();
    }
}

if (isset($_POST['update'])) {
    $idProgram = $_POST['idProgram'];
    $namaProgram = htmlspecialchars($_POST['namaProgram']);
    $deskripsiProgram = htmlspecialchars($_POST['deskripsiProgram']);
    $paketProgram = json_encode($_POST['paketProgram'] ?? []);
    $priceProgram = json_encode($_POST['priceProgram'] ?? []);
    $kuotaProgram = json_encode($_POST['kuotaProgram'] ?? []);
    $waktuProgram = json_encode($_POST['waktuProgram'] ?? []);
    $benefitProgram = json_encode($_POST['benefitProgram'] ?? []);

    $targetDir = "../../assets/img/fileImage/";

    if (!empty($_FILES["gambar"]["name"])) {
        $originalName = basename($_FILES["gambar"]["name"]);
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $fileName = uniqid('program_', true) . '.' . $extension;
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $targetFilePath)) {
            // Ambil gambar lama
            $queryOldImage = "SELECT gambarProgram FROM t_program WHERE idProgram='$idProgram'";
            $resultOldImage = mysqli_query($conn, $queryOldImage);
            $oldImage = mysqli_fetch_assoc($resultOldImage)['gambarProgram'];

            // Hapus gambar lama jika tidak digunakan program lain
            $queryCheck = "SELECT COUNT(*) as count FROM t_program WHERE gambarProgram='$oldImage'";
            $resultCheck = mysqli_query($conn, $queryCheck);
            $count = mysqli_fetch_assoc($resultCheck)['count'];
            if ($count <= 1 && file_exists($targetDir . $oldImage)) {
                unlink($targetDir . $oldImage);
            }

            $SQL = "UPDATE t_program SET 
                    namaProgram='$namaProgram', 
                    deskripsiProgram='$deskripsiProgram', 
                    paketProgram='$paketProgram',
                    priceProgram='$priceProgram',
                    kuotaProgram='$kuotaProgram',
                    waktuProgram='$waktuProgram',
                    benefitProgram='$benefitProgram',
                    gambarProgram='$fileName' 
                    WHERE idProgram='$idProgram'";
        }
    } else {
        $SQL = "UPDATE t_program SET 
                namaProgram='$namaProgram', 
                deskripsiProgram='$deskripsiProgram', 
                paketProgram='$paketProgram',
                priceProgram='$priceProgram',
                kuotaProgram='$kuotaProgram',
                waktuProgram='$waktuProgram',
                benefitProgram='$benefitProgram'
                WHERE idProgram='$idProgram'";
    }

    if (mysqli_query($conn, $SQL)) {
        header("Location: viewProgram.php");
        exit();
    } else {
        echo "Error: " . $SQL . "<br>" . mysqli_error($conn);
    }
}

if (isset($_POST['delete'])) {
    $idProgram = $_POST['idProgram'];

    // Ambil nama gambar
    $queryImage = "SELECT gambarProgram FROM t_program WHERE idProgram='$idProgram'";
    $resultImage = mysqli_query($conn, $queryImage);
    $image = mysqli_fetch_assoc($resultImage)['gambarProgram'];

    // Hapus program
    $SQL = "DELETE FROM t_program WHERE idProgram='$idProgram'";
    if (mysqli_query($conn, $SQL)) {
        // Cek apakah gambar masih digunakan
        $queryCheck = "SELECT COUNT(*) as count FROM t_program WHERE gambarProgram='$image'";
        $resultCheck = mysqli_query($conn, $queryCheck);
        $count = mysqli_fetch_assoc($resultCheck)['count'];
        if ($count == 0 && file_exists($targetDir . $image)) {
            unlink($targetDir . $image);
        }

        header("Location: viewProgram.php");
        exit();
    } else {
        echo "Error: " . $SQL . "<br>" . mysqli_error($conn);
    }
}
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
    <link href="../../assets/img/straya.png" rel="icon" />
    <link href="../../assets/img/apple-touch-icon.png" rel="apple-touch-icon" />

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Vendor CSS Files -->
    <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet" />
    <link href="../../assets/vendor/aos/aos.css" rel="stylesheet" />
    <link href="../../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet" />
    <link href="../../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet" />

    <!-- Main CSS File -->
    <link href="../../assets/css/main.css" rel="stylesheet" />
</head>

<body class="index-page">

    <!-- Header -->
    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="container-fluid container-xl position-relative d-flex align-items-center">
            <a href="../index.php" class="logo d-flex align-items-center me-auto">
                <!-- Uncomment the line below if you also wish to use an image logo -->
                <img src="../../assets/img/straya.png" alt="" />
                <!-- <h1 class="sitename">STRAYA LANGUAGE INSTITUTE</h1> -->
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="../index.php#hero">Home</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="manageDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Manage
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="manageDropdown">
                            <li><a class="dropdown-item" href="../index.php#manage">Manage User</a></li>
                            <li><a class="dropdown-item" href="../index.php#pendaftaran">Pendaftaran</a></li>
                            <li><a class="dropdown-item" href="../index.php#presensi">Presensi</a></li>
                        </ul>
                    </li>
                    <li><a href="../beasiswa/viewBeasiswa.php">Beasiswa</a></li>
                    <li><a href="../program/viewProgram.php">Program</a></li>
                    <li class="nav-item dropdown d-flex align-items-center">
                        <span class="me-2 fw-bold text-white">Hello, <?= $_SESSION["fullName"] ?></span>
                        <div class="profile-picture bg-light" id="userDropdown">
                            <i class="fas fa-user"></i>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end" id="dropdownMenu">
                            <li><a class="dropdown-item" href="../logout.php"><i class="fas fa-sign-out-alt me-2"></i>
                                    Logout</a></li>
                        </ul>
                    </li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>
        </div>
    </header>

    <main class="main">
        <!-- Page Title -->
        <div class="page-title dark-background" style="background-image: url(../../assets/img/page-title-bg.webp);">
            <div class="container position-relative">
                <h1>Program Details</h1>
                <nav class="breadcrumbs">
                    <ol>
                        <li><a href="../index.php">Home</a></li>
                        <li class="current">Program Details</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Page Title -->

        <!-- Program Details Section -->
        <section id="program-details" class="program-details section">
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <?php
                                    echo '<div class="col-md-8">';
                                    echo '<h4 class="card-title">List Program</h4>';
                                    echo '</div>';
                                    echo '<div class="col-md-4 d-flex justify-content-end">';
                                    echo '<button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalAddProgram">';
                                    echo '<i class="fa fa-plus"></i>';
                                    echo '&nbsp';
                                    echo 'Add New Program';
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
                                            <th style="text-align: center;">Nama Program</th>
                                            <th style="text-align: center;">Gambar</th>
                                            <th style="text-align: center;" width="8.3%">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $query = mysqli_query($conn, 'SELECT * FROM t_program');

                                        while ($t_program = mysqli_fetch_array($query)) {
                                            ?>
                                            <tr>
                                                <td style="text-align: center;"><?php echo $no++; ?></td>
                                                <td style="text-align: center;"><?php echo $t_program['namaProgram']; ?>
                                                </td>
                                                <td style="text-align: center;">
                                                    <img src="../../assets/img/fileImage/<?php echo $t_program['gambarProgram']; ?>"
                                                        alt="Program Image" width="300">
                                                </td>
                                                <td style="text-align: center;">
                                                    <div class="btn-group" role="group">
                                                        <a href="#modalEditProgram<?php echo $t_program['idProgram']; ?>"
                                                            data-bs-toggle="modal" title="Edit"
                                                            class="btn btn-xs btn-primary">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <a href="#modalDeleteProgram<?php echo $t_program['idProgram']; ?>"
                                                            data-bs-toggle="modal" title="Delete"
                                                            class="btn btn-xs btn-danger">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                        <a href="viewDetailProgram.php?id=<?php echo $t_program['idProgram']; ?>"
                                                            title="View Details" class="btn btn-xs btn-info">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    </div>
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
        <!-- /Program Details Section -->
    </main>

    <!-- Modal Add New Program -->
    <div class="modal fade" id="modalAddProgram" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Program</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Program</label>
                            <input type="text" name="namaProgram" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi Program</label>
                            <textarea name="deskripsiProgram" class="form-control" required></textarea>
                        </div>
                        <br>
                        <div id="program-container">
                            <h6>Paket Program</h6>
                            <div class="program-group">
                                <div class="form-group">
                                    <label>Nama Paket</label>
                                    <input type="text" name="paketProgram[]" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Price Program</label>
                                    <input type="text" name="priceProgram[]" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Kuota Program</label>
                                    <input type="text" name="kuotaProgram[]" class="form-control" required>
                                </div>
                                <div class="form-group waktu-container">
                                    <label>Waktu Program</label>
                                    <div class="d-flex">
                                        <input type="text" name="waktuProgram[0][]" class="form-control" required>
                                        <button type="button" class="btn btn-success btn-sm ms-2 add-waktu">+</button>
                                    </div>
                                </div>
                                <div class="form-group benefit-container">
                                    <label>Benefit Program</label>
                                    <div class="d-flex">
                                        <input type="text" name="benefitProgram[0][]" class="form-control" required>
                                        <button type="button" class="btn btn-success btn-sm ms-2 add-benefit">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <button type="button" id="add-paket" class="btn btn-primary">Tambah Paket Program</button>
                        <div class="form-group mt-3">
                            <label>Upload Gambar</label>
                            <input type="file" name="gambar" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="save" class="btn btn-primary"><i
                                class="fa fa-save"></i>Save</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-undo"></i>
                            Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal Add New Program -->

    <!-- Modal Edit Program -->
    <?php
    $p = mysqli_query($conn, "SELECT * FROM t_program");
    while ($d = mysqli_fetch_array($p)) {
        ?>
        <div class="modal fade" id="modalEditProgram<?php echo $d['idProgram'] ?>" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Program</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                            <input type="hidden" name="idProgram" value="<?php echo $d['idProgram'] ?>">

                            <div class="form-group">
                                <label>Nama Program</label>
                                <input type="text" name="namaProgram" value="<?php echo $d['namaProgram'] ?>"
                                    class="form-control" required>
                            </div>

                            <br>
                            <div class="form-group">
                                <label>Deskripsi Program</label>
                                <textarea name="deskripsiProgram" class="form-control"
                                    required><?php echo $d['deskripsiProgram'] ?></textarea>
                            </div>

                            <br>
                            <div id="edit-program-container-<?php echo $d['idProgram']; ?>">
                                <h6>Paket Program</h6>

                                <?php
                                $paketPrograms = json_decode($d['paketProgram'], true) ?? [];
                                $pricePrograms = json_decode($d['priceProgram'], true) ?? [];
                                $kuotaPrograms = json_decode($d['kuotaProgram'], true) ?? [];
                                $waktuPrograms = json_decode($d['waktuProgram'], true) ?? [];
                                $benefitPrograms = json_decode($d['benefitProgram'], true) ?? [];

                                foreach ($paketPrograms as $index => $paketProgram) {
                                    ?>
                                    <div class="program-group">
                                        <div class="mb-3">
                                            <button type="button" class="btn btn-danger btn-sm remove-paket">Hapus
                                                Paket</button>
                                        </div>

                                        <div class="form-group">
                                            <label>Nama Paket</label>
                                            <input type="text" name="paketProgram[]"
                                                value="<?php echo htmlspecialchars($paketProgram); ?>" class="form-control"
                                                required>
                                        </div>

                                        <br>
                                        <div class="form-group">
                                            <label>Price Program</label>
                                            <input type="text" name="priceProgram[]"
                                                value="<?php echo htmlspecialchars($pricePrograms[$index] ?? ''); ?>"
                                                class="form-control" required>
                                        </div>

                                        <br>
                                        <div class="form-group">
                                            <label>Kuota Program</label>
                                            <input type="text" name="kuotaProgram[]"
                                                value="<?php echo htmlspecialchars($kuotaPrograms[$index] ?? ''); ?>"
                                                class="form-control" required>
                                        </div>

                                        <br>
                                        <div class="form-group waktu-container">
                                            <label>Waktu Program</label>
                                            <?php if (!empty($waktuPrograms[$index])) { ?>
                                                <?php foreach ($waktuPrograms[$index] as $waktu) { ?>
                                                    <div class="d-flex mt-2">
                                                        <input type="text" name="waktuProgram[<?php echo $index; ?>][]"
                                                            value="<?php echo htmlspecialchars($waktu); ?>" class="form-control"
                                                            required>
                                                        <button type="button" class="btn btn-danger btn-sm ms-2 remove-field">-</button>
                                                    </div>
                                                <?php } ?>
                                            <?php } ?>
                                            <br>
                                            <button type="button" class="btn btn-success btn-sm ms-2 add-waktu">+</button>
                                        </div>

                                        <br>
                                        <div class="form-group benefit-container">
                                            <label>Benefit Program</label>
                                            <?php if (!empty($benefitPrograms[$index])) { ?>
                                                <?php foreach ($benefitPrograms[$index] as $benefit) { ?>
                                                    <div class="d-flex mt-2">
                                                        <input type="text" name="benefitProgram[<?php echo $index; ?>][]"
                                                            value="<?php echo htmlspecialchars($benefit); ?>" class="form-control"
                                                            required>
                                                        <button type="button" class="btn btn-danger btn-sm ms-2 remove-field">-</button>
                                                    </div>
                                                <?php } ?>
                                            <?php } ?>
                                            <br>
                                            <button type="button" class="btn btn-success btn-sm ms-2 add-benefit">+</button>
                                        </div>
                                        <hr>
                                    </div>
                                <?php } ?>
                            </div>

                            <button type="button" class="btn btn-primary add-edit-paket"
                                data-idprogram="<?php echo $d['idProgram']; ?>">Tambah Paket Program</button>

                            <div class="form-group mt-3">
                                <label>Upload Gambar</label>
                                <input type="file" name="gambar" class="form-control">
                                <br>
                                <img src="../../assets/img/fileImage/<?php echo htmlspecialchars($d['gambarProgram']); ?>"
                                    width="100px" alt="Gambar">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="update" class="btn btn-primary"><i
                                    class="fa fa-save"></i>Save</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-undo"></i>
                                Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>
    <!-- End Modal Edit Program -->

    <!-- Modal Delete Program -->
    <?php
    $c = mysqli_query($conn, 'SELECT * from t_program');
    while ($row = mysqli_fetch_array($c)) {
        ?>
        <div class="modal fade" id="modalDeleteProgram<?php echo $row['idProgram'] ?>" tabindex="-1" role="dialog"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header no-bd">
                        <h5 class="modal-title">
                            <span class="fw-mediumbold">Delete Program</span>
                        </h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" enctype="multipart/form-data" action="">
                        <div class="modal-body">
                            <input type="hidden" name="idProgram" value="<?php echo $row['idProgram'] ?>">
                            <h4>Are you sure to remove this Program ?</h4>
                        </div>
                        <div class="modal-footer no-bd">
                            <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>
    <!-- End Modal Delete Program -->

    <!-- Footer -->
    <footer id="footer" class="footer dark-background">
        <div class="container footer-top">
            <div class="row gy-4">
                <div class="col-lg-6 col-md-6 footer-about">
                    <a href="../index.php" class="logo d-flex align-items-center">
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
                        <!-- <a href=""><i class="bi bi-twitter-x"></i></a> -->
                        <a href="https://www.facebook.com/straya.institute" target="_blank"><i
                                class="bi bi-facebook"></i></a>
                        <a href="https://www.instagram.com/straya.institute" target="_blank"><i
                                class="bi bi-instagram"></i></a>
                        <!-- <a href=""><i class="bi bi-linkedin"></i></a> -->
                    </div>
                </div>

                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Menu</h4>
                    <ul>
                        <li><i class="bi bi-chevron-right"></i> <a href="../index.php#hero">Home</a></li>
                        <li>
                            <i class="bi bi-chevron-right"></i> <a href="../index.php#manage">Manage User</a>
                        </li>
                        <li>
                            <i class="bi bi-chevron-right"></i> <a href="../beasiswa/viewBeasiswa.php">Beasiswa</a>
                        </li>
                        <li>
                            <i class="bi bi-chevron-right"></i> <a href="../program/viewProgram.php">Program</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="container copyright text-center mt-4">
            <p>
                ©️ <span>Copyright</span> <strong class="px-1 sitename">STRAYA LANGUAGE INSTITUTE</strong>
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
    <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/vendor/php-email-form/validate.js"></script>
    <script src="../../assets/vendor/aos/aos.js"></script>
    <script src="../../assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="../../assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="../../assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="../../assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="../../assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Main JS File -->
    <script src="../../assets/js/main.js"></script>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>