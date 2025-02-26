<?php
require 'koneksi.php';

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
$waktu = json_decode($program['waktuProgram'], true);
$benefits = json_decode($program['benefitProgram'], true);
?>


<!-- Header -->
<?php include_once('template/header.php'); ?>

<main class="main">

  <!-- Page Title -->
  <div class="page-title dark-background" data-aos="fade" style="background-image: url(assets/img/page-title-bg.webp);">
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
          <img src="assets/img/fileImage/<?php echo htmlspecialchars($program['gambarProgram']); ?>" class="img-fluid" alt="">
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
          <?php foreach ($pakets as $index => $paket) : ?>
            <div class="program-info" data-aos="fade-up" data-aos-delay="200">
              <h3><?php echo htmlspecialchars($paket); ?></h3>
              <ul>
                <li><strong>Harga Program</strong>: <?php echo htmlspecialchars($price[$index] ?? 'Tidak tersedia'); ?></li>
                
                <br>
                <li><strong>Waktu Program</strong>:</li>
                <ul>
                  <?php if (!empty($waktu[$index])) : ?>
                    <?php foreach ($waktu[$index] as $w) : ?>
                      <li><i class='bi bi-clock'></i> <span><?php echo htmlspecialchars($w); ?></span></li>
                    <?php endforeach; ?>
                  <?php else : ?>
                    <li><i class='bi bi-x-circle'></i> <span>Tidak tersedia</span></li>
                  <?php endif; ?>
                </ul>
                <br>
                <li><strong>Benefit Program</strong>:</li>
                <ul>
                  <?php if (!empty($benefits[$index])) : ?>
                    <?php foreach ($benefits[$index] as $benefit) : ?>
                      <li><i class='bi bi-check-circle'></i> <span><?php echo htmlspecialchars($benefit); ?></span></li>
                    <?php endforeach; ?>
                  <?php else : ?>
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
<?php include_once('template/footer.php'); ?>