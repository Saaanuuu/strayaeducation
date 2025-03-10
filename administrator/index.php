<?php
include '../koneksi.php';

session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] != "1") {
    header("Location: login.php?alert=not_logged_in");
    exit;
}

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
        echo "Error: " . $SQL . "<br>" . mysqli_error($conn);
    }
    mysqli_close($conn);
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
            WHERE   id='$id'";

    if (mysqli_query($conn, $SQL)) {
        header("Location: index.php#manage");
        exit();
    } else {
        echo "Error: " . $SQL . "<br>" . mysqli_error($conn);
    }
}

if (isset($_POST['delete'])) {
    $id = $_POST['id'];

    $SQL = "DELETE FROM t_user WHERE id='$id'";
    if (mysqli_query($conn, $SQL)) {
        header("Location: index.php#manage");
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
    <link href="../assets/img/straya.png" rel="icon" />
    <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon" />

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
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
        <div
            class="container-fluid container-xl position-relative d-flex align-items-center">
            <a href="../administrator/index.php" class="logo d-flex align-items-center me-auto">
                <!-- Uncomment the line below if you also wish to use an image logo -->
                <img src="../assets/img/straya.png" alt="" />
                <!-- <h1 class="sitename">STRAYA LANGUAGE INSTITUTE</h1> -->
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="index.php#hero">Home</a></li>
                    <li><a href="index.php#manage">Manage User</a></li>
                    <li><a href="../administrator/beasiswa/viewBeasiswa.php">Beasiswa</a></li>
                    <li><a href="../administrator/program/viewProgram.php">Program</a></li>
                    <li class="nav-item dropdown d-flex align-items-center">
                        <span class="me-2 fw-bold text-white">Hello, <?= $_SESSION["fullName"] ?></span>
                        <div class="profile-picture bg-light" id="userDropdown">
                            <i class="fas fa-user"></i>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end" id="dropdownMenu">
                            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
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
                                        $query = mysqli_query($conn, 'SELECT * FROM t_user');

                                        while ($t_user = mysqli_fetch_array($query)) {
                                        ?>
                                            <tr>
                                                <td style="text-align: center;"><?php echo $no++; ?></td>
                                                <td><?php echo $t_user['fullName']; ?></td>
                                                <td><?php echo $t_user['email']; ?></td>
                                                <td>
                                                    <a href="#modalEditAdmin<?php echo $t_user['id']; ?>" data-bs-toggle="modal" title="Edit" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a>
                                                    <a href="#modalDeleteAdmin<?php echo $t_user['id']; ?>" data-bs-toggle="modal" title="Hapus" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
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
                            <input type="text" name="fullName" id="fullName" class="form-control" placeholder="Full Name" autocomplete="off" required="">
                        </div>
                        <br>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" name="email" id="email" class="form-control" placeholder="Email" autocomplete="off" required="">
                        </div>
                        <br>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Password" autocomplete="off" required="">
                        </div>
                    </div>
                    <div class="modal-footer no-bd">
                        <button type="submit" name="save" class="btn btn-primary"><i class="fa fa-save"></i>Save</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-undo"></i> Close</button>
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
                                <input value="<?php echo $d['fullName'] ?>" type="text" name="fullName" id="fullName_<?php echo $d['id'] ?>" class="form-control" placeholder="Full Name" autocomplete="off" required="">
                            </div>
                            <br>
                            <div class="form-group">
                                <label>Email</label>
                                <input value="<?php echo $d['email'] ?>" type="text" name="email" id="email_<?php echo $d['id'] ?>" class="form-control" placeholder="Email" autocomplete="off" required="">
                            </div>
                        </div>
                        <div class="modal-footer no-bd">
                            <button type="submit" name="update" class="btn btn-primary"><i class="fa fa-save"></i>Simpan</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-undo"></i> Tutup</button>
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
        <div class="modal fade" id="modalDeleteAdmin<?php echo $row['id'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
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
                            <button type="submit" name="delete" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-undo"></i> Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>
    <!-- End Modal Delete Admin -->

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
                        <!-- <a href=""><i class="bi bi-twitter-x"></i></a> -->
                        <a href="https://www.facebook.com/straya.institute" target="_blank"><i class="bi bi-facebook"></i></a>
                        <a href="https://www.instagram.com/straya.institute" target="_blank"><i class="bi bi-instagram"></i></a>
                        <!-- <a href=""><i class="bi bi-linkedin"></i></a> -->
                    </div>
                </div>

                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Menu</h4>
                    <ul>
                        <li><i class="bi bi-chevron-right"></i> <a href="../administrator/index.php#hero">Home</a></li>
                        <li>
                            <i class="bi bi-chevron-right"></i> <a href="../administrator/index.php#manage">Manage User</a>
                        </li>
                        <li>
                            <i class="bi bi-chevron-right"></i> <a href="../administrator/beasiswa/viewBeasiswa.php">Beasiswa</a>
                        </li>
                        <li>
                            <i class="bi bi-chevron-right"></i> <a href="../administrator/program/viewProgram.php">Program</a>
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
</body>

</html>