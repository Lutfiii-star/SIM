<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

$username = $_SESSION['username'];
$data = mysqli_query($conn, "SELECT * FROM tbl_mahasiswa WHERE npm='$username'");
$mahasiswa = mysqli_fetch_assoc($data);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mahasiswa - SIM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="favicon-96x96.png" sizes="96x96" />
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #28a745;
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

        .profile-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .profile-header {
            background-color: var(--primary);
            color: white;
            padding: 1.5rem;
        }

        .profile-body {
            padding: 2rem;
        }

        .profile-photo {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 10px;
            border: 3px solid white;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .info-table {
            width: 100%;
        }

        .info-table th {
            width: 30%;
            padding: 0.75rem;
            font-weight: 500;
            color: #6c757d;
        }

        .info-table td {
            padding: 0.75rem;
            border-bottom: 1px solid #eee;
        }

        .btn-edit {
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-edit:hover {
            transform: translateY(-2px);
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
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h4 class="mb-0">SIM Mahasiswa</h4>
            <small class="text-white-50">Mahasiswa Panel</small>
        </div>

        <div class="sidebar-menu">
            <a href="dashboard_user.php" class="active">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="edit_profile_user.php">
                <i class="fas fa-user-edit"></i> Edit Profil
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
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($mahasiswa['nama']); ?>&background=random" alt="Profile">
                        <span><?php echo $mahasiswa['nama']; ?></span>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Content -->
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">Dashboard Mahasiswa</h3>
                </div>

                <div class="row">
                    <div class="col-lg-4 mb-4">
                        <div class="profile-card text-center">
                            <div class="profile-header">
                                <?php if ($mahasiswa['foto']) { ?>
                                    <img src="uploads/<?php echo $mahasiswa['foto']; ?>" class="profile-photo mb-3">
                                <?php } else { ?>
                                    <div class="profile-photo mb-3 bg-light d-flex align-items-center justify-content-center mx-auto">
                                        <i class="fas fa-user fa-3x text-muted"></i>
                                    </div>
                                <?php } ?>
                                <h4><?php echo $mahasiswa['nama']; ?></h4>
                                <p class="mb-0"><?php echo $mahasiswa['npm']; ?></p>
                            </div>
                            <div class="profile-body">
                                <a href="edit_profile_user.php" class="btn btn-primary btn-edit w-100">
                                    <i class="fas fa-user-edit me-2"></i> Edit Profil
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="profile-card">
                            <div class="profile-header">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Profil</h5>
                            </div>
                            <div class="profile-body">
                                <table class="info-table">
                                    <tr>
                                        <th>NPM</th>
                                        <td><?php echo $mahasiswa['npm']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Nama Lengkap</th>
                                        <td><?php echo $mahasiswa['nama']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Alamat</th>
                                        <td><?php echo $mahasiswa['alamat']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <span class="badge bg-success">Aktif</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
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
    </script>
    <?php if (isset($_SESSION['success_msg'])): ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    <?php if (isset($_SESSION['login_success'])): ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Login Berhasil!',
                text: '<?php echo $_SESSION['login_success']; ?>',
                confirmButtonText: 'Lanjut'
            });
        </script>
    <?php unset($_SESSION['login_success']);
    endif; ?>

</body>

</html>