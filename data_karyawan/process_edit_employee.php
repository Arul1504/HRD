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

// Cek jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Ambil data dari form dan sanitasi
    $nik_ktp = htmlspecialchars($_POST['nik_ktp']);
    $nama_lengkap = htmlspecialchars($_POST['nama_lengkap']);
    $jenis_kelamin = htmlspecialchars($_POST['jenis_kelamin']);
    $tempat_lahir = htmlspecialchars($_POST['tempat_lahir']);
    $tanggal_lahir = htmlspecialchars($_POST['tanggal_lahir']);
    $alamat = htmlspecialchars($_POST['alamat']);
    $no_hp_wa = htmlspecialchars($_POST['no_hp_wa']);
    $status_pernikahan = htmlspecialchars($_POST['status_pernikahan']);
    $agama = htmlspecialchars($_POST['agama']);
    $nik_karyawan = htmlspecialchars($_POST['nik_karyawan']);
    $tanggal_masuk = htmlspecialchars($_POST['tanggal_masuk']);
    $departemen = htmlspecialchars($_POST['departemen']);
    $jabatan = htmlspecialchars($_POST['jabatan']);
    $status_kerja = htmlspecialchars($_POST['status_kerja']);
    $lokasi_kerja = htmlspecialchars($_POST['lokasi_kerja']);

    // Perbaikan: Ganti operator '??' dengan isset() untuk kompatibilitas PHP lama
    $npwp = isset($_POST['npwp']) ? htmlspecialchars($_POST['npwp']) : '';
    $bpjs_kesehatan = isset($_POST['bpjs_kesehatan']) ? htmlspecialchars($_POST['bpjs_kesehatan']) : '';
    $bpjs_ketenagakerjaan = isset($_POST['bpjs_ketenagakerjaan']) ? htmlspecialchars($_POST['bpjs_ketenagakerjaan']) : '';
    $rekening = isset($_POST['rekening']) ? htmlspecialchars($_POST['rekening']) : '';
    $nama_bank = isset($_POST['nama_bank']) ? htmlspecialchars($_POST['nama_bank']) : '';

    $email = htmlspecialchars($_POST['email']);
    $role = htmlspecialchars($_POST['role']);

    // Siapkan statement SQL untuk UPDATE
    $sql = "UPDATE karyawan SET 
        nama_lengkap = ?, jenis_kelamin = ?, tempat_lahir = ?, tanggal_lahir = ?, alamat = ?, no_hp_wa = ?, status_pernikahan = ?, agama = ?,
        nik_karyawan = ?, tanggal_masuk = ?, departemen = ?, jabatan = ?, status_kerja = ?, lokasi_kerja = ?,
        npwp = ?, bpjs_kesehatan = ?, bpjs_ketenagakerjaan = ?, rekening = ?, nama_bank = ?,
        email = ?, role = ?
        WHERE nik_ktp = ?";

    $stmt = $conn->prepare($sql);
    
    // Perbaikan: Pastikan jumlah dan urutan parameter sesuai dengan query UPDATE
    $stmt->bind_param("ssssssssssssssssssssss", 
        $nama_lengkap, $jenis_kelamin, $tempat_lahir, $tanggal_lahir, $alamat, $no_hp_wa, $status_pernikahan, $agama,
        $nik_karyawan, $tanggal_masuk, $departemen, $jabatan, $status_kerja, $lokasi_kerja,
        $npwp, $bpjs_kesehatan, $bpjs_ketenagakerjaan, $rekening, $nama_bank,
        $email, $role,
        $nik_ktp
    );

    // Jalankan statement dan cek hasilnya
    if ($stmt->execute()) {
        // Redirect kembali ke halaman data karyawan dengan pesan sukses
        header("Location: data_karyawan.php?status=updated");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>