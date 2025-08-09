<?php
require 'koneksi.php';
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
  <link href="assets/css/main.css" rel="stylesheet" />
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

    <!-- Page Title -->
    <div class="page-title dark-background" data-aos="fade"
      style="background-image: url(assets/img/page-title-bg.webp);">
      <div class="container position-relative">
        <h1>Program Details</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.php">Home</a></li>
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
            <img src="assets/img/fileImage/<?php echo htmlspecialchars($program['gambarProgram']); ?>" class="img-fluid"
              alt="">
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
                  <li><strong>Harga Program</strong>: <?php echo htmlspecialchars($price[$index] ?? 'Tidak tersedia'); ?>
                  </li>

                  <br>
                  <li><strong>Kuota Program</strong>: <?php echo htmlspecialchars($kuota[$index] ?? 'Tidak tersedia'); ?>
                    Orang</li>

                  <br>
                  <li><strong>Waktu Program</strong>:</li>
                  <ul>
                    <?php if (!empty($waktu[$index])): ?>
                      <?php foreach ($waktu[$index] as $w): ?>
                        <li><i class='bi bi-clock'></i> <span><?php echo htmlspecialchars($w); ?></span></li>
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
                        <li><i class='bi bi-check-circle'></i> <span><?php echo htmlspecialchars($benefit); ?></span></li>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <li><i class='bi bi-x-circle'></i> <span>Tidak tersedia</span></li>
                    <?php endif; ?>
                  </ul>
                  <?php if (isset($_SESSION['login']) && $_SESSION['login'] === true): ?>
                    <br>
                    <li>
                      <button class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#programRegistrationModal">Daftar
                        Program</button>
                    </li>
                  <?php endif; ?>
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

  <!-- Modal Pendaftaran Program -->
  <div class="modal fade" id="programRegistrationModal" tabindex="-1" aria-labelledby="programRegistrationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="programRegistrationModalLabel">Form Pendaftaran Program
            <?php echo htmlspecialchars($program['namaProgram']); ?>
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="programRegistrationForm">
            <!-- Form fields -->
            <div class="mb-3">
              <label for="fullName" class="form-label">Nama Lengkap</label>
              <input type="text" class="form-control" id="fullName"
                value="<?php echo isset($_SESSION['fullName']) ? htmlspecialchars($_SESSION['fullName']) : ''; ?>"
                required>
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email"
                value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>" required>
            </div>

            <div class="mb-3">
              <label for="phone" class="form-label">Nomor HP</label>
              <input type="tel" class="form-control" id="phone" required>
            </div>

            <div class="mb-3">
              <label for="address" class="form-label">Alamat</label>
              <textarea class="form-control" id="address" rows="3" required></textarea>
            </div>

            <div class="mb-3">
              <label for="waktuProgram" class="form-label">Waktu Program</label>
              <select class="form-select" id="waktuProgram" required>
                <option value="" selected disabled>Pilih waktu program</option>
                <?php foreach ($pakets as $index => $paket): ?>
                  <?php if (!empty($waktu[$index]) && isset($price[$index]) && isset($kuota[$index])): ?>
                    <?php foreach ($waktu[$index] as $w): ?>
                      <?php if (is_array($w))
                        continue; // Skip if $w is an array (unexpected structure) ?>
                      <option value="<?php echo htmlspecialchars($w); ?>" data-paket="<?php echo htmlspecialchars($paket); ?>"
                        data-harga="<?php echo htmlspecialchars($price[$index] * 1000); ?>"
                        data-kuota="<?php echo htmlspecialchars($kuota[$index]); ?>" <?php echo ($kuota[$index] <= 0) ? 'disabled' : ''; ?>>
                        <?php echo htmlspecialchars($paket) . ' - ' . htmlspecialchars($w); ?>
                        <?php if ($kuota[$index] <= 0): ?>
                          (KUOTA HABIS)
                        <?php endif; ?>
                      </option>
                    <?php endforeach; ?>
                  <?php endif; ?>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Tambahkan div pembayaran yang awalnya tersembunyi -->
            <div id="paymentMethodSection" style="display: none;">
              <div class="mb-3">
                <label for="paymentMethod" class="form-label">Metode Pembayaran</label>
                <select class="form-select" id="paymentMethod" name="paymentMethod" required>
                  <option value="" selected disabled>Pilih metode pembayaran</option>
                  <option value="QRIS">QRIS</option>
                  <option value="Transfer Bank">Transfer Bank</option>
                </select>
              </div>

              <!-- <div id="qrisImage" class="text-center mt-1" style="display: none;">
                <img src="assets/img/qris2.png" alt="QRIS Payment" class="img-fluid mb-1" style="max-width: 350px;">
                <p class="small text-muted mt-6 mb-3">Scan QR code di atas untuk melakukan pembayaran</p>
              </div> -->
            </div>

            <!-- Teks Transfer Bank (muncul hanya jika Transfer Bank dipilih) -->
            <!-- <div id="bankTransferInfo" class="text-center mt-3" style="display: none;">
              <p class="text-muted">Silahkan transfer ke rekening: <strong><br>BCA 1234567890</strong> a.n.
                <strong>Straya
                  Institute</strong>
              </p>
            </div> -->

            <div class="mb-3">
              <div class="form-text" id="infoProgramDynamic">
                <strong>Informasi Program:</strong><br>
                Silahkan pilih Waktu Program terlebih dahulu.
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="button" class="btn btn-primary" id="submitRegistration">Daftar Sekarang</button>
        </div>
      </div>
    </div>
  </div>

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

  <!-- Script untuk Modal Pendaftaran -->
  <!-- Form Pendaftaran -->


  <!-- Script JavaScript -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const formFields = ['fullName', 'email', 'phone', 'address', 'waktuProgram'];
      const paymentSection = document.getElementById('paymentMethodSection');
      const paymentMethod = document.getElementById('paymentMethod');
      // const qrisImage = document.getElementById('qrisImage');
      // const bankTransferInfo = document.getElementById('bankTransferInfo'); // pastikan ID ini ada
      const submitButton = document.getElementById('submitRegistration');
      const infoDiv = document.getElementById('infoProgramDynamic');
      const waktuSelect = document.getElementById('waktuProgram');

      // Fungsi untuk memeriksa kelengkapan form
      function checkFormCompletion() {
        let isComplete = true;

        formFields.forEach(fieldId => {
          const field = document.getElementById(fieldId);
          if (!field.value.trim()) {
            isComplete = false;
          }
        });

        // Tampilkan/sembunyikan bagian pembayaran
        paymentSection.style.display = isComplete ? 'block' : 'none';

        // Nonaktifkan tombol submit jika belum lengkap
        if (submitButton) {
          submitButton.disabled = !isComplete || (isComplete && !paymentMethod.value);
        }
      }

      // Tampilkan gambar QRIS atau info transfer bank
      if (paymentMethod) {
        paymentMethod.addEventListener('change', function () {
          const selectedMethod = this.value;

          // qrisImage.style.display = selectedMethod === 'QRIS' ? 'block' : 'none';
          // bankTransferInfo.style.display = selectedMethod === 'Transfer Bank' ? 'block' : 'none';

          if (submitButton) {
            submitButton.disabled = !selectedMethod;
          }
        });
      }

      // Update info program secara dinamis saat user memilih waktu program
      if (waktuSelect && infoDiv) {
        waktuSelect.addEventListener('change', function () {
          const selectedOption = this.options[this.selectedIndex];
          const paket = selectedOption.getAttribute('data-paket');
          const harga = selectedOption.getAttribute('data-harga');
          const kuota = selectedOption.getAttribute('data-kuota');

          if (parseInt(kuota) <= 0) {
            alert("Maaf, kuota untuk program ini sudah habis");
            submitButton.disabled = true;
          } else {
            submitButton.disabled = false;
          }

          infoDiv.innerHTML = `
          <strong>Informasi Program:</strong><br>
          Nama Program: <?php echo htmlspecialchars($program['namaProgram']); ?><br>
            Paket: ${paket}<br>
            Harga: Rp. ${parseInt(harga).toLocaleString('id-ID')}<br>
            Kuota: ${kuota} Peserta
          `;
        });
      }

      // Tambahkan event listener untuk setiap field
      formFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
          field.addEventListener('input', checkFormCompletion);
          field.addEventListener('change', checkFormCompletion);
        }
      });

      // Handler untuk tombol submit
      if (submitButton) {
        submitButton.addEventListener('click', function (event) {
          event.preventDefault();

          // Validasi akhir sebelum submit
          let isValid = true;
          const allFields = [...formFields, 'paymentMethod'];

          allFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (!field.value.trim()) {
              isValid = false;
              field.classList.add('is-invalid');
            } else {
              field.classList.remove('is-invalid');
            }
          });

          if (!isValid) {
            alert("Mohon lengkapi semua data sebelum mendaftar.");
            return;
          }

          // Kirim data ke server
          const formData = new FormData();
          formData.append('fullName', document.getElementById('fullName').value);
          formData.append('email', document.getElementById('email').value);
          formData.append('phone', document.getElementById('phone').value);
          formData.append('address', document.getElementById('address').value);
          formData.append('waktuProgram', document.getElementById('waktuProgram').value);
          formData.append('paymentMethod', document.getElementById('paymentMethod').value);
          formData.append('idProgram', "<?php echo htmlspecialchars($idProgram); ?>");
          formData.append('idUser', "<?php echo $_SESSION['id']; ?>");

          fetch('pendaftaran.php', {
            method: 'POST',
            body: formData
          })
            .then(response => response.text())
            .then(result => {
              alert(result);
              document.getElementById('programRegistrationForm').reset();
              paymentSection.style.display = 'none';
              // qrisImage.style.display = 'none';
              // bankTransferInfo.style.display = 'none';
              // Reset info program
              if (infoProgramDynamic) {
                infoProgramDynamic.style.display = 'block';
                infoProgramDynamic.innerHTML = `
                <strong>Informasi Program:</strong><br>
                Silahkan pilih Waktu Program terlebih dahulu.
                `;
              }

              if (submitButton) submitButton.disabled = true;

              const modal = bootstrap.Modal.getInstance(document.getElementById('programRegistrationModal'));
              modal.hide();
            })
            .catch(error => {
              console.error('Error:', error);
              alert('Terjadi kesalahan saat mendaftar.');
            });
        });
      }
    });
  </script>


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

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>
</body>

</html>