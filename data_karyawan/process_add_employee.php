<?php
// Konfigurasi database
$servername = "localhost";
$username = "root"; // Ganti dengan username database Anda
$password = ""; // Ganti dengan password database Anda
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
    $no_hp_wa = htmlspecialchars($_POST['no_hp']);
    $status_pernikahan = htmlspecialchars($_POST['status_pernikahan']);
    $agama = htmlspecialchars($_POST['agama']);

    $serumah_nama = isset($_POST['serumah_nama']) ? htmlspecialchars($_POST['serumah_nama']) : '';
    $serumah_no_hp = isset($_POST['serumah_no_hp']) ? htmlspecialchars($_POST['serumah_no_hp']) : '';
    $serumah_alamat = isset($_POST['serumah_alamat']) ? htmlspecialchars($_POST['serumah_alamat']) : '';
    $serumah_hubungan = isset($_POST['serumah_hubungan']) ? htmlspecialchars($_POST['serumah_hubungan']) : '';

    $tidaksesumah_nama = isset($_POST['tidaksesumah_nama']) ? htmlspecialchars($_POST['tidaksesumah_nama']) : '';
    $tidaksesumah_no_hp = isset($_POST['tidaksesumah_no_hp']) ? htmlspecialchars($_POST['tidaksesumah_no_hp']) : '';
    $tidaksesumah_alamat = isset($_POST['tidaksesumah_alamat']) ? htmlspecialchars($_POST['tidaksesumah_alamat']) : '';
    $tidaksesumah_hubungan = isset($_POST['tidaksesumah_hubungan']) ? htmlspecialchars($_POST['tidaksesumah_hubungan']) : '';

    $nik_ktp = htmlspecialchars($_POST['nik_ktp']);
    $tanggal_masuk = htmlspecialchars($_POST['tanggal_masuk']);
    $departemen = htmlspecialchars($_POST['departemen']);
    $jabatan = htmlspecialchars($_POST['jabatan']);
    $status_kerja = htmlspecialchars($_POST['status_kerja']);

    // Tentukan tanggal_akhir_kontrak berdasarkan status_kerja
    // Jika statusnya 'Kontrak', ambil nilai dari form. Jika tidak, set NULL.
    $tanggal_akhir_kontrak = null;
    if ($status_kerja === 'Kontrak' && isset($_POST['tanggal_akhir_kontrak'])) {
        $tanggal_akhir_kontrak = htmlspecialchars($_POST['tanggal_akhir_kontrak']);
    }

    $lokasi_kerja = htmlspecialchars($_POST['lokasi_kerja']);

    $npwp = isset($_POST['npwp']) ? htmlspecialchars($_POST['npwp']) : '';
    $bpjs_kesehatan = isset($_POST['bpjs_kesehatan']) ? htmlspecialchars($_POST['bpjs_kesehatan']) : '';
    $bpjs_ketenagakerjaan = isset($_POST['bpjs_ketenagakerjaan']) ? htmlspecialchars($_POST['bpjs_ketenagakerjaan']) : '';
    $rekening = isset($_POST['rekening']) ? htmlspecialchars($_POST['rekening']) : '';
    $nama_bank = isset($_POST['nama_bank']) ? htmlspecialchars($_POST['nama_bank']) : '';

    $email = htmlspecialchars($_POST['email']);
    $password_awal = htmlspecialchars($_POST['password']);
    $role = htmlspecialchars($_POST['role']);

    // Proses upload foto (jika ada)
    $foto_path = NULL;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $target_dir = "uploads/";
        $file_name = uniqid() . '-' . basename($_FILES["foto"]["name"]);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Cek direktori, buat jika belum ada
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Cek ukuran & tipe file
        $check = getimagesize($_FILES["foto"]["tmp_name"]);
        if ($check !== false) {
            if ($_FILES["foto"]["size"] > 5000000) { // 5MB
                echo "Maaf, ukuran file terlalu besar.";
            } else if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                echo "Maaf, hanya file JPG, JPEG, & PNG yang diizinkan.";
            } else {
                if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                    $foto_path = $target_file;
                } else {
                    echo "Maaf, ada error saat mengunggah file Anda.";
                }
            }
        } else {
            echo "File bukan gambar.";
        }
    }

    // Hash password sebelum disimpan
    $password_hash = password_hash($password_awal, PASSWORD_DEFAULT);

    // Siapkan statement SQL
    $sql = "INSERT INTO karyawan (
        nik_ktp, nama_lengkap, jenis_kelamin, tempat_lahir, tanggal_lahir, alamat, no_hp_wa, status_pernikahan, agama, foto_path,
        serumah_nama, serumah_no_hp, serumah_alamat, serumah_hubungan,
        tidaksesumah_nama, tidaksesumah_no_hp, tidaksesumah_alamat, tidaksesumah_hubungan,
        nik_ktp, tanggal_masuk, departemen, jabatan, status_kerja, tanggal_akhir_kontrak, lokasi_kerja,
        npwp, bpjs_kesehatan, bpjs_ketenagakerjaan, rekening, nama_bank,
        email, password_hash, role
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Gunakan prepared statement untuk mencegah SQL injection
    $stmt = $conn->prepare($sql);

    // Perbaikan: Tambahkan 's' untuk kolom tanggal_akhir_kontrak
    $stmt->bind_param(
        "sssssssssssssssssssssssssssssssss",
        $nik_ktp,
        $nama_lengkap,
        $jenis_kelamin,
        $tempat_lahir,
        $tanggal_lahir,
        $alamat,
        $no_hp_wa,
        $status_pernikahan,
        $agama,
        $foto_path,
        $serumah_nama,
        $serumah_no_hp,
        $serumah_alamat,
        $serumah_hubungan,
        $tidaksesumah_nama,
        $tidaksesumah_no_hp,
        $tidaksesumah_alamat,
        $tidaksesumah_hubungan,
        $nik_ktp,
        $tanggal_masuk,
        $departemen,
        $jabatan,
        $status_kerja,
        $tanggal_akhir_kontrak,
        $lokasi_kerja,
        $npwp,
        $bpjs_kesehatan,
        $bpjs_ketenagakerjaan,
        $rekening,
        $nama_bank,
        $email,
        $password_hash,
        $role
    );

    // Jalankan statement dan cek hasilnya
    if ($stmt->execute()) {
        // Redirect kembali ke halaman data karyawan dengan pesan sukses
        header("Location: data_karyawan.php?status=success");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>