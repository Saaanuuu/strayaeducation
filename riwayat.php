<?php
require 'koneksi.php';
session_start();

// Verifikasi login
if (!isset($_SESSION['id'])) {
    header("Location: administrator/auth/login.php");
    exit;
}

// Query untuk mendapatkan riwayat pendaftaran
$query = "SELECT 
            pd.idPendaftaran,
            pd.idProgram,
            pd.idUser,
            pd.fullName,
            pd.email,
            pd.phone,
            pd.waktuProgram,
            pd.paymentMethod,
            pd.tanggalDaftar,
            pd.statusPendaftaran,
            pd.score,
            pd.scoreBefore,
            pr.namaProgram,
            pr.paketProgram,
            pr.priceProgram,
            pr.kuotaProgram,
            pr.waktuProgram as programWaktu
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
        .modal-body h6 {
            font-weight: 600;
            color: #444;
            margin-bottom: 0.25rem;
        }

        .modal-body .text-muted {
            font-size: 0.95rem;
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-warning {
            background-color: #ffc107;
        }

        .badge-danger {
            background-color: #dc3545;
        }

        .badge-secondary {
            background-color: #6c757d;
        }

        /* Style untuk invoice */
        .invoice-header {
            background-color: #0d6efd;
            color: white;
            padding: 1rem;
            border-radius: 5px 5px 0 0;
        }

        .invoice-section {
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 1rem;
        }

        .invoice-section h4 {
            color: #0d6efd;
            margin-bottom: 1rem;
        }

        .invoice-table {
            width: 100%;
        }

        .invoice-table td {
            padding: 0.5rem 0;
            vertical-align: top;
        }

        .invoice-table tr:last-child td {
            border-bottom: none;
        }

        .total-row {
            font-weight: bold;
            background-color: #f8f9fa;
        }

        .package-table {
            width: 100%;
            margin-bottom: 1rem;
        }

        .package-table th,
        .package-table td {
            padding: 0.5rem;
            border: 1px solid #dee2e6;
        }

        .package-table .table-active {
            background-color: rgba(13, 110, 253, 0.1);
        }

        /* Add new styles */
        .btn-outline-primary.bg-transparent {
            background-color: transparent !important;
            border-color: #0d6efd;
            color: #0d6efd;
        }

        .btn-outline-primary.bg-transparent:hover {
            background-color: #0d6efd !important;
            color: white;
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
                                <li><a class="dropdown-item" href="pembayaran.php"><i
                                            class="fas fa-money-bill-wave me-2"></i>
                                        Pembayaran</a></li>
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
                <h1>Riwayat Daftar</h1>
                <nav class="breadcrumbs">
                    <ol>
                        <li><a href="index.php">Home</a></li>
                        <li class="current">Riwayat Daftar</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Riwayat Section -->
        <section id="riwayat" class="riwayat section">
            <div class="container section-title" data-aos="fade-up">
                <h2>Riwayat Pendaftaran</h2>
            </div>

            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <!-- Alert Messages -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <?= $_SESSION['success'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <?= $_SESSION['error'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-dark">
                            <tr class="text-center">
                                <th>No</th>
                                <th>Tanggal Daftar</th>
                                <th>Program</th>
                                <th>Waktu</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Score Awal</th>
                                <th>Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows === 0) {
                                echo '<tr><td colspan="8" class="text-center">Belum ada data pendaftaran</td></tr>';
                            } else {
                                $no = 1;
                                while ($row = $result->fetch_assoc()) {
                                    // Format status
                                    $statusText = htmlspecialchars($row['statusPendaftaran'] ?? '-');
                                    $statusClass = match ($statusText) {
                                        'Diterima' => 'success',
                                        'Ditolak' => 'danger',
                                        default => 'secondary'
                                    };

                                    // Format scoreBefore
                                    $scoreBefore = $row['scoreBefore'] ?? null;
                                    $scoreBeforeText = '-';
                                    $scoreBeforeClass = 'secondary';
                                    $isToefl = stripos($row['namaProgram'], 'TOEFL') !== false;
                                    $isIelts = stripos($row['namaProgram'], 'IELTS') !== false;

                                    if (!empty($scoreBefore) && $scoreBefore !== '-') {
                                        $scoreBeforeText = htmlspecialchars($scoreBefore);

                                        if ($isToefl) {
                                            // TOEFL validation (0-600, no decimals)
                                            $scoreBeforeInt = intval($scoreBefore);
                                            $scoreBeforeClass = match (true) {
                                                $scoreBeforeInt >= 500 => 'success',
                                                $scoreBeforeInt >= 400 => 'warning',
                                                default => 'danger'
                                            };
                                        } elseif ($isIelts) {
                                            // IELTS validation (0.0-9.0, with one decimal)
                                            $scoreBeforeFloat = floatval($scoreBefore);
                                            $scoreBeforeClass = match (true) {
                                                $scoreBeforeFloat >= 6.5 => 'success',
                                                $scoreBeforeFloat >= 5.5 => 'warning',
                                                default => 'danger'
                                            };
                                        } else {
                                            // Default validation for other programs (0-100)
                                            $scoreBeforeFloat = floatval($scoreBefore);
                                            $scoreBeforeClass = match (true) {
                                                $scoreBeforeFloat >= 80 => 'success',
                                                $scoreBeforeFloat >= 60 => 'warning',
                                                default => 'danger'
                                            };
                                        }
                                    }

                                    // Format score
                                    $score = $row['score'] ?? null;
                                    $scoreText = '-';
                                    $scoreClass = 'secondary';
                                    $isToefl = stripos($row['namaProgram'], 'TOEFL') !== false;
                                    $isIelts = stripos($row['namaProgram'], 'IELTS') !== false;

                                    if (!empty($score) && $score !== '-') {
                                        if ($isToefl) {
                                            // Validasi TOEFL (0-600 tanpa desimal)
                                            $scoreInt = intval($score);
                                            $scoreText = $scoreInt;
                                            $scoreClass = match (true) {
                                                $scoreInt >= 500 => 'success',  // Advanced
                                                $scoreInt >= 400 => 'warning',  // Intermediate
                                                default => 'danger'             // Basic
                                            };
                                        } elseif ($isIelts) {
                                            // Validasi IELTS (0.0-9.0 dengan 1 desimal)
                                            $scoreFloat = floatval($score);
                                            $scoreText = number_format($scoreFloat, 1);
                                            $scoreClass = match (true) {
                                                $scoreFloat >= 6.5 => 'success',  // Competent
                                                $scoreFloat >= 5.5 => 'warning',  // Modest
                                                default => 'danger'               // Limited
                                            };
                                        } else {
                                            // Default scoring untuk program lain (0-100)
                                            $defaultScore = intval($score);
                                            $scoreText = $defaultScore;
                                            $scoreClass = match (true) {
                                                $defaultScore >= 80 => 'success',
                                                $defaultScore >= 60 => 'warning',
                                                default => 'danger'
                                            };
                                        }
                                    }

                                    echo "<tr>
                                            <td class='text-center'>$no</td>
                                            <td class='text-center'>" . date('d M Y H:i', strtotime($row['tanggalDaftar'])) . "</td>
                                            <td class='text-center'>" . htmlspecialchars($row['namaProgram']) . "</td>
                                            <td class='text-center'>" . htmlspecialchars($row['waktuProgram']) . "</td>
                                            <td class='text-center'>" . htmlspecialchars($row['paymentMethod']) . "</td>
                                            <td class='text-center'>";

                                    if ($statusText === 'Diterima') {
                                        echo "<div class='d-flex flex-column align-items-center'>
                                                <span class='badge bg-$statusClass mb-1'>$statusText</span>
                                                <button class='btn btn-sm btn-outline-primary bg-transparent' onclick='showDetailModal(" . htmlspecialchars(json_encode($row)) . ", event)'>
                                                    <i class='fas fa-file-invoice me-1'></i> Detail
                                                </button>
                                              </div>";
                                    } else {
                                        echo "<span class='badge bg-$statusClass'>$statusText</span>";
                                    }

                                    echo "</td>
                                            <td class='text-center'><span class='badge bg-$scoreBeforeClass'>$scoreBeforeText</span></td>
                                            <td class='text-center'><span class='badge bg-$scoreClass'>$scoreText</span></td>
                                          </tr>";

                                    $no++;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Detail Pendaftaran Modal -->
        <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header invoice-header">
                        <h5 class="modal-title" id="detailModalLabel">INVOICE PENDAFTARAN PROGRAM</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="invoice-section">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Informasi Peserta</h4>
                                    <table class="invoice-table">
                                        <tr>
                                            <td width="40%"><strong>Nama</strong></td>
                                            <td id="modalNama">-</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email</strong></td>
                                            <td id="modalEmail">-</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nomor HP</strong></td>
                                            <td id="modalPhone">-</td>
                                        </tr>
                                        <tr>
                                            <td><strong>ID Pendaftaran</strong></td>
                                            <td id="modalIdPendaftaran">-</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Score Awal</strong></td>
                                            <td id="modalScoreBefore">-</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h4>Detail Invoice</h4>
                                    <table class="invoice-table">
                                        <tr>
                                            <td width="40%"><strong>No Invoice</strong></td>
                                            <td id="modalNoInvoice">-</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal Invoice</strong></td>
                                            <td id="modalTanggalInvoice">-</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status Pendaftaran</strong></td>
                                            <td><span class="badge" id="modalStatusPendaftaran">-</span></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="invoice-section">
                            <h4>Program yang Diikuti</h4>
                            <table class="invoice-table">
                                <tr>
                                    <td width="30%"><strong>Nama Program</strong></td>
                                    <td id="modalProgram">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Paket Program</strong></td>
                                    <td id="modalPaketProgram">
                                        <div class="table-responsive">
                                            <table class="package-table">
                                                <thead>
                                                    <tr>
                                                        <th>Paket</th>
                                                        <th>Harga</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="packageOptionsBody">
                                                    <!-- Will be filled by JavaScript -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Waktu Program</strong></td>
                                    <td id="modalWaktuProgram">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Daftar</strong></td>
                                    <td id="modalTanggalDaftar">-</td>
                                </tr>
                            </table>
                        </div>

                        <div class="invoice-section">
                            <h4>Rincian Biaya</h4>
                            <table class="invoice-table">
                                <tr class="total-row">
                                    <td><strong>Total Pembayaran</strong></td>
                                    <td><strong id="modalTotalBiaya">-</strong></td>
                                </tr>
                            </table>
                        </div>

                        <div class="invoice-section">
                            <h4>Metode Pembayaran</h4>
                            <table class="invoice-table">
                                <tr>
                                    <td width="30%"><strong>Metode</strong></td>
                                    <td id="modalMetodePembayaran">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Status Pembayaran</strong></td>
                                    <td><span class="badge bg-success" id="modalStatusPembayaran">LUNAS</span></td>
                                </tr>
                            </table>
                        </div>

                        <div class="alert alert-info mt-4">
                            <i class="fas fa-info-circle me-2"></i> Simpan invoice ini sebagai bukti pendaftaran.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" onclick="printInvoice()">
                            <i class="fas fa-print me-2"></i> Cetak Invoice
                        </button>
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
            // Format Rupiah helper function
            function formatRupiah(angka) {
                if (!angka && angka !== 0) return 'Rp 0';
                const num = parseInt(angka.toString().replace(/\D/g, ''));
                return 'Rp ' + num.toLocaleString('id-ID');
            }

            // Format Tanggal helper function
            function formatTanggal(dateString) {
                if (!dateString) return '-';
                const options = {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                };
                return new Date(dateString).toLocaleDateString('id-ID', options);
            }

            // Show detail modal function - SIMPLIFIED VERSION
            function showDetailModal(data, event) {
                if (event) event.stopPropagation();

                // Parse package data
                let paketOptions = [];
                try {
                    if (Array.isArray(data.paketProgram)) {
                        paketOptions = data.paketProgram;
                    } else if (data.paketProgram) {
                        paketOptions = JSON.parse(data.paketProgram.replace(/\\"/g, '"'));
                    }
                } catch (e) {
                    console.error("Error parsing paketProgram:", e);
                    paketOptions = ["Regular (8 Pertemuan)", "Exclusive (24 Pertemuan)"];
                }

                // Determine selected package
                let selectedPaketIndex = 0;
                const waktuProgram = data.waktuProgram || '';
                const waktuExclusive = ["15.00 WITA", "17.00 WITA", "14.00 WITA", "16.00 WITA"];
                if (waktuExclusive.includes(waktuProgram)) {
                    selectedPaketIndex = 1;
                }

                // Parse price data
                let priceOptions = [];
                try {
                    if (Array.isArray(data.priceProgram)) {
                        priceOptions = data.priceProgram;
                    } else if (data.priceProgram) {
                        priceOptions = JSON.parse(data.priceProgram.replace(/\\"/g, '"'));
                    }
                } catch (e) {
                    console.error("Error parsing priceProgram:", e);
                    priceOptions = ["350000", "900000"];
                }

                // Display basic information
                document.getElementById('modalNama').textContent = data.fullName || '-';
                document.getElementById('modalEmail').textContent = data.email || '-';
                document.getElementById('modalPhone').textContent = data.phone || '-';
                document.getElementById('modalIdPendaftaran').textContent = data.idPendaftaran ? 'STRAYA-' + data.idPendaftaran : '-';
                document.getElementById('modalNoInvoice').textContent = data.idPendaftaran ? `INV/${new Date(data.tanggalDaftar).getFullYear()}/${data.idPendaftaran}` : '-';
                document.getElementById('modalTanggalInvoice').textContent = formatTanggal(new Date());
                document.getElementById('modalProgram').textContent = data.namaProgram || '-';
                document.getElementById('modalWaktuProgram').textContent = waktuProgram || '-';
                document.getElementById('modalTanggalDaftar').textContent = formatTanggal(data.tanggalDaftar);
                document.getElementById('modalScoreBefore').textContent = data.scoreBefore || '-';

                // Display only the selected package (no table)
                document.getElementById('modalPaketProgram').textContent = paketOptions[selectedPaketIndex] || 'Paket tidak tersedia';

                // Display prices
                document.getElementById('modalTotalBiaya').textContent = formatRupiah(priceOptions[selectedPaketIndex] || '0');
                document.getElementById('modalMetodePembayaran').textContent = data.paymentMethod || '-';

                // Status and score
                const statusElement = document.getElementById('modalStatusPendaftaran');
                statusElement.textContent = data.statusPendaftaran || '-';
                statusElement.className = 'badge bg-' + (
                    data.statusPendaftaran === 'Diterima' ? 'success' :
                        data.statusPendaftaran === 'Ditolak' ? 'danger' : 'warning'
                );

                // Show modal
                new bootstrap.Modal(document.getElementById('detailModal')).show();
            }

            function printInvoice() {
                const modalContent = document.querySelector('#detailModal .modal-content').cloneNode(true);
                const printWindow = window.open('', '_blank');

                // Hapus header & footer modal (jangan ikut tercetak)
                const modalFooter = modalContent.querySelector('.modal-footer');
                const modalHeader = modalContent.querySelector('.modal-header');
                if (modalFooter) modalFooter.remove();
                if (modalHeader) modalHeader.remove();

                printWindow.document.write(`
        <html>
            <head>
                <title>Invoice Pendaftaran</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body {
                        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                        padding: 40px;
                        background: #fff;
                        color: #333;
                    }

                    .invoice-header {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        margin-bottom: 30px;
                    }

                    .invoice-title {
                        font-size: 1.75rem;
                        font-weight: 600;
                        color: #0d6efd;
                    }

                    .logo-container img {
                        width: 100px;
                        height: auto;
                        border: none;
                        background: none;
                        box-shadow: none;
                    }

                    .invoice-section-title {
                        font-size: 1.2rem;
                        font-weight: bold;
                        color: #0d6efd;
                        margin-top: 40px;
                        margin-bottom: 15px;
                        border-bottom: 1px solid #dee2e6;
                        padding-bottom: 5px;
                    }

                    .invoice-table td {
                        padding: 0.4rem;
                        vertical-align: top;
                    }

                    .invoice-footer {
                        margin-top: 50px;
                        font-size: 0.9rem;
                        text-align: center;
                        color: #777;
                        border-top: 1px solid #ccc;
                        padding-top: 10px;
                    }

                    .modal-body h4 {
                        margin-top: 30px !important;
                        margin-bottom: 15px !important;
                        color: #0d6efd;
                        border-bottom: 1px solid #dee2e6;
                        padding-bottom: 5px;
                        font-size: 1.2rem;
                    }

                    /* ✨ Tambahkan ini untuk samakan jarak antar isi data */
                    .modal-body p,
                    .modal-body .row,
                    .modal-body .mb-2 {
                        margin-bottom: 10px !important;
                    }

                    @media print {
                        body {
                            padding: 0;
                            margin: 0;
                        }
                        .invoice-container {
                            padding: 0;
                            border: none;
                        }
                    }
                </style>
            </head>
            <body>
                <div class="invoice-container">
                    <div class="invoice-header">
                        <div class="invoice-title">Invoice Pendaftaran</div>
                        <div class="logo-container">
                            <img src="assets/img/straya.png" alt="STRAYA Logo">
                        </div>
                    </div>
                    ${modalContent.innerHTML}
                    <div class="invoice-footer">
                        &copy; ${new Date().getFullYear()} STRAYA Language Institute. All rights reserved.
                    </div>
                </div>
                <script>
                    window.onload = function() {
                        window.print();
                        setTimeout(function() {
                            window.close();
                        }, 1000);
                    };
                <\/script>
            </body>
        </html>
    `);
                printWindow.document.close();
            }
        </script>
    </main>
</body>

</html>