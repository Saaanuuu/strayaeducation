<?php
require 'koneksi.php';
?>


<!-- Header -->
<?php include_once('template/header.php'); ?>

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
            Voluptatem dignissimos provident laboris nisi ut aliquip ex ea commodo
          </h3>
          <img src="assets/img/about.jpg" class="img-fluid rounded-4 mb-4" alt="" />
          <p>
            Ut fugiat ut sunt quia veniam. Voluptate perferendis perspiciatis quod nisi et.
            Placeat debitis quia recusandae odit et consequatur voluptatem. Dignissimos pariatur
            consectetur fugiat voluptas ea.
          </p>
          <p>
            Temporibus nihil enim deserunt sed ea. Provident sit expedita aut cupiditate nihil
            vitae quo officia vel. Blanditiis eligendi possimus et in cum. Quidem eos ut sint rem
            veniam qui. Ut ut repellendus nobis tempore doloribus debitis explicabo similique sit.
            Accusantium sed ut omnis beatae neque deleniti repellendus.
          </p>
        </div>
        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="250">
          <div class="content ps-0 ps-lg-5">
            <p class="fst-italic">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
            </p>
            <ul>
              <li>
                <i class="bi bi-check-circle-fill"></i>
                <span>Ullamco laboris nisi ut aliquip ex ea commodo consequat.</span>
              </li>
              <li>
                <i class="bi bi-check-circle-fill"></i>
                <span>Duis aute irure dolor in reprehenderit in voluptate velit.</span>
              </li>
              <li>
                <i class="bi bi-check-circle-fill"></i>
                <span>Ullamco laboris nisi ut aliquip ex ea commodo consequat.
                  Duis aute irure dolor in reprehenderit in voluptate trideta
                  storacalaperda mastiro dolore eu fugiat nulla pariatur.</span>
              </li>
            </ul>
            <p>
              Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit
              in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident
            </p>

            <div class="position-relative mt-4">
              <img src="assets/img/about-2.jpg" class="img-fluid rounded-4" alt="" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- /About Section -->

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
<?php include_once('template/footer.php'); ?>