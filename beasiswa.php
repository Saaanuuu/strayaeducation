<?php
require 'koneksi.php';

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
<?php include_once('template/header.php'); ?>

<main class="main">

  <!-- Page Title -->
  <div class="page-title dark-background" data-aos="fade" style="background-image: url(assets/img/page-title-bg.webp);">
    <div class="container position-relative">
      <h1>Beasiswa Details</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="index.php">Home</a></li>
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
          <img src="assets/img/fileImage/<?php echo htmlspecialchars($beasiswa['gambarBeasiswa']); ?>" alt="Gambar Beasiswa" class="img-fluid beasiswa-img">
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
<?php include_once('template/footer.php'); ?>