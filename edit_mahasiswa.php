<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tbl_mahasiswa WHERE idMhs = '$id'"));

if (isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];

    // Jika ganti foto
    if ($_FILES['foto']['name'] != '') {
        $foto = uniqid() . '_' . $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], 'uploads/' . $foto);
        // Hapus foto lama jika ada
        if ($data['foto'] != '') unlink('uploads/' . $data['foto']);
        mysqli_query($conn, "UPDATE tbl_mahasiswa SET nama='$nama', alamat='$alamat', foto='$foto' WHERE idMhs='$id'");
    } else {
        mysqli_query($conn, "UPDATE tbl_mahasiswa SET nama='$nama', alamat='$alamat' WHERE idMhs='$id'");
    }

    $_SESSION['success_msg'] = 'Data mahasiswa berhasil diupdate';
    header("Location: tampil_mahasiswa.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa - SIM</title>
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
        
        .sidebar-menu a:hover, .sidebar-menu a.active {
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
        }
        
        .student-photo {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #eee;
            margin-top: 10px;
        }
        
        .btn-submit {
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
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
                    <h3 class="mb-0">Edit Data Mahasiswa</h3>
                    <a href="tampil_mahasiswa.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </a>
                </div>
                
                <div class="form-container">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label class="form-label">NPM (Tidak Bisa Diubah)</label>
                            <input type="text" value="<?= $data['npm']; ?>" class="form-control" readonly>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" value="<?= $data['nama']; ?>" class="form-control" required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="3" required><?= $data['alamat']; ?></textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">Foto Profil</label>
                            <input type="file" name="foto" class="form-control mb-2">
                            <?php if ($data['foto'] != '') { ?>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="uploads/<?= $data['foto']; ?>" class="student-photo">
                                    <span class="text-muted">Foto saat ini</span>
                                </div>
                            <?php } else { ?>
                                <p class="text-muted">Tidak ada foto profil</p>
                            <?php } ?>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" name="update" class="btn btn-primary btn-submit">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                            <a href="tampil_mahasiswa.php" class="btn btn-outline-secondary">
                                Batal
                            </a>
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
    </script>
</body>
</html>