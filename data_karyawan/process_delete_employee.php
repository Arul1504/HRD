<?php
// Konfigurasi database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hrd_system";

// Buat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil NIK dari URL
// Perbaikan: Ganti operator '??' dengan isset() untuk kompatibilitas PHP lama
$nik = isset($_GET['nik']) ? $_GET['nik'] : '';

if (!empty($nik)) {
    // Siapkan prepared statement untuk menghapus data
    $stmt = $conn->prepare("DELETE FROM karyawan WHERE nik_karyawan = ?");
    $stmt->bind_param("s", $nik);

    if ($stmt->execute()) {
        // Redirect kembali ke halaman data karyawan dengan pesan sukses
        header("Location: data_karyawan.php?status=deleted");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "NIK tidak valid.";
}

$conn->close();
?>