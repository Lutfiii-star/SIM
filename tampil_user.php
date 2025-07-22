<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
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

// Jika ada aksi ubah role
if (isset($_POST['ubah_role'])) {
    $username = $_POST['username'];
    $role = $_POST['role'];

    $query = mysqli_query($conn, "UPDATE tbl_user SET role='$role' WHERE username='$username'");

    if ($query) {
        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'Role berhasil diubah'
        ];
    } else {
        $_SESSION['alert'] = [
            'type' => 'error',
            'message' => 'Gagal mengubah role'
        ];
    }
    header("Location: tampil_user.php?page=$page&per_page=$records_per_page");
    exit;
}

// Jika ada aksi hapus user
if (isset($_GET['hapus'])) {
    $username = $_GET['hapus'];

    // Hapus dari tbl_user
    $query1 = mysqli_query($conn, "DELETE FROM tbl_user WHERE username='$username'");

    // Hapus dari tbl_mahasiswa jika ada
    $query2 = mysqli_query($conn, "DELETE FROM tbl_mahasiswa WHERE npm='$username'");

    if ($query1) {
        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'User berhasil dihapus'
        ];
    } else {
        $_SESSION['alert'] = [
            'type' => 'error',
            'message' => 'Gagal menghapus user'
        ];
    }
    header("Location: tampil_user.php?page=$page&per_page=$records_per_page");
    exit;
}

// Hitung total records
$total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM tbl_user");
$total_row = mysqli_fetch_assoc($total_query);
$total_records = $total_row['total'];

// Hitung total halaman
$total_pages = ceil($total_records / $records_per_page);

// Ambil data dengan pagination
$data = mysqli_query($conn, "SELECT * FROM tbl_user ORDER BY username ASC LIMIT $offset, $records_per_page");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - SIM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="icon" type="image/png" href="favicon-96x96.png" sizes="96x96" />
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --danger: #dc3545;
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

        .role-badge {
            padding: 0.35rem 0.5rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .role-admin {
            background-color: #f8d7da;
            color: #842029;
        }

        .role-user {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .btn-action {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .btn-delete {
            background-color: var(--danger);
            border-color: var(--danger);
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
            <a href="tampil_mahasiswa.php">
                <i class="fas fa-users"></i> Data Mahasiswa
            </a>
            <a href="tampil_user.php" class="active">
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">Manajemen User & Role</h3>
                </div>

                <div class="table-container">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Username</th>
                                <th width="15%">Role</th>
                                <th width="30%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = $offset + 1;
                            while ($user = mysqli_fetch_assoc($data)) {
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($user['username']); ?></td>
                                    <td>
                                        <span class="role-badge <?= $user['role'] == 'admin' ? 'role-admin' : 'role-user'; ?>">
                                            <?= ucfirst($user['role']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <form method="POST" class="d-flex gap-2 flex-grow-1">
                                                <input type="hidden" name="username" value="<?= htmlspecialchars($user['username']); ?>">
                                                <select name="role" class="form-select form-select-sm" required>
                                                    <option value="user" <?= $user['role'] == 'user' ? 'selected' : ''; ?>>User</option>
                                                    <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                                </select>
                                                <button type="submit" name="ubah_role" class="btn btn-sm btn-primary btn-action">
                                                    <i class="fas fa-save"></i>
                                                </button>
                                            </form>
                                            <button class="btn btn-sm btn-danger btn-action btn-delete"
                                                data-username="<?= htmlspecialchars($user['username']); ?>">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
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

    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        // Delete user confirmation
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function() {
                const username = this.getAttribute('data-username');

                Swal.fire({
                    title: 'Hapus User?',
                    text: `Anda yakin ingin menghapus user ${username}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `tampil_user.php?hapus=${username}&page=<?= $page ?>&per_page=<?= $records_per_page ?>`;
                    }
                });
            });
        });

        // Function untuk mengubah jumlah data per halaman
        function changePerPage(value) {
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('per_page', value);
            urlParams.set('page', 1); // Kembali ke halaman pertama saat mengubah jumlah data
            window.location.search = urlParams.toString();
        }

        // Display any PHP session alerts
        <?php if (isset($_SESSION['alert'])): ?>
            Swal.fire({
                icon: '<?php echo $_SESSION['alert']['type']; ?>',
                title: '<?php echo $_SESSION['alert']['type'] == 'success' ? 'Berhasil' : 'Gagal'; ?>',
                text: '<?php echo $_SESSION['alert']['message']; ?>',
                confirmButtonColor: '#4361ee'
            });
            <?php unset($_SESSION['alert']); ?>
        <?php endif; ?>
    </script>
</body>

</html>