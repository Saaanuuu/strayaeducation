<?php
session_start();
require '../../koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php?alert=not_logged_in");
    exit;
}

if (isset($_POST['save'])) {
    $namaBeasiswa = htmlspecialchars($_POST['namaBeasiswa']);
    $visiBeasiswa = htmlspecialchars($_POST['visiBeasiswa']);
    $misiBeasiswa = htmlspecialchars($_POST['misiBeasiswa']);
    $motoBeasiswa = htmlspecialchars($_POST['motoBeasiswa']);
    $deskripsiBeasiswa = htmlspecialchars($_POST['deskripsiBeasiswa']);
    $benefitBeasiswa = json_encode($_POST['benefitBeasiswa']);

    // Upload Gambar
    $targetDir = "../../assets/img/fileImage/";
    $fileName = basename($_FILES["gambar"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    $allowedTypes = array("jpg", "jpeg", "png", "gif");
    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $targetFilePath)) {
            $query = "INSERT INTO t_beasiswa (namaBeasiswa, visiBeasiswa, misiBeasiswa, motoBeasiswa, deskripsiBeasiswa, benefitBeasiswa, gambarBeasiswa) 
                    VALUES ('$namaBeasiswa', '$visiBeasiswa', '$misiBeasiswa', '$motoBeasiswa', '$deskripsiBeasiswa', '$benefitBeasiswa', '$fileName')";

            if (mysqli_query($conn, $query)) {
                header("Location: viewBeasiswa.php");
                exit();
            } else {
                echo "Error: " . $query . "<br>" . mysqli_error($conn);
            }
        } else {
            echo "Gagal mengupload gambar.";
        }
    } else {
        echo "Format gambar tidak didukung. Gunakan JPG, JPEG, PNG, atau GIF.";
    }
    mysqli_close($conn);
}

if (isset($_POST['update'])) {
    $idBeasiswa = $_POST['idBeasiswa'];
    $namaBeasiswa = htmlspecialchars($_POST['namaBeasiswa']);
    $visiBeasiswa = htmlspecialchars($_POST['visiBeasiswa']);
    $misiBeasiswa = htmlspecialchars($_POST['misiBeasiswa']);
    $motoBeasiswa = htmlspecialchars($_POST['motoBeasiswa']);
    $deskripsiBeasiswa = htmlspecialchars($_POST['deskripsiBeasiswa']);
    $benefitBeasiswa = json_encode($_POST['benefitBeasiswa']);

    $targetDir = "../../assets/img/fileImage/";
    $fileName = $_FILES["gambar"]["name"] ? basename($_FILES["gambar"]["name"]) : null;
    $targetFilePath = $fileName ? $targetDir . $fileName : null;
    $fileType = $fileName ? strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION)) : null;

    $allowedTypes = array("jpg", "jpeg", "png", "gif");

    if ($fileName) {
        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $targetFilePath)) {
                // Hapus gambar lama
                $queryOldImage = "SELECT gambarBeasiswa FROM t_beasiswa WHERE idBeasiswa='$idBeasiswa'";
                $resultOldImage = mysqli_query($conn, $queryOldImage);
                $oldImage = mysqli_fetch_assoc($resultOldImage)['gambarBeasiswa'];

                if (!empty($oldImage) && file_exists($targetDir . $oldImage)) {
                    unlink($targetDir . $oldImage);
                }

                $SQL = "UPDATE t_beasiswa SET 
                        namaBeasiswa='$namaBeasiswa', 
                        visiBeasiswa='$visiBeasiswa', 
                        misiBeasiswa='$misiBeasiswa', 
                        motoBeasiswa='$motoBeasiswa', 
                        deskripsiBeasiswa='$deskripsiBeasiswa',
                        benefitBeasiswa='$benefitBeasiswa',
                        gambarBeasiswa='$fileName' 
                        WHERE idBeasiswa='$idBeasiswa'";
            } else {
                echo "Gagal mengupload gambar.";
                exit();
            }
        } else {
            echo "Format gambar tidak didukung. Gunakan JPG, JPEG, PNG, atau GIF.";
            exit();
        }
    } else {
        $SQL = "UPDATE t_beasiswa SET 
                namaBeasiswa='$namaBeasiswa', 
                visiBeasiswa='$visiBeasiswa', 
                misiBeasiswa='$misiBeasiswa', 
                motoBeasiswa='$motoBeasiswa', 
                deskripsiBeasiswa='$deskripsiBeasiswa',
                benefitBeasiswa='$benefitBeasiswa'
                WHERE idBeasiswa='$idBeasiswa'";
    }

    if (mysqli_query($conn, $SQL)) {
        header("Location: viewBeasiswa.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}

if (isset($_POST['delete'])) {
    $idBeasiswa = $_POST['idBeasiswa'];

    $queryImage = "SELECT gambarBeasiswa FROM t_beasiswa WHERE idBeasiswa='$idBeasiswa'";
    $resultImage = mysqli_query($conn, $queryImage);
    $image = mysqli_fetch_assoc($resultImage)['gambarBeasiswa'];

    $targetDir = "../../assets/img/fileImage/";
    if (!empty($image) && file_exists($targetDir . $image)) {
        unlink($targetDir . $image);
    }

    $SQL = "DELETE FROM t_beasiswa WHERE idBeasiswa='$idBeasiswa'";
    if (mysqli_query($conn, $SQL)) {
        header("Location: viewBeasiswa.php");
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
                <h1>List Beasiswa</h1>
                <nav class="breadcrumbs">
                    <ol>
                        <li><a href="../index.php">Home</a></li>
                        <li class="current">List Beasiswa</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Page Title -->

        <!-- Beasiswa Details Section -->
        <section id="beasiswa-details" class="beasiswa-details section">
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <?php
                                    echo '<div class="col-md-8">';
                                    echo '<h4 class="card-title">List Beasiswa</h4>';
                                    echo '</div>';
                                    echo '<div class="col-md-4 d-flex justify-content-end">';
                                    echo '<button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalAddBeasiswa">';
                                    echo '<i class="fa fa-plus"></i>';
                                    echo '&nbsp';
                                    echo 'Add New Beasiswa';
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
                                            <th style="text-align: center;">Nama Beasiswa</th>
                                            <th style="text-align: center;">Gambar</th>
                                            <th style="text-align: center;" width="8.3%">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $query = mysqli_query($conn, 'SELECT * FROM t_beasiswa');

                                        while ($t_beasiswa = mysqli_fetch_array($query)) {
                                            ?>
                                            <tr>
                                                <td style="text-align: center;"><?php echo $no++; ?></td>
                                                <td style="text-align: center;"><?php echo $t_beasiswa['namaBeasiswa']; ?>
                                                </td>
                                                <td style="text-align: center;">
                                                    <img src="../../assets/img/fileImage/<?php echo $t_beasiswa['gambarBeasiswa']; ?>"
                                                        alt="Beasiswa Image" width="300">
                                                </td>
                                                <td style="text-align: center;">
                                                    <div class="btn-group" role="group">
                                                        <a href="#modalEditBeasiswa<?php echo $t_beasiswa['idBeasiswa']; ?>"
                                                            data-bs-toggle="modal" title="Edit"
                                                            class="btn btn-xs btn-primary">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <a href="#modalDeleteBeasiswa<?php echo $t_beasiswa['idBeasiswa']; ?>"
                                                            data-bs-toggle="modal" title="Delete"
                                                            class="btn btn-xs btn-danger">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                        <a href="viewDetailBeasiswa.php?id=<?php echo $t_beasiswa['idBeasiswa']; ?>"
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
        <!-- /Beasiswa Details Section -->
    </main>

    <!-- Modal Add New Beasiswa -->
    <div class="modal fade" id="modalAddBeasiswa" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header no-bd">
                    <h5 class="modal-title">Add New Beasiswa</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Beasiswa</label>
                            <input type="text" name="namaBeasiswa" id="namaBeasiswa" class="form-control"
                                placeholder="Nama Beasiswa" autocomplete="off" required="">
                        </div>
                        <br>
                        <div class="form-group">
                            <label>Visi Lembaga</label>
                            <input type="text" name="visiBeasiswa" id="visiBeasiswa" class="form-control"
                                placeholder="Visi Lembaga" autocomplete="off" required="">
                        </div>
                        <br>
                        <div class="form-group">
                            <label>Misi Lembaga</label>
                            <input type="text" name="misiBeasiswa" id="misiBeasiswa" class="form-control"
                                placeholder="Misi Lembaga" autocomplete="off" required="">
                        </div>
                        <br>
                        <div class="form-group">
                            <label>Moto Lembaga</label>
                            <input type="text" name="motoBeasiswa" id="motoBeasiswa" class="form-control"
                                placeholder="Moto Lembaga" autocomplete="off" required="">
                        </div>
                        <br>
                        <div class="form-group">
                            <label>Deskripsi Beasiswa</label>
                            <input type="text" name="deskripsiBeasiswa" id="deskripsiBeasiswa" class="form-control"
                                placeholder="Deskripsi Beasiswa" autocomplete="off" required="">
                        </div>
                        <br>
                        <div class="form-group">
                            <label>Benefit Beasiswa</label>
                            <div id="benefit-container" class="benefit-container">
                                <div class="benefit-group d-flex mb-2">
                                    <input type="text" name="benefitBeasiswa[]" class="form-control benefit-input"
                                        placeholder="Benefit Beasiswa" required>
                                    <button type="button" class="btn btn-danger btn-sm remove-benefit ms-2"
                                        style="display: none;">Hapus</button>
                                </div>
                            </div>
                            <button type="button" id="add-benefit" class="btn btn-success btn-sm mt-2">Tambah
                                Benefit</button>
                        </div>
                        <br>
                        <div class="form-group">
                            <label>Upload Gambar:</label>
                            <input type="file" id="gambar" name="gambar" multiple>
                        </div>
                    </div>
                    <div class="modal-footer no-bd">
                        <button type="submit" name="save" class="btn btn-primary"><i
                                class="fa fa-save"></i>Save</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-undo"></i>
                            Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal Add New Beasiswa -->

    <!-- Modal Edit Beasiswa -->
    <?php
    $p = mysqli_query($conn, 'SELECT * from t_beasiswa');
    while ($d = mysqli_fetch_array($p)) {
        ?>
        <div class="modal fade" id="modalEditBeasiswa<?php echo $d['idBeasiswa'] ?>" tabindex="-1" role="dialog"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header no-bd">
                        <h5 class="modal-title">Edit Beasiswa</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                            <input type="hidden" name="idBeasiswa" value="<?php echo $d['idBeasiswa'] ?>">

                            <div class="form-group">
                                <label>Nama Beasiswa</label>
                                <input type="text" name="namaBeasiswa" class="form-control"
                                    value="<?php echo $d['namaBeasiswa'] ?>" placeholder="Nama Beasiswa" autocomplete="off"
                                    required>
                            </div>
                            <br>

                            <div class="form-group">
                                <label>Visi Lembaga</label>
                                <input type="text" name="visiBeasiswa" class="form-control"
                                    value="<?php echo $d['visiBeasiswa'] ?>" placeholder="Visi Lembaga" autocomplete="off"
                                    required>
                            </div>
                            <br>

                            <div class="form-group">
                                <label>Misi Lembaga</label>
                                <input type="text" name="misiBeasiswa" class="form-control"
                                    value="<?php echo $d['misiBeasiswa'] ?>" placeholder="Misi Lembaga" autocomplete="off"
                                    required>
                            </div>
                            <br>

                            <div class="form-group">
                                <label>Moto Lembaga</label>
                                <input type="text" name="motoBeasiswa" class="form-control"
                                    value="<?php echo $d['motoBeasiswa'] ?>" placeholder="Moto Lembaga" autocomplete="off"
                                    required>
                            </div>
                            <br>

                            <div class="form-group">
                                <label>Deskripsi Beasiswa</label>
                                <input type="text" name="deskripsiBeasiswa" class="form-control"
                                    value="<?php echo $d['deskripsiBeasiswa'] ?>" placeholder="Deskripsi Beasiswa"
                                    autocomplete="off" required>
                            </div>
                            <br>

                            <div class="form-group">
                                <label>Benefit Beasiswa</label>
                                <div class="benefit-container" id="benefit-container-<?php echo $d['idBeasiswa'] ?>">
                                    <?php
                                    $benefits = json_decode($d['benefitBeasiswa'], true);
                                    if (!empty($benefits)) {
                                        foreach ($benefits as $index => $benefit) {
                                            echo '<div class="benefit-group d-flex mb-2">
                                              <input type="text" name="benefitBeasiswa[]" class="form-control benefit-input" value="' . htmlspecialchars($benefit) . '" required>
                                              <button type="button" class="btn btn-danger btn-sm ms-2 remove-benefit" ' . ($index == 0 ? 'style="display: none;"' : '') . '>Hapus</button>
                                          </div>';
                                        }
                                    } else {
                                        echo '<div class="benefit-group d-flex mb-2">
                                          <input type="text" name="benefitBeasiswa[]" class="form-control benefit-input" placeholder="Benefit Beasiswa" required>
                                          <button type="button" class="btn btn-danger btn-sm ms-2 remove-benefit" style="display: none;">Hapus</button>
                                      </div>';
                                    }
                                    ?>
                                </div>
                                <button type="button" class="btn btn-success btn-sm mt-2 add-benefit"
                                    data-id="<?php echo $d['idBeasiswa'] ?>">Tambah Benefit</button>
                            </div>
                            <br>

                            <div class="form-group">
                                <label>Upload Gambar:</label>
                                <input type="file" name="gambar" class="form-control">
                                <br>
                                <?php
                                if (!empty($d['gambarBeasiswa'])) {
                                    echo "<img src='../../assets/img/fileImage/" . htmlspecialchars($d['gambarBeasiswa']) . "' width='100px' alt='Gambar'>";
                                } else {
                                    echo "<p>Tidak ada gambar yang tersedia.</p>";
                                }
                                ?>
                            </div>
                        </div>
                        <div class="modal-footer no-bd">
                            <button type="submit" name="update" class="btn btn-primary"><i class="fa fa-save"></i>
                                Simpan</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-undo"></i>
                                Tutup</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>
    <!-- End Modal Edit Beasiswa -->

    <!-- Modal Delete Beasiswa -->
    <?php
    $c = mysqli_query($conn, 'SELECT * from t_beasiswa');
    while ($row = mysqli_fetch_array($c)) {
        ?>
        <div class="modal fade" id="modalDeleteBeasiswa<?php echo $row['idBeasiswa'] ?>" tabindex="-1" role="dialog"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header no-bd">
                        <h5 class="modal-title">
                            <span class="fw-mediumbold">Delete Beasiswa</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" enctype="multipart/form-data" action="">
                        <div class="modal-body">
                            <input type="hidden" name="idBeasiswa" value="<?php echo $row['idBeasiswa'] ?>">
                            <h4>Are you sure to remove this Beasiswa ?</h4>
                        </div>
                        <div class="modal-footer no-bd">
                            <button type="submit" name="delete" class="btn btn-danger"><i class="fa fa-trash"></i>
                                Delete</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-undo"></i>
                                Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>
    <!-- End Modal Delete Beasiswa -->

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