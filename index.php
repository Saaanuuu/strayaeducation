<?php
require 'koneksi.php';
session_start();

function getRegistrationDataByProgram($conn)
{
  $programs = mysqli_query($conn, "SELECT * FROM t_program");
  $monthlyData = [];
  $colors = [
    'rgba(255, 99, 132, 0.7)',
    'rgba(54, 162, 235, 0.7)',
    'rgba(255, 206, 86, 0.7)',
    'rgba(75, 192, 192, 0.7)',
    'rgba(153, 102, 255, 0.7)'
  ];

  $i = 0;
  while ($program = mysqli_fetch_assoc($programs)) {
    $sql = "SELECT 
                    MONTH(tanggalDaftar) as month, 
                    COUNT(*) as count 
                FROM pendaftaran 
                WHERE YEAR(tanggalDaftar) = YEAR(CURDATE())
                AND idProgram = " . $program['idProgram'] . "
                AND statusPendaftaran = 'Diterima'
                GROUP BY MONTH(tanggalDaftar)";

    $result = mysqli_query($conn, $sql);
    $data = array_fill(0, 12, 0);

    while ($row = mysqli_fetch_assoc($result)) {
      $monthIndex = $row['month'] - 1;
      $data[$monthIndex] = (int) $row['count'];
    }

    $monthlyData[] = [
      'label' => $program['namaProgram'],
      'data' => $data,
      'backgroundColor' => $colors[$i % count($colors)],
      'borderColor' => $colors[$i % count($colors)],
      'borderWidth' => 1
    ];
    $i++;
  }

  return $monthlyData;
}

function getSingleProgramData($conn, $programId)
{
  $programQuery = mysqli_query($conn, "SELECT namaProgram FROM t_program WHERE idProgram = " . intval($programId));
  $program = mysqli_fetch_assoc($programQuery);
  $programName = $program ? $program['namaProgram'] : 'Program';

  $sql = "SELECT 
                MONTH(tanggalDaftar) as month, 
                COUNT(*) as count 
            FROM pendaftaran 
            WHERE YEAR(tanggalDaftar) = YEAR(CURDATE())
            AND idProgram = " . intval($programId) . "
            AND statusPendaftaran = 'Diterima'
            GROUP BY MONTH(tanggalDaftar)";

  $result = mysqli_query($conn, $sql);
  $data = array_fill(0, 12, 0);

  while ($row = mysqli_fetch_assoc($result)) {
    $monthIndex = $row['month'] - 1;
    $data[$monthIndex] = (int) $row['count'];
  }

  return [
    'label' => $programName,
    'data' => $data,
    'backgroundColor' => 'rgba(54, 162, 235, 0.7)',
    'borderColor' => 'rgba(54, 162, 235, 1)',
    'borderWidth' => 1
  ];
}

// Handle AJAX request
if (isset($_GET['getChartData'])) {
  header('Content-Type: application/json');
  $programId = $_GET['program'] ?? null;

  if ($programId === 'all' || $programId === null) {
    echo json_encode(getRegistrationDataByProgram($conn));
  } else {
    echo json_encode(getSingleProgramData($conn, $programId));
  }
  exit();
}

// Get initial data for all programs
$initialChartData = json_encode(getRegistrationDataByProgram($conn));
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
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet" />
  <link href="assets/vendor/aos/aos.css" rel="stylesheet" />
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet" />
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet" />

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <style>
    .chart-legend {
      margin-top: 20px;
      font-size: 14px;
    }

    .chart-legend ul {
      list-style: none;
      padding: 0;
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
    }

    .chart-legend li {
      display: flex;
      align-items: center;
      margin-right: 15px;
    }

    .chart-legend span {
      display: inline-block;
      width: 15px;
      height: 15px;
      margin-right: 5px;
      border-radius: 3px;
    }
  </style>

  <style>
    .small-font {
      font-size: 0.85rem;
    }

    .action-column {
      width: 80px;
    }

    .view-details {
      padding: 0.15rem 0.3rem;
      font-size: 0.8rem;
    }

    .hidden-row {
      display: none;
    }
  </style>


</head>

<body class="index-page">
  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">
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
                <li><a class="dropdown-item" href="riwayat.php"><i class="fas fa-history me-2"></i> Riwayat</a></li>
                <li><a class="dropdown-item" href="pembayaran.php"><i class="fas fa-money-bill-wave me-2"></i>
                    Pembayaran</a></li>
                <li><a class="dropdown-item" href="administrator/logout.php"><i class="fas fa-sign-out-alt me-2"></i>
                    Logout</a>
                </li>
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
              Sejak 2018, Straya Language Institute telah berpengalaman mendampingi ribuan peserta mulai dari pelajar,
              mahasiswa, guru, dosen, tenaga kesehatan,
              birokrat, hingga para profesional dalam meningkatkan kemampuan Bahasa Inggris mereka. Banyak di antara
              mereka yang berhasil meraih beasiswa dan menempuh
              studi S1, S2, serta S3 di dalam dan luar negeri, sekaligus membangun karier gemilang di berbagai bidang.
            </p>
            <p style="text-align: justify;">
              Dengan tim pengajar berkualifikasi lulusan luar negeri, metode pembelajaran interaktif yang menyenangkan,
              serta layanan konsultasi dan interaksi 24 jam,
              kami berkomitmen membantu setiap siswa meraih kompetensi Bahasa Inggris optimal. Dukungan menyeluruh ini
              memungkinkan mereka meraih kesuksesan akademik maupun profesional,
              sekaligus membuka lebih banyak peluang dan pengalaman berharga.
            </p>
            <div class="row mt-4">
              <div class="col-lg-12">
                <!-- Tabel Data -->
                <h3 class="text-center mb-4">Progress Siswa</h3>
                <div class="table-responsive">
                  <table class="display table table-striped table-hover table-bordered align-middle small-font">
                    <thead class="table-white text-center">
                      <tr>
                        <th>ID Pendaftaran</th>
                        <th>Nama Program</th>
                        <th>Score Awal</th>
                        <th>Score Akhir</th>
                      </tr>
                    </thead>
                    <tbody id="registrationTableBody">
                      <?php
                      // Ambil semua data dari database
                      $query = "SELECT p.idPendaftaran, t.namaProgram, p.scoreBefore, p.score 
                              FROM pendaftaran p 
                              JOIN t_program t ON p.idProgram = t.idProgram";
                      $result = mysqli_query($conn, $query);
                      $allData = [];
                      while ($row = mysqli_fetch_assoc($result)) {
                        $allData[] = $row;
                      }

                      // Tampilkan hanya 5 data pertama
                      $initialData = array_slice($allData, 0, 5);
                      foreach ($initialData as $row) {
                        $scoreBefore = ($row['scoreBefore'] === null || $row['scoreBefore'] === '') ? '-' : $row['scoreBefore'];
                        $score = ($row['score'] === null || $row['score'] === '') ? '-' : $row['score'];

                        echo "<tr class='text-center'>
                                <td>{$row['idPendaftaran']}</td>
                                <td>{$row['namaProgram']}</td>
                                <td>{$scoreBefore}</td>
                                <td>{$score}</td>
                              </tr>";
                      }
                      ?>
                    </tbody>
                  </table>

                  <!-- Action Buttons -->
                  <div class="text-center mt-3">
                    <button id="showMoreBtn" class="btn btn-sm btn-outline-secondary"
                      data-all-data='<?php echo json_encode($allData); ?>' data-offset="5">
                      Show More <i class="bi bi-chevron-down"></i>
                    </button>
                    <button id="showLessBtn" class="btn btn-sm btn-outline-secondary ms-2" style="display: none;">
                      Show Less <i class="bi bi-chevron-up"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
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
              <!-- Chart Section -->
              <div class="chart-container mt-4">
                <h3 class="text-center mb-4">Statistik Pendaftaran Siswa</h3>
                <div class="mb-3">
                  <!-- <label for="programFilter" class="form-label fw-bold">Filter Program:</label> -->
                  <select class="form-select" id="programFilter">
                    <option value="all">Semua Program</option>
                    <?php
                    $programs = mysqli_query($conn, "SELECT * FROM t_program");
                    while ($program = mysqli_fetch_assoc($programs)) {
                      echo '<option value="' . $program['idProgram'] . '">' . $program['namaProgram'] . '</option>';
                    }
                    ?>
                  </select>
                </div>
                <div class="card shadow-sm p-3">
                  <canvas id="registrationChart" height="250"></canvas>
                </div>
              </div>
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
                  <img src="assets/img/fileImage/<?php echo $gambarBeasiswa; ?>" class="img-fluid"
                    alt="<?php echo $gambarBeasiswa; ?>" />
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
                  <img src="assets/img/fileImage/<?php echo $d['gambarProgram']; ?>" class="img-fluid"
                    alt="<?php echo $d['namaProgram']; ?>">
                  <div class="program-info">
                    <h4><?php echo $d['namaProgram']; ?></h4>
                    <a href="assets/img/fileImage/<?php echo $d['gambarProgram']; ?>"
                      title="<?php echo $d['namaProgram']; ?>" data-gallery="program-gallery-app"
                      class="glightbox preview-link">
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
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up"
                  data-aos-delay="200">
                  <a href="https://maps.app.goo.gl/fSNwCjGt8gK91yjq5?g_st=com.google.maps.preview.copy" target="_blank">
                    <i class="bi bi-geo-alt"></i>
                  </a>
                  <h3>Address</h3>
                  <p>Jalan Swakarsa, Gg. Semanggi No.19, Kekalik Jaya, Kec. Sekarbela, Kota Mataram, Nusa Tenggara
                    Barat. 83114, Indonesia</p>
                </div>
              </div>
              <!-- End Address -->

              <!-- Call Us -->
              <div class="col-md-6">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up"
                  data-aos-delay="300">
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
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up"
                  data-aos-delay="400">
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
              <strong>Phone:</strong> <span><a href="https://wa.me/6287872690246" target="_blank">+62
                  878-7269-0246</a></span>
            </p>
            <p><strong>Email:</strong> <span><a
                  href="https://mail.google.com/mail/?view=cm&fs=1&to=straya.institute@gmail.com"
                  target="_blank">straya.institute@gmail.com</a></span></p>
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
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

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

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const ctx = document.getElementById('registrationChart').getContext('2d');
      let registrationChart;

      function initChart(data, isMultiProgram) {
        if (registrationChart) {
          registrationChart.destroy();
        }

        const chartData = {
          labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
          datasets: isMultiProgram ? data : [data]
        };

        registrationChart = new Chart(ctx, {
          type: 'bar',
          data: chartData,
          options: {
            responsive: true,
            scales: {
              y: {
                beginAtZero: true,
                max: 10,
                ticks: {
                  stepSize: 1,
                  precision: 0
                },
                stacked: false,
                title:{
                  display: true,
                  text:'Jumlah Peserta'
                }
              },
              x: {
                stacked: false,
                title:{
                  display: true,
                  text:'Bulan'
                }
              }
            },
            plugins: {
              legend: {
                position: 'bottom',
                display: isMultiProgram
              },
              tooltip: {
                mode: 'index',
                intersect: false
              }
            }
          }
        });
      }

      // Load initial data (all programs)
      initChart(<?php echo $initialChartData; ?>, true);

      // Filter handler
      document.getElementById('programFilter').addEventListener('change', async function () {
        try {
          const programId = this.value;
          const response = await fetch(`?getChartData=1&program=${programId}`);

          if (!response.ok) throw new Error('Network error');

          const data = await response.json();

          const isMultiProgram = programId === 'all';
          initChart(data, isMultiProgram);
        } catch (error) {
          console.error('Error:', error);
          alert('Gagal memuat data. Silakan coba lagi.');
        }
      });
    });
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const showMoreBtn = document.getElementById('showMoreBtn');
      const showLessBtn = document.getElementById('showLessBtn');
      const tableBody = document.getElementById('registrationTableBody');
      const allData = JSON.parse(showMoreBtn.getAttribute('data-all-data'));
      const initialLimit = 5;
      let currentOffset = initialLimit;

      // Fungsi untuk menampilkan nilai atau strip jika kosong
      function displayValue(value) {
        return (value === null || value === '') ? '-' : value;
      }

      // Fungsi untuk update tombol
      function updateButtons() {
        if (currentOffset >= allData.length) {
          showMoreBtn.style.display = 'none';
        } else {
          showMoreBtn.style.display = 'inline-block';
          showMoreBtn.innerHTML = `Show More (${allData.length - currentOffset}) <i class="bi bi-chevron-down"></i>`;
        }

        if (currentOffset > initialLimit) {
          showLessBtn.style.display = 'inline-block';
        } else {
          showLessBtn.style.display = 'none';
        }
      }

      // Show More - Tambah 5 data
      showMoreBtn.addEventListener('click', function () {
        const moreData = allData.slice(currentOffset, currentOffset + 5);

        moreData.forEach(row => {
          const tr = document.createElement('tr');
          tr.className = 'text-center';
          tr.innerHTML = `
                <td>${row.idPendaftaran}</td>
                <td>${row.namaProgram}</td>
                <td>${displayValue(row.scoreBefore)}</td>
                <td>${displayValue(row.score)}</td>
            `;
          tableBody.appendChild(tr);
        });

        currentOffset += 5;
        updateButtons();
      });

      // Show Less - Kembali ke 5 data awal
      showLessBtn.addEventListener('click', function () {
        const rows = tableBody.querySelectorAll('tr');

        // Sembunyikan semua row kecuali 5 pertama
        rows.forEach((row, index) => {
          if (index >= initialLimit) {
            row.remove(); // Hapus row yang ditambahkan
          }
        });

        currentOffset = initialLimit;
        updateButtons();
      });

      // Inisialisasi tombol
      updateButtons();

      // Sembunyikan tombol jika data kurang dari 5
      if (allData.length <= initialLimit) {
        showMoreBtn.style.display = 'none';
      }
    });
  </script>

</body>

</html>