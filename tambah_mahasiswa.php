<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';

if (isset($_POST['simpan'])) {
    $npm = $_POST['npm'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];

    // Check if NPM already exists
    $check = mysqli_query($conn, "SELECT * FROM tbl_mahasiswa WHERE npm='$npm'");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['error_msg'] = 'NPM sudah terdaftar';
        header("Location: tambah_mahasiswa.php");
        exit();
    }

    // Upload foto
    $foto = '';
    if ($_FILES['foto']['name'] != '') {
        $foto = uniqid() . '_' . $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], 'uploads/' . $foto);
    }

    // Simpan ke tbl_mahasiswa
    mysqli_query($conn, "INSERT INTO tbl_mahasiswa (npm, nama, alamat, foto) VALUES ('$npm', '$nama', '$alamat', '$foto')");

    // Buat akun user otomatis
    $username = $npm;
    $password = md5($npm . '!!'); // Password = NPM+!!
    $role = 'user';
    mysqli_query($conn, "INSERT INTO tbl_user (username, password, role) VALUES ('$username', '$password', '$role')");

    $_SESSION['success_msg'] = 'Data mahasiswa berhasil ditambahkan';
    header("Location: tampil_mahasiswa.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mahasiswa - SIM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="favicon-96x96.png" sizes="96x96" />
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

        .form-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            padding: 2rem;
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .form-control,
        .form-select {
            padding: 0.75rem 1rem;
            border-radius: 8px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }

        .btn-submit {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-submit:hover {
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

        .password-note {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 0.5rem;
        }

        .invalid-feedback {
            display: none;
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .is-invalid {
            border-color: #dc3545 !important;
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
            <small class="text-white-50">Administrator Panel</small>
        </div>

        <div class="sidebar-menu">
            <a href="dashboard_admin.php">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="tampil_mahasiswa.php">
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">Tambah Data Mahasiswa</h3>
                    <a href="tampil_mahasiswa.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </a>
                </div>

                <?php if (isset($_SESSION['error_msg'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php echo $_SESSION['error_msg'];
                        unset($_SESSION['error_msg']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="form-container">
                    <form id="formTambahMahasiswa" action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label class="form-label">NPM</label>
                            <input type="text" name="npm" id="npm" class="form-control" placeholder="Masukkan NPM" required>
                            <small class="text-muted">NPM akan menjadi username untuk login</small>
                            <div class="invalid-feedback" id="npm-feedback">
                                NPM sudah terdaftar!
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat" required></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Foto Profil (Opsional)</label>
                            <input type="file" name="foto" class="form-control">
                            <small class="text-muted">Format: JPG/PNG, maksimal 2MB</small>
                        </div>

                        <div class="mb-4 bg-light p-3 rounded">
                            <h6 class="mb-3">Informasi Akun</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control" value="[Auto: NPM]" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Password Default</label>
                                    <input type="text" class="form-control" value="NPM!!" readonly>
                                </div>
                            </div>
                            <div class="password-note">
                                <i class="fas fa-info-circle me-2"></i>Akun akan dibuat otomatis dengan password default "NPM!!"
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" name="simpan" class="btn btn-primary btn-submit" id="submit-btn">
                                <i class="fas fa-save me-2"></i> Simpan Data
                            </button>
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="fas fa-undo me-2"></i> Reset
                            </button>
                        </div>
                    </form>
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

        // NPM validation
        const npmInput = document.getElementById('npm');
        const npmFeedback = document.getElementById('npm-feedback');
        const submitBtn = document.getElementById('submit-btn');
        let npmValid = false;

        npmInput.addEventListener('input', function() {
            const npm = this.value.trim();

            if (npm.length === 0) {
                resetValidation();
                return;
            }

            // Check NPM availability via AJAX
            checkNPM(npm);
        });

        function checkNPM(npm) {
            fetch(`check_npm.php?npm=${npm}`)
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        showError();
                    } else {
                        showSuccess();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function showError() {
            npmInput.classList.add('is-invalid');
            npmFeedback.style.display = 'block';
            submitBtn.disabled = true;
            npmValid = false;
        }

        function showSuccess() {
            npmInput.classList.remove('is-invalid');
            npmFeedback.style.display = 'none';
            submitBtn.disabled = false;
            npmValid = true;
        }

        function resetValidation() {
            npmInput.classList.remove('is-invalid');
            npmFeedback.style.display = 'none';
            submitBtn.disabled = false;
            npmValid = false;
        }

        // Form submission
        document.getElementById('formTambahMahasiswa').addEventListener('submit', function(e) {
            if (!npmValid) {
                e.preventDefault();
                showError();
            }
        });
    </script>
</body>

</html>