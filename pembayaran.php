<?php
// Include database connection
require 'koneksi.php';
session_start();

// Verify login
if (!isset($_SESSION['id'])) {
    header("Location: administrator/auth/login.php");
    exit;
}

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_bukti'])) {
    $idPendaftaran = $_POST['idPendaftaran'];

    // Check if file was uploaded without errors
    if (isset($_FILES['bukti_bayar']) && $_FILES['bukti_bayar']['error'] === UPLOAD_ERR_OK) {
        $originalName = basename($_FILES["bukti_bayar"]["name"]);
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $fileName = uniqid('bukti_', true) . '.' . $extension;
        $targetDir = "assets/img/bukti_bayar/";
        $targetFilePath = $targetDir . $fileName;

        // Check file type (allow only certain image formats)
        $allowedTypes = ['jpg', 'jpeg', 'png', 'pdf'];
        if (in_array(strtolower($extension), $allowedTypes)) {
            if (move_uploaded_file($_FILES["bukti_bayar"]["tmp_name"], $targetFilePath)) {
                // Update database with new file name
                $updateQuery = "UPDATE pendaftaran SET buktiBayar = ? WHERE idPendaftaran = ?";
                $stmt = $conn->prepare($updateQuery);
                $stmt->bind_param("si", $fileName, $idPendaftaran);

                if ($stmt->execute()) {
                    $_SESSION['success_message'] = "Bukti bayar berhasil diupload!";
                } else {
                    $_SESSION['error_message'] = "Gagal menyimpan data ke database";
                }
            } else {
                $_SESSION['error_message'] = "Maaf, terjadi kesalahan saat mengupload file.";
            }
        } else {
            $_SESSION['error_message'] = "Hanya file JPG, JPEG, PNG & PDF yang diperbolehkan.";
        }
    } else {
        $_SESSION['error_message'] = "Silakan pilih file yang akan diupload.";
    }

    header("Location: pembayaran.php");
    exit;
}

// Handle delete proof of payment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_bukti'])) {
    $idPendaftaran = $_POST['idPendaftaran'];

    // Get the filename from database
    $query = "SELECT buktiBayar FROM pendaftaran WHERE idPendaftaran = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $idPendaftaran);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row && !empty($row['buktiBayar'])) {
        $filePath = "assets/img/bukti_bayar/" . $row['buktiBayar'];

        // Delete file from server
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Update database
        $updateQuery = "UPDATE pendaftaran SET buktiBayar = NULL WHERE idPendaftaran = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("i", $idPendaftaran);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Bukti bayar berhasil dihapus!";
        } else {
            $_SESSION['error_message'] = "Gagal menghapus data dari database";
        }
    } else {
        $_SESSION['error_message'] = "Tidak ada bukti bayar yang bisa dihapus";
    }

    header("Location: pembayaran.php");
    exit;
}

// Function to format price in Rupiah
function formatRupiah($angka)
{
    if (!isset($angka))
        return 'Rp 0';
    $num = intval(preg_replace('/[^0-9]/', '', $angka));
    return 'Rp ' . number_format($num, 0, ',', '.');
}

// Function to parse price data
function parsePriceData($priceProgram)
{
    try {
        if (is_array($priceProgram)) {
            return $priceProgram;
        } elseif (is_string($priceProgram)) {
            return json_decode(str_replace('\\"', '"', $priceProgram), true);
        }
    } catch (Exception $e) {
        error_log("Error parsing priceProgram: " . $e->getMessage());
    }
    return ["350000", "900000"]; // Default values
}

$query = "SELECT 
            pd.idPendaftaran,
            pd.fullName AS nama,
            pd.paymentMethod AS metodePembayaran,
            pr.priceProgram AS hargaData,
            pd.buktiBayar AS buktiBayar,
            pd.waktuProgram,
            pr.namaProgram,
            pr.paketProgram,
            pd.tanggalDaftar
          FROM pendaftaran pd
          JOIN t_program pr ON pd.idProgram = pr.idProgram
          WHERE pd.idUser = ? 
          ORDER BY pd.tanggalDaftar DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STRAYA LANGUAGE INSTITUTE</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="assets/img/straya.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet">

    <style>
        /* Tambahkan style untuk detail pembayaran */
        .payment-detail {
            cursor: pointer;
            color: #0d6efd;
        }

        .payment-detail:hover {
            text-decoration: underline;
        }

        .payment-info {
            margin-top: 15px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .qris-image {
            max-width: 250px;
            margin: 0 auto;
            display: block;
        }

        /* Tambahkan style untuk upload modal */
        .upload-modal .modal-body {
            padding: 20px;
        }

        .upload-area {
            border: 2px dashed #ddd;
            border-radius: 5px;
            padding: 20px;
            text-align: center;
            margin-bottom: 15px;
            cursor: pointer;
        }

        .upload-area:hover {
            border-color: #0d6efd;
        }

        .upload-area i {
            font-size: 48px;
            color: #0d6efd;
            margin-bottom: 10px;
        }

        .preview-image {
            max-width: 100%;
            max-height: 200px;
            margin-top: 15px;
        }
    </style>
</head>

<body class="index-page">
    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="container-fluid container-xl position-relative d-flex align-items-center">
            <a href="index.php" class="logo d-flex align-items-center me-auto">
                <img src="assets/img/straya.png" alt="">
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <?php if (isset($_SESSION['login'])): ?>
                        <li class="nav-item dropdown d-flex align-items-center">
                            <div class="text-truncate fw-bold text-white">
                                Hello, <?= htmlspecialchars($_SESSION["fullName"]) ?>
                            </div>
                            <div class="profile-picture bg-light" id="userDropdown">
                                <i class="fas fa-user"></i>
                            </div>
                            <ul class="dropdown-menu dropdown-menu-end" id="dropdownMenu">
                                <li><a class="dropdown-item" href="index.php"><i class="fas fa-home me-2"></i> Home</a></li>
                                <li><a class="dropdown-item" href="riwayat.php"><i class="fas fa-history me-2"></i>
                                        Riwayat</a></li>
                                <li><a class="dropdown-item" href="administrator/logout.php"><i
                                            class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>
        </div>
    </header>

    <main class="main">
        <!-- Page Title -->
        <div class="page-title dark-background" style="background-image: url(assets/img/page-title-bg.webp);">
            <div class="container position-relative">
                <h1>Pembayaran</h1>
                <nav class="breadcrumbs">
                    <ol>
                        <li><a href="index.php">Home</a></li>
                        <li class="current">Pembayaran</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Payment Section -->
        <section id="pembayaran" class="pembayaran section">
            <div class="container section-title" data-aos="fade-up">
                <h2>Riwayat Pembayaran</h2>
            </div>

            <!-- Notifikasi -->
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="container">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= $_SESSION['success_message'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="container">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $_SESSION['error_message'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-dark">
                            <tr class="text-center">
                                <th>No</th>
                                <th>Tanggal Daftar</th>
                                <th>Nama</th>
                                <th>Metode Pembayaran</th>
                                <th>Harga</th>
                                <th>Bukti Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows === 0) {
                                echo '<tr><td colspan="5" class="text-center">Belum ada riwayat pembayaran</td></tr>';
                            } else {
                                $no = 1;
                                while ($row = $result->fetch_assoc()) {
                                    // Parse price data
                                    $priceOptions = parsePriceData($row['hargaData']);

                                    // Determine selected package index based on waktuProgram
                                    $selectedPaketIndex = 0;
                                    $waktuExclusive = ["15.00 WITA", "17.00 WITA", "14.00 WITA", "16.00 WITA"];
                                    if (in_array($row['waktuProgram'], $waktuExclusive)) {
                                        $selectedPaketIndex = 1;
                                    }

                                    // Get the selected price
                                    $harga = $priceOptions[$selectedPaketIndex] ?? $priceOptions[0] ?? 0;

                                    echo "<tr>
                                        <td class='text-center'>$no</td>
                                        <td class='text-center'>" . date('d M Y H:i', strtotime($row['tanggalDaftar'])) . "</td>
                                        <td class='text-center'>" . htmlspecialchars($row['nama']) . "</td>
                                        <td class='text-center'>
                                            <div>" . htmlspecialchars($row['metodePembayaran']) . "</div>
                                            <div class='payment-detail' onclick='showPaymentDetail(\"" . htmlspecialchars($row['metodePembayaran']) . "\", $no)'>
                                                <i class='fas fa-info-circle me-1'></i>Detail
                                            </div>
                                            <div id='paymentDetail$no' class='payment-info' style='display:none;'>
                                                " . getPaymentDetailHtml($row['metodePembayaran']) . "
                                            </div>
                                        </td>
                                        <td class='harga-cell text-center'>" . formatRupiah($harga) . "</td>
                                        <td class='text-center'>";

                                    // Display proof of payment
                                    if (!empty($row['buktiBayar'])) {
                                        $filePath = "assets/img/bukti_bayar/" . htmlspecialchars($row['buktiBayar']);
                                        if (file_exists($filePath)) {
                                            $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                                            if (in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif'])) {
                                                echo "
                                                <div class='d-flex flex-column align-items-center'>
                                                    <a href='#' data-bs-toggle='modal' data-bs-target='#imageModal$no'>
                                                        <img src='$filePath' style='max-height:50px;' class='img-thumbnail mb-1'>
                                                    </a>
                                                    <form method='POST' class='delete-form'>
                                                        <input type='hidden' name='idPendaftaran' value='" . $row['idPendaftaran'] . "'>
                                                        <button type='submit' name='delete_bukti' class='btn btn-sm btn-outline-danger' onclick='return confirm(\"Yakin ingin menghapus bukti bayar?\")'>
                                                            <i class='fas fa-trash me-1'></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>";
                                            } else {
                                                echo "
                                                <div class='d-flex flex-column align-items-center'>
                                                    <a href='$filePath' target='_blank' class='btn btn-sm btn-outline-primary mb-1'>
                                                        <i class='fas fa-file-download me-1'></i> Download
                                                    </a>
                                                    <form method='POST' class='delete-form'>
                                                        <input type='hidden' name='idPendaftaran' value='" . $row['idPendaftaran'] . "'>
                                                        <button type='submit' name='delete_bukti' class='btn btn-sm btn-outline-danger' onclick='return confirm(\"Yakin ingin menghapus bukti bayar?\")'>
                                                            <i class='fas fa-trash me-1'></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>";
                                            }
                                        } else {
                                            echo htmlspecialchars($row['buktiBayar']);
                                        }
                                    } else {
                                        echo "
                                        <button class='btn btn-sm btn-outline-primary' data-bs-toggle='modal' data-bs-target='#uploadModal$no'>
                                            <i class='fas fa-upload me-1'></i> Upload
                                        </button>";
                                    }

                                    echo "</td>
                                          </tr>";

                                    // Modal untuk preview gambar
                                    if (!empty($row['buktiBayar']) && file_exists("assets/img/bukti_bayar/" . $row['buktiBayar'])) {
                                        $fileExtension = pathinfo($row['buktiBayar'], PATHINFO_EXTENSION);
                                        if (in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif'])) {
                                            echo '
                                            <div class="modal fade" id="imageModal' . $no . '" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Bukti Pembayaran</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <img src="assets/img/bukti_bayar/' . htmlspecialchars($row['buktiBayar']) . '" class="img-fluid">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>';
                                        }
                                    }

                                    // Modal untuk upload bukti bayar
                                    echo '
                                    <div class="modal fade" id="uploadModal' . $no . '" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST" enctype="multipart/form-data">
                                                    <input type="hidden" name="idPendaftaran" value="' . $row['idPendaftaran'] . '">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Upload Bukti Bayar</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="upload-area" id="dropArea' . $no . '">
                                                            <i class="fas fa-cloud-upload-alt"></i>
                                                            <h5>Seret file ke sini atau klik untuk memilih</h5>
                                                            <p class="text-muted">Format: JPG, JPEG, PNG, PDF (Maks. 5MB)</p>
                                                            <input type="file" name="bukti_bayar" id="fileInput' . $no . '" class="d-none" accept=".jpg,.jpeg,.png,.pdf" required>
                                                        </div>
                                                        <div id="previewContainer' . $no . '" class="text-center mt-3"></div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" name="upload_bukti" class="btn btn-primary">Upload</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>';

                                    $no++;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

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
                            <a href="https://www.facebook.com/straya.institute" target="_blank"><i
                                    class="bi bi-facebook"></i></a>
                            <a href="https://www.instagram.com/straya.institute" target="_blank"><i
                                    class="bi bi-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container copyright text-center mt-4">
                <p>© <span>Copyright</span> <strong class="px-1 sitename">STRAYA LANGUAGE INSTITUTE</strong> <span>All
                        Rights Reserved</span></p>
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

        <!-- Main JS File -->
        <script src="assets/js/main.js"></script>

        <script>
            // Fungsi untuk menampilkan detail pembayaran
            function showPaymentDetail(method, rowId) {
                const detailElement = document.getElementById(`paymentDetail${rowId}`);
                if (detailElement.style.display === 'none') {
                    detailElement.style.display = 'block';
                } else {
                    detailElement.style.display = 'none';
                }
            }

            // Handle file selection and preview for each modal
            document.querySelectorAll('.modal').forEach(modal => {
                const modalId = modal.id.replace('uploadModal', '');
                if (modalId) {
                    const dropArea = document.getElementById(`dropArea${modalId}`);
                    const fileInput = document.getElementById(`fileInput${modalId}`);
                    const previewContainer = document.getElementById(`previewContainer${modalId}`);

                    // Click on drop area to trigger file input
                    if (dropArea) dropArea.addEventListener('click', () => fileInput.click());

                    // Handle file selection
                    if (fileInput) fileInput.addEventListener('change', (e) => {
                        const file = e.target.files[0];
                        if (file) previewFile(file, modalId);
                    });

                    // Drag and drop functionality
                    if (dropArea) {
                        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                            dropArea.addEventListener(eventName, preventDefaults, false);
                        });

                        ['dragenter', 'dragover'].forEach(eventName => {
                            dropArea.addEventListener(eventName, highlight, false);
                        });

                        ['dragleave', 'drop'].forEach(eventName => {
                            dropArea.addEventListener(eventName, unhighlight, false);
                        });

                        dropArea.addEventListener('drop', (e) => {
                            const dt = e.dataTransfer;
                            const file = dt.files[0];
                            fileInput.files = dt.files;
                            if (file) previewFile(file, modalId);
                        });
                    }
                }
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            function highlight(e) {
                e.currentTarget.classList.add('bg-light');
            }

            function unhighlight(e) {
                e.currentTarget.classList.remove('bg-light');
            }

            // Preview the selected file
            function previewFile(file, modalId) {
                const previewContainer = document.getElementById(`previewContainer${modalId}`);
                previewContainer.innerHTML = '';

                const fileType = file.type;
                const validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];

                if (validImageTypes.includes(fileType)) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const preview = document.createElement('img');
                        preview.src = e.target.result;
                        preview.className = 'preview-image img-thumbnail';
                        previewContainer.appendChild(preview);
                    };
                    reader.readAsDataURL(file);
                } else if (fileType === 'application/pdf') {
                    const preview = document.createElement('div');
                    preview.innerHTML = `
                    <div class="alert alert-info">
                        <i class="fas fa-file-pdf me-2"></i> 
                        ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)
                    </div>`;
                    previewContainer.appendChild(preview);
                } else {
                    previewContainer.innerHTML = `
                    <div class="alert alert-warning">
                        Format file tidak didukung. Harap upload file gambar (JPG, PNG) atau PDF.
                    </div>`;
                }
            }
        </script>

</body>

</html>

<?php
// Fungsi untuk menghasilkan HTML detail pembayaran
function getPaymentDetailHtml($paymentMethod)
{
    if ($paymentMethod === 'QRIS') {
        return '
            <div class="text-center">
                <img src="assets/img/qris2.png" alt="QRIS Payment" class="qris-image">
            </div>';
    } elseif ($paymentMethod === 'Transfer Bank') {
        return '
            <div class="text-center">
                <p>Silahkan transfer ke rekening:</p>
                <p><strong>BCA 1234567890</strong></p>
                <p>a.n. <strong>Straya Institute</strong></p>
            </div>';
    } else {
        return '<p class="text-center">Tidak ada informasi pembayaran tambahan</p>';
    }
}
?>