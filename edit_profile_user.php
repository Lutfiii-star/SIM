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

if (isset($_POST['update'])) {
    $nama = htmlspecialchars($_POST['nama']);
    $alamat = htmlspecialchars($_POST['alamat']);

    // Cek apakah user upload foto baru
    if (!empty($_FILES['foto']['name'])) {
        $foto = uniqid() . '_' . $_FILES['foto']['name'];
        $tmp = $_FILES['foto']['tmp_name'];

        // Pindah file
        move_uploaded_file($tmp, "uploads/$foto");
        
        // Hapus foto lama jika ada
        if ($mahasiswa['foto'] != '') {
            unlink("uploads/" . $mahasiswa['foto']);
        }

        // Update data + foto
        $query = "UPDATE tbl_mahasiswa SET nama='$nama', alamat='$alamat', foto='$foto' WHERE npm='$username'";
    } else {
        // Update data tanpa ubah foto
        $query = "UPDATE tbl_mahasiswa SET nama='$nama', alamat='$alamat' WHERE npm='$username'";
    }

    if (mysqli_query($conn, $query)) {
        $_SESSION['success_msg'] = 'Profil berhasil diperbarui';
        header("Location: dashboard_user.php");
        exit;
    } else {
        $_SESSION['error_msg'] = 'Gagal memperbarui profil';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - SIM Mahasiswa</title>
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
        
        .profile-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            padding: 2rem;
        }
        
        .profile-photo {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
            border: 3px solid #eee;
        }
        
        .form-label {
            font-weight: 500;
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
        
        .file-upload {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }
        
        .file-upload-input {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
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
            <a href="dashboard_user.php">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="edit_profile_user.php" class="active">
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
                        <img src="<?php echo $mahasiswa['foto'] ? 'uploads/'.$mahasiswa['foto'] : 'https://ui-avatars.com/api/?name='.urlencode($mahasiswa['nama']).'&background=random'; ?>" alt="Profile">
                        <span><?php echo $mahasiswa['nama']; ?></span>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Content -->
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">Edit Profil Mahasiswa</h3>
                </div>
                
                <?php if(isset($_SESSION['error_msg'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php echo $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <div class="profile-container">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-4 mb-4 text-center">
                                <div class="mb-3">
                                    <?php if ($mahasiswa['foto']) { ?>
                                        <img src="uploads/<?php echo $mahasiswa['foto']; ?>" class="profile-photo mb-3" id="photoPreview">
                                    <?php } else { ?>
                                        <div class="profile-photo mb-3 bg-light d-flex align-items-center justify-content-center mx-auto" id="photoPreview">
                                            <i class="fas fa-user fa-3x text-muted"></i>
                                        </div>
                                    <?php } ?>
                                </div>
                                
                                <div class="file-upload btn btn-primary mb-3">
                                    <i class="fas fa-camera me-2"></i> Ganti Foto
                                    <input type="file" name="foto" class="file-upload-input" id="photoInput" accept="image/*">
                                </div>
                                <small class="text-muted d-block">Format: JPG/PNG, maks 2MB</small>
                            </div>
                            
                            <div class="col-md-8">
                                <div class="mb-4">
                                    <label class="form-label">NPM (Username)</label>
                                    <input type="text" class="form-control" value="<?php echo $mahasiswa['npm']; ?>" readonly>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" name="nama" class="form-control" value="<?php echo $mahasiswa['nama']; ?>" required>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label">Alamat</label>
                                    <textarea name="alamat" class="form-control" rows="3" required><?php echo $mahasiswa['alamat']; ?></textarea>
                                </div>
                                
                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <button type="submit" name="update" class="btn btn-success btn-submit">
                                        <i class="fas fa-save me-2"></i> Simpan Perubahan
                                    </button>
                                    <a href="dashboard_user.php" class="btn btn-outline-secondary">
                                        Batal
                                    </a>
                                </div>
                            </div>
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
        
        // Preview photo before upload
        document.getElementById('photoInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('photoPreview');
                    if (preview.tagName === 'IMG') {
                        preview.src = e.target.result;
                    } else {
                        // If it's the placeholder div, replace with img
                        const newPreview = document.createElement('img');
                        newPreview.src = e.target.result;
                        newPreview.className = 'profile-photo mb-3';
                        newPreview.id = 'photoPreview';
                        preview.parentNode.replaceChild(newPreview, preview);
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>