<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';

$id = $_GET['id'];

// Ambil data mahasiswa (untuk hapus foto)
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tbl_mahasiswa WHERE idMhs='$id'"));
if ($data['foto'] != '') unlink('uploads/' . $data['foto']);

// Hapus data mahasiswa
mysqli_query($conn, "DELETE FROM tbl_mahasiswa WHERE idMhs='$id'");

// Hapus juga user terkait (berdasarkan NPM)
$npm = $data['npm'];
mysqli_query($conn, "DELETE FROM tbl_user WHERE username = '$npm'");

header("Location: tampil_mahasiswa.php");
?>
