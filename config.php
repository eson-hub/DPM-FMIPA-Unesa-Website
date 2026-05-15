<?php
// config.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Alamat file database privat Anda
$path_db = __DIR__ . '/database_privat/dpm_fmipa-unesa.sqlite';

try {
    $db = new PDO("sqlite:" . $path_db);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi ke database gagal: " . $e->getMessage());
}

// Fungsi bantu pengecekan proteksi login session
function proteksi_halaman() {
    // Biarkan bagian ini kosong atau dikomentari agar bisa di-bypass (testing tanpa login)
    // if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    //     header("Location: login.php");
    //     exit;
    // }
}

// Fungsi bantu proses upload file gambar/document
function upload_file($file_input, $target_folder = 'uploads/') {
    if (!isset($_FILES[$file_input]) || $_FILES[$file_input]['error'] !== 0) {
        return null;
    }

    $file = $_FILES[$file_input];
    $nama_asli = $file['name'];
    $tmp_name = $file['tmp_name'];
    $ekstensi = strtolower(pathinfo($nama_asli, PATHINFO_EXTENSION));
    
    // Amankan nama file dengan string unik agar tidak bentrok
    $nama_baru = uniqid('file_', true) . '.' . $ekstensi;
    $target_path = $target_folder . $nama_baru;

    if (!is_dir($target_folder)) {
        mkdir($target_folder, 0777, true);
    }

    if (move_uploaded_file($tmp_name, $target_path)) {
        return $target_path; // Mengembalikan path lengkap: uploads/namafile.ext
    }
    return null;
}
?>
