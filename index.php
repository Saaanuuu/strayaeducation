<?php
require 'koneksi.php';
session_start();

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
  <link href="assets/img/straya.png" rel="icon" />
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon" />

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect" />
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

  <!-- Vendor CSS Files -->
  <link
    href="assets/vendor/bootstrap/css/bootstrap.min.css"
    rel="stylesheet" />
  <link
    href="assets/vendor/bootstrap-icons/bootstrap-icons.css"
    rel="stylesheet" />
  <link href="assets/vendor/aos/aos.css" rel="stylesheet" />
  <link
    href="assets/vendor/glightbox/css/glightbox.min.css"
    rel="stylesheet" />
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet" />

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet" />
</head>

<body class="index-page">
  <header id="header" class="header d-flex align-items-center fixed-top">
    <div
      class="container-fluid container-xl position-relative d-flex align-items-center">
      <a href="index.php" class="logo d-flex align-items-center me-auto">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <img src="assets/img/straya.png" alt="" />
        <!-- <h1 class="sitename">STRAYA LANGUAGE INSTITUTE</h1> -->
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="index.php#hero">Home</a></li>
          <li><a href="index.php#about">About</a></li>
          <li><a href="index.php#beasiswa">Beasiswa</a></li>
          <li><a href="index.php#program">Program</a></li>
          <li><a href="index.php#contact">Contact</a></li>
          <?php if (isset($_SESSION['login'])): ?>
            <li class="nav-item dropdown d-flex align-items-center">
              <div class="text-truncate fw-bold text-white">
                Hello, <?= $_SESSION["fullName"] ?>
              </div>
              <div class="profile-picture bg-light" id="userDropdown">
                <i class="fas fa-user"></i>
              </div>
              <ul class="dropdown-menu dropdown-menu-end" id="dropdownMenu">
                <li><a class="dropdown-item" href="administrator/logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
              </ul>
            </li>
          <?php else: ?>
            <li><a href="administrator/auth/login.php">Login</a></li>
            <li><a href="administrator/auth/regist.php">Register</a></li>
          <?php endif; ?>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
    </div>
  </header>

  <main class="main">
    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">
      <img src="assets/img/hero-bg.jpg" alt="" data-aos="fade-in" />

      <div class="container d-flex flex-column align-items-center">
        <h2 data-aos="fade-up" data-aos-delay="100">STRAYA LANGUAGE INSTITUTE</h2>
        <p data-aos="fade-up" data-aos-delay="200">
          Everyone Can Get Scholarship
        </p>
      </div>
    </section>
    <!-- /Hero Section -->

    <!-- About Section -->
    <section id="about" class="about section">
      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            <h3>
              ABOUT STRAYA INSTITUTE
            </h3>
            <img src="assets/img/about.jpg" class="img-fluid rounded-4 mb-4" alt="" />
            <p style="text-align: justify;">
              Sejak 2018, Straya Language Institute telah berpengalaman mendampingi ribuan peserta mulai dari pelajar, mahasiswa, guru, dosen, tenaga kesehatan,
              birokrat, hingga para profesional dalam meningkatkan kemampuan Bahasa Inggris mereka. Banyak di antara mereka yang berhasil meraih beasiswa dan menempuh
              studi S1, S2, serta S3 di dalam dan luar negeri, sekaligus membangun karier gemilang di berbagai bidang.
            </p>
            <p style="text-align: justify;">
              Dengan tim pengajar berkualifikasi lulusan luar negeri, metode pembelajaran interaktif yang menyenangkan, serta layanan konsultasi dan interaksi 24 jam,
              kami berkomitmen membantu setiap siswa meraih kompetensi Bahasa Inggris optimal. Dukungan menyeluruh ini memungkinkan mereka meraih kesuksesan akademik maupun profesional,
              sekaligus membuka lebih banyak peluang dan pengalaman berharga.
            </p>
          </div>
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="250">
            <div class="content ps-0 ps-lg-5">
              <p class="fw-bold">
                MENGAPA MEMILIH STRAYA LANGUAGE INSTITUTE?
              </p>
              <ul>
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  <span>Kurikulum yang disesuaikan dengan kebutuhan peserta.</span>
                </li>
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  <span>Pengajar lulusan kampus bergengsi dalam dan luar negeri.</span>
                </li>
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  <span>Pengajar merupakan awardee dan alumni berbagai beasiswa.</span>
                </li>
              </ul>
              <p style="text-align: justify;">
                Bersama Straya Language Institute, wujudkan impianmu untuk menguasai bahasa Inggris
                dan meraih kesempatan belajar ke tingkat global!
              </p>
              <div class="position-relative mt-4">
                <img src="assets/img/about-2.jpg" class="img-fluid rounded-4" alt="" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /About Section -->

    <!-- Beasiswa Section -->
    <section id="beasiswa" class="beasiswa section">
      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Beasiswa</h2>
        <p>List Beasiswa<br /></p>
      </div>
      <!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row gy-5">

          <!-- Beasiswa Item -->
          <?php
          $query = "SELECT * FROM t_beasiswa";
          $result = mysqli_query($conn, $query);

          while ($row = mysqli_fetch_assoc($result)) {
            $idBeasiswa = $row['idBeasiswa'];
            $namaBeasiswa = htmlspecialchars($row['namaBeasiswa']);
            $motoBeasiswa = htmlspecialchars($row['motoBeasiswa']);
            $gambarBeasiswa = htmlspecialchars($row['gambarBeasiswa']);
          ?>
            <div class="col-xl-4 col-md-6" data-aos="zoom-in" data-aos-delay="200">
              <div class="beasiswa-item">
                <div class="img">
                  <img src="assets/img/fileImage/<?php echo $gambarBeasiswa; ?>" class="img-fluid" alt="<?php echo $gambarBeasiswa; ?>" />
                </div>
                <div class="details position-relative">
                  <div class="icon">
                    <i class="bi bi-broadcast"></i>
                  </div>
                  <a href="beasiswa.php?id=<?php echo $idBeasiswa; ?>" class="stretched-link">
                    <h3><?php echo $namaBeasiswa; ?></h3>
                  </a>
                  <p> <?php echo $motoBeasiswa; ?> </p>
                </div>
              </div>
            </div>
          <?php } ?>
          <!-- End Beasiswa Item -->
        </div>
      </div>
    </section>
    <!-- /Beasiswa Section -->

    <!-- Program Section -->
    <section id="program" class="program section">
      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Program</h2>
        <p>CHECK OUR PROGRAM</p>
      </div>
      <!-- End Section Title -->

      <div class="container">
        <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">
          <div class="row gy-4 isotope-container" data-aos="fade-up" data-aos-delay="200">
            <?php
            $query = mysqli_query($conn, "SELECT * FROM t_program");
            while ($d = mysqli_fetch_array($query)) {
            ?>
              <div class="col-lg-4 col-md-6 program-item isotope-item filter-app">
                <div class="program-content h-100">
                  <img src="assets/img/fileImage/<?php echo $d['gambarProgram']; ?>" class="img-fluid" alt="<?php echo $d['namaProgram']; ?>">
                  <div class="program-info">
                    <h4><?php echo $d['namaProgram']; ?></h4>
                    <a href="assets/img/fileImage/<?php echo $d['gambarProgram']; ?>" title="<?php echo $d['namaProgram']; ?>"
                      data-gallery="program-gallery-app" class="glightbox preview-link">
                      <i class="bi bi-zoom-in"></i>
                    </a>
                    <a href="program.php?id=<?php echo $d['idProgram']; ?>" title="More Details" class="details-link">
                      <i class="bi bi-link-45deg"></i>
                    </a>
                  </div>
                </div>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </section>
    <!-- /Program Section -->

    <!-- Contact Section -->
    <section id="contact" class="contact section">
      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Contact</h2>
        <p>Contact Us</p>
      </div>
      <!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row">
          <div class="col">
            <div class="row gy-4">
              <!-- Address -->
              <div class="col-lg-12">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="200">
                  <a href="https://maps.app.goo.gl/fSNwCjGt8gK91yjq5?g_st=com.google.maps.preview.copy" target="_blank">
                    <i class="bi bi-geo-alt"></i>
                  </a>
                  <h3>Address</h3>
                  <p>Jalan Swakarsa, Gg. Semanggi No.19, Kekalik Jaya, Kec. Sekarbela, Kota Mataram, Nusa Tenggara Barat. 83114, Indonesia</p>
                </div>
              </div>
              <!-- End Address -->

              <!-- Call Us -->
              <div class="col-md-6">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="300">
                  <a href="https://wa.me/6287872690246" target="_blank">
                    <i class="bi bi-telephone"></i>
                  </a>
                  <h3>Call Us</h3>
                  <p>+62 878-7269-0246</p>
                </div>
              </div>
              <!-- End Call Us -->

              <!-- Email Us -->
              <div class="col-md-6">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="400">
                  <a href="https://mail.google.com/mail/?view=cm&fs=1&to=straya.institute@gmail.com" target="_blank">
                    <i class="bi bi-envelope"></i>
                  </a>
                  <h3>Email Us</h3>
                  <p>straya.institute@gmail.com</p>
                </div>
              </div>
              <!-- End Email Us -->
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /Contact Section -->
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

        <!-- <div class="col-lg-2 col-md-3 footer-links">
                <h4>Our Beasiswa</h4>
                <ul>
                    <li>
                        <i class="bi bi-chevron-right"></i> <a href="#">Web Design</a>
                    </li>
                    <li>
                        <i class="bi bi-chevron-right"></i>
                        <a href="#">Web Development</a>
                    </li>
                    <li>
                        <i class="bi bi-chevron-right"></i>
                        <a href="#">Product Management</a>
                    </li>
                    <li>
                        <i class="bi bi-chevron-right"></i> <a href="#">Marketing</a>
                    </li>
                    <li>
                        <i class="bi bi-chevron-right"></i>
                        <a href="#">Graphic Design</a>
                    </li>
                </ul>
            </div> -->
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
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>
</body>

</html>