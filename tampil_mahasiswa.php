<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';

// Konfigurasi Pagination
$records_per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

// Validasi opsi records per page
$allowed_per_page = [10, 25, 50, 100];
if (!in_array($records_per_page, $allowed_per_page)) {
    $records_per_page = 10;
}

// Hitung offset
$offset = ($page - 1) * $records_per_page;

// Hitung total records
$total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM tbl_mahasiswa");
$total_row = mysqli_fetch_assoc($total_query);
$total_records = $total_row['total'];

// Hitung total halaman
$total_pages = ceil($total_records / $records_per_page);

// Ambil data dengan pagination
$result = mysqli_query($conn, "SELECT * FROM tbl_mahasiswa ORDER BY nama ASC LIMIT $offset, $records_per_page");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa - SIM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="favicon-96x96.png" sizes="96x96" />
    <!-- SweetAlert CSS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --dark: #212529;
            --light: #f8f9fa;
        }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background-color: #f5f7fa;
            overflow-x: hidden;
        }

        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            left: 0;
            top: 0;
            background: linear-gradient(180deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            transition: all 0.3s;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .sidebar-menu a {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1.5rem;
            margin: 0.25rem 0;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar-menu a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            transition: all 0.3s;
        }

        .navbar {
            padding: 0.75rem 1.5rem;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .content-wrapper {
            padding: 1.5rem;
        }

        .table-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            overflow-x: auto;
        }

        .table th {
            background-color: var(--primary);
            color: white;
            vertical-align: middle;
        }

        .table td {
            vertical-align: middle;
        }

        .user-profile {
            display: flex;
            align-items: center;
        }

        .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
        }

        .student-photo {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }

        .btn-action {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        /* Pagination Styles */
        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
            padding: 0.5rem 0;
        }

        .per-page-selector {
            display: flex;
            align-items: center;
        }

        .per-page-selector label {
            margin-right: 0.5rem;
            margin-bottom: 0;
        }

        .per-page-selector select {
            width: 80px;
        }

        .pagination .page-item.active .page-link {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .pagination .page-link {
            color: var(--primary);
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar.active {
                width: 250px;
            }

            .main-content.active {
                margin-left: 250px;
            }

            .pagination-container {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h4 class="mb-0">SIM Mahasiswa</h4>
            <small class="text-white-50">Administrator Panel</small>
        </div>

        <div class="sidebar-menu">
            <a href="dashboard_admin.php">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="tampil_mahasiswa.php" class="active">
                <i class="fas fa-users"></i> Data Mahasiswa
            </a>
            <a href="tampil_user.php">
                <i class="fas fa-user-cog"></i> Manajemen User
            </a>
            <a href="logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white">
            <div class="container-fluid">
                <button class="btn btn-sm d-lg-none" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="d-flex align-items-center ms-auto">
                    <div class="user-profile me-3">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['username']); ?>&background=random" alt="User">
                        <span><?php echo $_SESSION['username']; ?></span>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Content -->
        <div class="content-wrapper">
            <div class="container-fluid">
                <?php if (isset($_SESSION['success_msg'])): ?>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: '<?php echo $_SESSION['success_msg']; ?>',
                            confirmButtonText: 'OK'
                        });
                    </script>
                <?php unset($_SESSION['success_msg']);
                endif; ?>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">Data Mahasiswa</h3>
                    <a href="tambah_mahasiswa.php" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Mahasiswa
                    </a>
                </div>

                <div class="table-container">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>NPM</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th width="10%">Foto</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = $offset + 1;
                            while ($data = mysqli_fetch_assoc($result)) {
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($data['npm']); ?></td>
                                    <td><?= htmlspecialchars($data['nama']); ?></td>
                                    <td><?= htmlspecialchars($data['alamat']); ?></td>
                                    <td class="text-center">
                                        <?php if ($data['foto'] != '') { ?>
                                            <img src="uploads/<?= htmlspecialchars($data['foto']); ?>" class="student-photo" alt="Foto Mahasiswa">
                                        <?php } else { ?>
                                            <span class="text-muted">No photo</span>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="edit_mahasiswa.php?id=<?= $data['idMhs']; ?>" class="btn btn-warning btn-sm btn-action">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="javascript:void(0);" class="btn btn-danger btn-sm btn-action" onclick="confirmDelete(<?= $data['idMhs']; ?>)">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="pagination-container">
                        <div class="per-page-selector">
                            <label for="per_page">Data per halaman:</label>
                            <select class="form-select form-select-sm" id="per_page" onchange="changePerPage(this.value)">
                                <option value="10" <?= $records_per_page == 10 ? 'selected' : '' ?>>10</option>
                                <option value="25" <?= $records_per_page == 25 ? 'selected' : '' ?>>25</option>
                                <option value="50" <?= $records_per_page == 50 ? 'selected' : '' ?>>50</option>
                                <option value="100" <?= $records_per_page == 100 ? 'selected' : '' ?>>100</option>
                            </select>
                        </div>

                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?page=<?= $page - 1 ?>&per_page=<?= $records_per_page ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>

                                <?php
                                // Tampilkan nomor halaman (dibatasi 5 di sekitar halaman aktif)
                                $start_page = max(1, $page - 2);
                                $end_page = min($total_pages, $page + 2);

                                if ($start_page > 1) {
                                    echo '<li class="page-item"><a class="page-link" href="?page=1&per_page=' . $records_per_page . '">1</a></li>';
                                    if ($start_page > 2) {
                                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                    }
                                }

                                for ($i = $start_page; $i <= $end_page; $i++) {
                                    echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '">';
                                    echo '<a class="page-link" href="?page=' . $i . '&per_page=' . $records_per_page . '">' . $i . '</a>';
                                    echo '</li>';
                                }

                                if ($end_page < $total_pages) {
                                    if ($end_page < $total_pages - 1) {
                                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                    }
                                    echo '<li class="page-item"><a class="page-link" href="?page=' . $total_pages . '&per_page=' . $records_per_page . '">' . $total_pages . '</a></li>';
                                }
                                ?>

                                <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?page=<?= $page + 1 ?>&per_page=<?= $records_per_page ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar on mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.main-content').classList.toggle('active');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');

            if (window.innerWidth <= 768 &&
                !sidebar.contains(event.target) &&
                event.target !== sidebarToggle &&
                !sidebarToggle.contains(event.target)) {
                sidebar.classList.remove('active');
                document.querySelector('.main-content').classList.remove('active');
            }
        });

        // Function untuk mengubah jumlah data per halaman
        function changePerPage(value) {
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('per_page', value);
            urlParams.set('page', 1); // Kembali ke halaman pertama saat mengubah jumlah data
            window.location.search = urlParams.toString();
        }

        // Fungsi konfirmasi hapus dengan SweetAlert
        function confirmDelete(idMhs) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'hapus_mahasiswa.php?id=' + idMhs;
                }
            });
        }
    </script>
</body>

</html>