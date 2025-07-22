<?php
include 'koneksi.php';

header('Content-Type: application/json');

try {
    if (!isset($_GET['npm']) || empty(trim($_GET['npm']))) {
        throw new Exception('Parameter NPM tidak valid');
    }

    $npm = mysqli_real_escape_string($conn, trim($_GET['npm']));
    
    // Gunakan prepared statement
    $stmt = $conn->prepare("SELECT npm FROM tbl_mahasiswa WHERE npm = ?");
    $stmt->bind_param("s", $npm);
    $stmt->execute();
    $result = $stmt->get_result();
    
    echo json_encode([
        'exists' => $result->num_rows > 0,
        'error' => false
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'exists' => false,
        'error' => $e->getMessage()
    ]);
} finally {
    if (isset($stmt)) $stmt->close();
    $conn->close();
}
?>