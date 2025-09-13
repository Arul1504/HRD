<?php
// Koneksi database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hrd_system"; // ganti sesuai DB kamu

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "UPDATE pengajuan SET status='Disetujui' WHERE id_pengajuan=$id";
    if ($conn->query($sql) === TRUE) {
        header("Location: kelola_pengajuan.php?msg=success");
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "ID tidak ditemukan!";
}

$conn->close();
?>
