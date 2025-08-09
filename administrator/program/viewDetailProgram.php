<?php
require '../../koneksi.php';
session_start();

$idProgram = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = "SELECT * FROM t_program WHERE idProgram = $idProgram";
$result = mysqli_query($conn, $query);
$program = mysqli_fetch_assoc($result);

if (!$program) {
    echo "<script>alert('Program tidak ditemukan!'); window.location='index.php';</script>";
    exit();
}
$pakets = json_decode($program['paketProgram'], true);
$price = json_decode($program['priceProgram'], true);
$kuota = json_decode($program['kuotaProgram'], true);
$waktu = json_decode($program['waktuProgram'], true);
$benefits = json_decode($program['benefitProgram'], true);
?>


<!-- Header -->
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
                    <li><a href="../index.php#manage">Manage User</a></li>
                    <li><a href="../beasiswa/viewBeasiswa.php">Beasiswa</a></li>
                    <li><a href="../program/viewProgram.php">Program</a></li>
                    <li class="nav-item dropdown d-flex align-items-center">
                        <span class="me-2 fw-bold text-white">Hello, <?= $_SESSION["fullName"] ?></span>
                        <div class="profile-picture bg-light" id="userDropdown">
                            <i class="fas fa-user"></i>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end" id="dropdownMenu">
                            <li><a class="dropdown-item" href="?view=viewProfile"><i class="fas fa-user me-2"></i>
                                    Profile</a></li>
                            <li><a class="dropdown-item" href="?view=changePassword"><i class="fas fa-lock me-2"></i>
                                    Change Password</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
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
        <div class="page-title dark-background" data-aos="fade"
            style="background-image: url(../../assets/img/page-title-bg.webp);">
            <div class="container position-relative">
                <h1>Program Details</h1>
                <nav class="breadcrumbs">
                    <ol>
                        <li><a href="../index.php">Home</a></li>
                        <li><a href="../program/viewProgram.php">Program</a></li>
                        <li class="current">Program Details</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Page Title -->

        <!-- Program Details Section -->
        <section id="program-details" class="program-details section">
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row gy-4">
                    <div class="col-lg-8">
                        <img src="../../assets/img/fileImage/<?php echo htmlspecialchars($program['gambarProgram']); ?>"
                            class="img-fluid" alt="">
                    </div>
                    <div class="col-lg-4">

                        <div class="program-description" data-aos="fade-up" data-aos-delay="300">
                            <h3>
                                <b>
                                    <?php echo htmlspecialchars($program['namaProgram']); ?>
                                </b>
                            </h3>

                            <br>
                            <p>
                                <?php echo htmlspecialchars($program['deskripsiProgram']); ?>
                            </p>
                        </div>

                        <br>
                        <?php foreach ($pakets as $index => $paket): ?>
                            <div class="program-info" data-aos="fade-up" data-aos-delay="200">
                                <h3><?php echo htmlspecialchars($paket); ?></h3>
                                <ul>
                                    <li><strong>Harga Program</strong>:
                                        <?php echo htmlspecialchars($price[$index] ?? 'Tidak tersedia'); ?></li>

                                    <br>
                                    <li><strong>Kuota Program</strong>:
                                        <?php echo htmlspecialchars($kuota[$index] ?? 'Tidak tersedia'); ?> Orang</li>

                                    <br>
                                    <li><strong>Waktu Program</strong>:</li>
                                    <ul>
                                        <?php if (!empty($waktu[$index])): ?>
                                            <?php foreach ($waktu[$index] as $w): ?>
                                                <li><i class='bi bi-clock'></i> <span><?php echo htmlspecialchars($w); ?></span>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <li><i class='bi bi-x-circle'></i> <span>Tidak tersedia</span></li>
                                        <?php endif; ?>
                                    </ul>
                                    <br>
                                    <li><strong>Benefit Program</strong>:</li>
                                    <ul>
                                        <?php if (!empty($benefits[$index])): ?>
                                            <?php foreach ($benefits[$index] as $benefit): ?>
                                                <li><i class='bi bi-check-circle'></i>
                                                    <span><?php echo htmlspecialchars($benefit); ?></span></li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <li><i class='bi bi-x-circle'></i> <span>Tidak tersedia</span></li>
                                        <?php endif; ?>
                                    </ul>
                                </ul>
                            </div>
                            <br>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>
        <!-- /Program Details Section -->
    </main>

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
                            <strong>Phone:</strong> <span><a href="https://wa.me/6287872690246" target="_blank">+62
                                    878-7269-0246</a></span>
                        </p>
                        <p><strong>Email:</strong> <span><a
                                    href="https://mail.google.com/mail/?view=cm&fs=1&to=straya.institute@gmail.com"
                                    target="_blank">straya.institute@gmail.com</a></span></p>
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
                        <li><i class="bi bi-chevron-right"></i> <a href="#hero">Home</a></li>
                        <li>
                            <i class="bi bi-chevron-right"></i> <a href="#about">About us</a>
                        </li>
                        <li>
                            <i class="bi bi-chevron-right"></i> <a href="#beasiswa">Beasiswa</a>
                        </li>
                        <li>
                            <i class="bi bi-chevron-right"></i> <a href="#program">Program</a>
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

    <!-- Main JS File -->
    <script src="../../assets/js/main.js"></script>
</body>

</html>