<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hrd_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Koneksi gagal"]));
}

if (isset($_GET['nik'])) {
    $nik = $_GET['nik'];
    $sql = "SELECT * FROM karyawan WHERE nik_ktp = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nik);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data) {
        echo json_encode($data);
    } else {
        echo json_encode(["error" => "Data karyawan tidak ditemukan"]);
    }
} else {
    echo json_encode(["error" => "Parameter nik tidak dikirim"]);
}
?>
