<?php
require '../../koneksi.php';
session_start();


$idBeasiswa = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = "SELECT * FROM t_beasiswa WHERE idBeasiswa = $idBeasiswa";
$result = mysqli_query($conn, $query);
$beasiswa = mysqli_fetch_assoc($result);

if (!$beasiswa) {
    echo "<script>alert('Beasiswa tidak ditemukan!'); window.location='index.php';</script>";
    exit();
}
$benefits = json_decode($beasiswa['benefitBeasiswa'], true);
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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
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
        <div
            class="container-fluid container-xl position-relative d-flex align-items-center">
            <a href="index.php" class="logo d-flex align-items-center me-auto">
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
                            <li><a class="dropdown-item" href="?view=viewProfile"><i class="fas fa-user me-2"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="?view=changePassword"><i class="fas fa-lock me-2"></i> Change Password</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="../logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>
        </div>
    </header>

    <main class="main">

        <!-- Page Title -->
        <div class="page-title dark-background" data-aos="fade" style="background-image: url(../../assets/img/page-title-bg.webp);">
            <div class="container position-relative">
                <h1>Beasiswa Details</h1>
                <nav class="breadcrumbs">
                    <ol>
                        <li><a href="../index.php">Home</a></li>
                        <li><a href="../beasiswa/viewBeasiswa.php">Beasiswa</a></li>
                        <li class="current">Beasiswa Details</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Page Title -->

        <!-- Beasiswa Details Section -->
        <section id="beasiswa-details" class="beasiswa-details section">
            <div class="container">
                <div class="row gy-4">
                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                        <h1>
                            <b>
                                <?php echo htmlspecialchars($beasiswa['namaBeasiswa']); ?>
                            </b>
                        </h1>
                        <br>

                        <br>
                        <h3>Visi Kami</h3>
                        <p style="text-align:justify">
                            <?php echo htmlspecialchars($beasiswa['visiBeasiswa']); ?>
                        </p>
                        <br>

                        <h3>Misi Kami</h3>
                        <ul>
                            <?php
                            $misiList = explode("\n", $beasiswa['misiBeasiswa']);
                            foreach ($misiList as $misi) {
                                echo "<li style='text-align:justify'>" . htmlspecialchars($misi) . "</li>";
                            }
                            ?>
                        </ul>
                    </div>

                    <div class="col-lg-8" data-aos="fade-up" data-aos-delay="200">
                        <img src="../../assets/img/fileImage/<?php echo htmlspecialchars($beasiswa['gambarBeasiswa']); ?>" alt="Gambar Beasiswa" class="img-fluid beasiswa-img">
                        <h3>
                            <?php echo htmlspecialchars($beasiswa['motoBeasiswa']); ?>
                        </h3>
                        <p>
                            <?php echo htmlspecialchars($beasiswa['deskripsiBeasiswa']); ?>
                        </p>
                        <ul>
                            <?php
                            if (!empty($benefits)) {
                                foreach ($benefits as $benefit) {
                                    echo "<li><i class='bi bi-check-circle'></i> <span>" . htmlspecialchars($benefit) . "</span></li>";
                                }
                            } else {
                                echo "<li><i class='bi bi-check-circle'></i> <span>Tidak ada benefit yang terdaftar.</span></li>";
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <!-- /Beasiswa Details Section -->

    </main>

    <!-- Footer -->
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
                            <strong>Phone:</strong> <span><a href="https://wa.me/6287872690246" target="_blank">+62 878-7269-0246</a></span>
                        </p>
                        <p><strong>Email:</strong> <span><a href="https://mail.google.com/mail/?view=cm&fs=1&to=straya.institute@gmail.com" target="_blank">straya.institute@gmail.com</a></span></p>
                    </div>
                    <div class="social-links d-flex mt-4">
                        <!-- <a href=""><i class="bi bi-twitter-x"></i></a> -->
                        <a href="https://www.facebook.com/straya.institute" target="_blank"><i class="bi bi-facebook"></i></a>
                        <a href="https://www.instagram.com/straya.institute" target="_blank"><i class="bi bi-instagram"></i></a>
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
                © <span>Copyright</span> <strong class="px-1 sitename">STRAYA LANGUAGE INSTITUTE</strong>
                <span>All Rights Reserved</span>
            </p>
        </div>
    </footer>

    <!-- Scroll Top -->
    <a
        href="#"
        id="scroll-top"
        class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

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