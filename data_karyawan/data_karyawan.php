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

// Menampilkan pesan dari URL
$message = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'success') {
        $message = '<div class="alert success">Karyawan berhasil ditambahkan!</div>';
    } elseif ($_GET['status'] == 'deleted') {
        $message = '<div class="alert success">Karyawan berhasil dihapus!</div>';
    } elseif ($_GET['status'] == 'updated') {
        $message = '<div class="alert success">Data karyawan berhasil diperbarui!</div>';
    }
}

// Logika untuk pencarian dan filter
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$filter_dept = isset($_GET['departemen']) ? $_GET['departemen'] : '';
$filter_jabatan = isset($_GET['jabatan']) ? $_GET['jabatan'] : '';

// Bangun query SQL
$sql = "SELECT * FROM karyawan WHERE 1=1";
$params = [];
$types = '';

if (!empty($search_query)) {
    $sql .= " AND (nik_karyawan LIKE ? OR nama_lengkap LIKE ?)";
    $search_param = "%" . $search_query . "%";
    $params[] = &$search_param;
    $params[] = &$search_param;
    $types .= "ss";
}

if (!empty($filter_dept)) {
    $sql .= " AND departemen = ?";
    $params[] = &$filter_dept;
    $types .= "s";
}

if (!empty($filter_jabatan)) {
    $sql .= " AND jabatan = ?";
    $params[] = &$filter_jabatan;
    $types .= "s";
}

// Siapkan dan jalankan prepared statement
$stmt = $conn->prepare($sql);
if ($stmt) {
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $employees = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    $employees = [];
    echo "Error: " . $conn->error;
}

// Ambil daftar unik departemen dan jabatan untuk filter dropdown
$departments_sql = "SELECT DISTINCT departemen FROM karyawan ORDER BY departemen";
$jabatan_sql = "SELECT DISTINCT jabatan FROM karyawan ORDER BY jabatan";
$departments_result = $conn->query($departments_sql);
$jabatan_result = $conn->query($jabatan_sql);

$all_departments = [];
$all_jabatan = [];

if ($departments_result) {
    while ($row = $departments_result->fetch_assoc()) {
        $all_departments[] = $row['departemen'];
    }
}
if ($jabatan_result) {
    while ($row = $jabatan_result->fetch_assoc()) {
        $all_jabatan[] = $row['jabatan'];
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Karyawan</title>
    <link rel="stylesheet" href="../style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <div class="company-brand">
                <img src="../image/manu.png" alt="Logo PT Mandiri Andalan Utama" class="company-logo">
                <p class="company-name">PT Mandiri Andalan Utama</p>
            </div>
            <div class="user-info">
                <div class="user-avatar">SN</div>
                <div class="user-details">
                    <p class="user-name">Siti Nurhaliza</p>
                    <p class="user-id">HRD001</p>
                    <p class="user-role">HR Manager</p>
                </div>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="../index.php"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="#"><i class="fas fa-file-alt"></i> Rekap Absensi</a></li>
                    <li><a href="../pengajuan/kelola_pengajuan.php"><i class="fas fa-edit"></i> Kelola Pengajuan</a>
                    </li>
                    <li><a href="#"><i class="fas fa-calendar-alt"></i> Monitoring Kontrak</a></li>
                    <li><a href="../payslip/e_payslip_hrd.php"><i class="fas fa-money-check-alt"></i> E-Pay Slip</a>
                    </li>
                    <li class="active"><a href="#"><i class="fas fa-users"></i> Data Karyawan</a></li>
                </ul>
            </nav>
            <div class="logout-link">
                <a href="#"><i class="fas fa-sign-out-alt"></i> Keluar</a>
            </div>
        </aside>

        <main class="main-content">
            <header class="main-header">
                <h1>Data Karyawan</h1>
                <p class="current-date"><?php echo date('l, d F Y'); ?></p>
            </header>

            <?php echo $message; ?>

            <div class="toolbar">
                <div class="search-filter-container">
                    <form action="data_karyawan.php" method="GET" class="search-form">
                        <div class="search-box">
                            <input type="text" name="search" placeholder="Cari NIK atau Nama..."
                                value="<?php echo htmlspecialchars($search_query); ?>">
                            <button type="submit"><i class="fas fa-search"></i></button>
                        </div>
                        <div class="filter-box">
                            <select name="departemen" onchange="this.form.submit()">
                                <option value="">Semua Departemen</option>
                                <?php
                                foreach ($all_departments as $dept) {
                                    $selected = ($filter_dept == $dept) ? 'selected' : '';
                                    echo "<option value='" . htmlspecialchars($dept) . "' $selected>" . htmlspecialchars($dept) . "</option>";
                                }
                                ?>
                            </select>
                            <select name="jabatan" onchange="this.form.submit()">
                                <option value="">Semua Jabatan</option>
                                <?php
                                foreach ($all_jabatan as $pos) {
                                    $selected = ($filter_jabatan == $pos) ? 'selected' : '';
                                    echo "<option value='" . htmlspecialchars($pos) . "' $selected>" . htmlspecialchars($pos) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="action-buttons">
                    <button class="add-button" onclick="openModal()">
                        <i class="fas fa-plus-circle"></i> Tambah Karyawan
                    </button>
                </div>
            </div>

            <div class="data-table-container">
                <table>
                    <thead>
                        <tr>
                            <th>NIK</th>
                            <th>Nama Lengkap</th>
                            <th>Jabatan</th>
                            <th>Departemen</th>
                            <th>Status Kerja</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($employees)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center;">Tidak ada data karyawan yang ditemukan.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($employees as $employee): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($employee['nik_ktp']); ?></td>
                                    <td><?php echo htmlspecialchars($employee['nama_lengkap']); ?></td>
                                    <td><?php echo htmlspecialchars($employee['jabatan']); ?></td>
                                    <td><?php echo htmlspecialchars($employee['departemen']); ?></td>
                                    <td><?php echo htmlspecialchars($employee['status_kerja']); ?></td>
                                    <td>
                                        <button class="action-btn view-btn"
                                            onclick="openViewModal('<?php echo htmlspecialchars($employee['nik_ktp']); ?>')"><i
                                                class="fas fa-eye"></i></button>
                                        <button class="action-btn edit-btn"
                                            onclick="openEditModal('<?php echo htmlspecialchars($employee['nik_ktp']); ?>')"><i
                                                class="fas fa-edit"></i></button>
                                        <button class="action-btn delete-btn"
                                            onclick="confirmDelete('<?php echo htmlspecialchars($employee['nik_ktp']); ?>')"><i
                                                class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div id="addEmployeeModal" class="modal">
                <div class="modal-content">
                    <span class="close-btn" onclick="closeModal()">&times;</span>
                    <h2>Form Tambah Karyawan</h2>
                    <form action="process_add_employee.php" method="POST" enctype="multipart/form-data">
                        <h3>1. Data Pribadi</h3>
                        <div class="form-section">
                            <div class="form-group">
                                <label for="nik_ktp">NIK (KTP)</label>
                                <input type="text" id="nik_ktp" name="nik_ktp" required>
                            </div>
                            <div class="form-group">
                                <label for="nama_lengkap">Nama Lengkap</label>
                                <input type="text" id="nama_lengkap" name="nama_lengkap" required>
                            </div>
                            <div class="form-group">
                                <label for="jenis_kelamin">Jenis Kelamin</label>
                                <select id="jenis_kelamin" name="jenis_kelamin">
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tempat_lahir">Tempat Lahir</label>
                                <input type="text" id="tempat_lahir" name="tempat_lahir">
                            </div>
                            <div class="form-group">
                                <label for="tanggal_lahir">Tanggal Lahir</label>
                                <input type="date" id="tanggal_lahir" name="tanggal_lahir" required>
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea id="alamat" name="alamat" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="no_hp">Nomor HP / WhatsApp</label>
                                <input type="text" id="no_hp" name="no_hp" required>
                            </div>
                            <div class="form-group">
                                <label for="status_pernikahan">Status Pernikahan</label>
                                <select id="status_pernikahan" name="status_pernikahan">
                                    <option value="Lajang">Lajang</option>
                                    <option value="Menikah">Menikah</option>
                                    <option value="Cerai">Cerai</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="agama">Agama</label>
                                <select id="agama" name="agama">
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen">Kristen</option>
                                    <option value="Katolik">Katolik</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Konghucu">Konghucu</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="foto">Upload Foto (opsional)</label>
                                <input type="file" id="foto" name="foto">
                            </div>
                        </div>

                        <h3>Kontak Orang Terdekat Serumah</h3>
                        <div class="form-section">
                            <div class="form-group">
                                <label for="serumah_nama">Nama</label>
                                <input type="text" id="serumah_nama" name="serumah_nama">
                            </div>
                            <div class="form-group">
                                <label for="serumah_no_hp">No HP</label>
                                <input type="text" id="serumah_no_hp" name="serumah_no_hp">
                            </div>
                            <div class="form-group">
                                <label for="serumah_alamat">Alamat</label>
                                <textarea id="serumah_alamat" name="serumah_alamat" rows="2"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="serumah_hubungan">Hubungan</label>
                                <input type="text" id="serumah_hubungan" name="serumah_hubungan">
                            </div>
                        </div>

                        <h3>Kontak Orang Terdekat Tidak Serumah</h3>
                        <div class="form-section">
                            <div class="form-group">
                                <label for="tidaksesumah_nama">Nama</label>
                                <input type="text" id="tidaksesumah_nama" name="tidaksesumah_nama">
                            </div>
                            <div class="form-group">
                                <label for="tidaksesumah_no_hp">No HP</label>
                                <input type="text" id="tidaksesumah_no_hp" name="tidaksesumah_no_hp">
                            </div>
                            <div class="form-group">
                                <label for="tidaksesumah_alamat">Alamat</label>
                                <textarea id="tidaksesumah_alamat" name="tidaksesumah_alamat" rows="2"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="tidaksesumah_hubungan">Hubungan</label>
                                <input type="text" id="tidaksesumah_hubungan" name="tidaksesumah_hubungan">
                            </div>
                        </div>

                        <h3>2. Data Pekerjaan</h3>
                        <div class="form-section">
                            <div class="form-group">
                                <label for="nik_karyawan">Nomor Induk Karyawan (ID Unik)</label>
                                <input type="text" id="nik_karyawan" name="nik_karyawan" required>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_masuk">Tanggal Masuk Kerja</label>
                                <input type="date" id="tanggal_masuk" name="tanggal_masuk" required>
                            </div>
                            <div class="form-group">
                                <label for="departemen">Departemen / Divisi</label>
                                <input type="text" id="departemen" name="departemen" required>
                            </div>
                            <div class="form-group">
                                <label for="jabatan">Jabatan / Posisi</label>
                                <input type="text" id="jabatan" name="jabatan" required>
                            </div>
                            <div class="form-group">
                                <label for="status_kerja">Status Kerja</label>
                                <select id="status_kerja" name="status_kerja">
                                    <option value="Kontrak">Kontrak</option>
                                    <option value="Tetap">Tetap</option>
                                    <option value="Magang">Magang</option>
                                    <option value="Outsourcing">Outsourcing</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="lokasi_kerja">Lokasi / Cabang Kerja</label>
                                <input type="text" id="lokasi_kerja" name="lokasi_kerja" required>
                            </div>
                        </div>

                        <h3>3. Data Administrasi</h3>
                        <div class="form-section">
                            <div class="form-group">
                                <label for="npwp">Nomor NPWP</label>
                                <input type="text" id="npwp" name="npwp">
                            </div>
                            <div class="form-group">
                                <label for="bpjs_kesehatan">Nomor BPJS Kesehatan</label>
                                <input type="text" id="bpjs_kesehatan" name="bpjs_kesehatan">
                            </div>
                            <div class="form-group">
                                <label for="bpjs_ketenagakerjaan">Nomor BPJS Ketenagakerjaan</label>
                                <input type="text" id="bpjs_ketenagakerjaan" name="bpjs_ketenagakerjaan">
                            </div>
                            <div class="form-group">
                                <label for="rekening">Nomor Rekening</label>
                                <input type="text" id="rekening" name="rekening">
                            </div>
                            <div class="form-group">
                                <label for="nama_bank">Nama Bank</label>
                                <input type="text" id="nama_bank" name="nama_bank">
                            </div>
                        </div>

                        <h3>4. Data Login</h3>
                        <div class="form-section">
                            <div class="form-group">
                                <label for="email">Email (Username)</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password Awal</label>
                                <input type="text" id="password" name="password" placeholder="Otomatis digenerate"
                                    readonly>
                            </div>
                            <div class="form-group">
                                <label for="role">Role / Level Akses</label>
                                <select id="role" name="role">
                                    <option value="Karyawan">Karyawan</option>
                                    <option value="HRD">HRD</option>
                                    <option value="Manager">Manager</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-buttons">
                            <button type="submit" class="submit-btn"><i class="fas fa-save"></i> Simpan
                                Karyawan</button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="viewEmployeeModal" class="modal">
                <div class="modal-content">
                    <span class="close-btn" onclick="closeViewModal()">&times;</span>
                    <h2>Detail Karyawan</h2>
                    <div id="employeeDetails">
                    </div>
                </div>
            </div>

            <div id="editEmployeeModal" class="modal">
                <div class="modal-content">
                    <span class="close-btn" onclick="closeEditModal()">&times;</span>
                    <h2>Edit Data Karyawan</h2>
                    <form id="editForm" action="process_edit_employee.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="edit_nik_ktp" name="nik_ktp">

                        <h3>1. Data Pribadi</h3>
                        <div class="form-section">
                            <div class="form-group">
                                <label for="edit_nama_lengkap">Nama Lengkap</label>
                                <input type="text" id="edit_nama_lengkap" name="nama_lengkap" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_jenis_kelamin">Jenis Kelamin</label>
                                <select id="edit_jenis_kelamin" name="jenis_kelamin">
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_tempat_lahir">Tempat Lahir</label>
                                <input type="text" id="edit_tempat_lahir" name="tempat_lahir">
                            </div>
                            <div class="form-group">
                                <label for="edit_tanggal_lahir">Tanggal Lahir</label>
                                <input type="date" id="edit_tanggal_lahir" name="tanggal_lahir" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_alamat">Alamat</label>
                                <textarea id="edit_alamat" name="alamat" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="edit_no_hp">Nomor HP / WhatsApp</label>
                                <input type="text" id="edit_no_hp" name="no_hp_wa" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_status_pernikahan">Status Pernikahan</label>
                                <select id="edit_status_pernikahan" name="status_pernikahan">
                                    <option value="Lajang">Lajang</option>
                                    <option value="Menikah">Menikah</option>
                                    <option value="Cerai">Cerai</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_agama">Agama</label>
                                <select id="edit_agama" name="agama">
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen">Kristen</option>
                                    <option value="Katolik">Katolik</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Konghucu">Konghucu</option>
                                </select>
                            </div>
                        </div>

                        <h3>2. Data Pekerjaan</h3>
                        <div class="form-section">
                            <div class="form-group">
                                <label for="edit_nik_karyawan">Nomor Induk Karyawan (ID Unik)</label>
                                <input type="text" id="edit_nik_karyawan" name="nik_karyawan" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_tanggal_masuk">Tanggal Masuk Kerja</label>
                                <input type="date" id="edit_tanggal_masuk" name="tanggal_masuk" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_departemen">Departemen / Divisi</label>
                                <input type="text" id="edit_departemen" name="departemen" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_jabatan">Jabatan / Posisi</label>
                                <input type="text" id="edit_jabatan" name="jabatan" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_status_kerja">Status Kerja</label>
                                <select id="edit_status_kerja" name="status_kerja">
                                    <option value="Kontrak">Kontrak</option>
                                    <option value="Tetap">Tetap</option>
                                    <option value="Magang">Magang</option>
                                    <option value="Outsourcing">Outsourcing</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_lokasi_kerja">Lokasi / Cabang Kerja</label>
                                <input type="text" id="edit_lokasi_kerja" name="lokasi_kerja" required>
                            </div>
                        </div>

                        <h3>3. Data Administrasi</h3>
                        <div class="form-section">
                            <div class="form-group">
                                <label for="edit_npwp">Nomor NPWP</label>
                                <input type="text" id="edit_npwp" name="npwp">
                            </div>
                            <div class="form-group">
                                <label for="edit_bpjs_kesehatan">Nomor BPJS Kesehatan</label>
                                <input type="text" id="edit_bpjs_kesehatan" name="bpjs_kesehatan">
                            </div>
                            <div class="form-group">
                                <label for="edit_bpjs_ketenagakerjaan">Nomor BPJS Ketenagakerjaan</label>
                                <input type="text" id="edit_bpjs_ketenagakerjaan" name="bpjs_ketenagakerjaan">
                            </div>
                            <div class="form-group">
                                <label for="edit_rekening">Nomor Rekening</label>
                                <input type="text" id="edit_rekening" name="rekening">
                            </div>
                            <div class="form-group">
                                <label for="edit_nama_bank">Nama Bank</label>
                                <input type="text" id="edit_nama_bank" name="nama_bank">
                            </div>
                        </div>

                        <h3>4. Data Login</h3>
                        <div class="form-section">
                            <div class="form-group">
                                <label for="edit_email">Email (Username)</label>
                                <input type="email" id="edit_email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_role">Role / Level Akses</label>
                                <select id="edit_role" name="role">
                                    <option value="Karyawan">Karyawan</option>
                                    <option value="HRD">HRD</option>
                                    <option value="Manager">Manager</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-buttons">
                            <button type="submit" class="submit-btn"><i class="fas fa-save"></i> Simpan
                                Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Modal Tambah Karyawan
        var addModal = document.getElementById("addEmployeeModal");
        function openModal() { addModal.style.display = "block"; }
        function closeModal() { addModal.style.display = "none"; }
        window.onclick = function (event) {
            if (event.target == addModal) {
                addModal.style.display = "none";
            }
        }
        document.getElementById('tanggal_lahir').addEventListener('change', function () {
            var dob = this.value;
            if (dob) {
                var password = dob.replace(/-/g, '');
                document.getElementById('password').value = password;
            } else {
                document.getElementById('password').value = '';
            }
        });

        // Modal View Karyawan
        var viewModal = document.getElementById("viewEmployeeModal");
        function openViewModal(nik) {
            var detailsContainer = document.getElementById("employeeDetails");
            detailsContainer.innerHTML = "Memuat data...";
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "get_employee_details.php?nik=" + nik, true);
            xhr.onload = function () {
                if (this.status == 200) {
                    var data = JSON.parse(this.responseText);
                    if (data) {
                        detailsContainer.innerHTML = `
                            <p><strong>NIK Karyawan:</strong> ${data.nik_karyawan}</p>
                            <p><strong>Nama Lengkap:</strong> ${data.nama_lengkap}</p>
                            <p><strong>Jenis Kelamin:</strong> ${data.jenis_kelamin}</p>
                            <p><strong>Tempat, Tanggal Lahir:</strong> ${data.tempat_lahir}, ${data.tanggal_lahir}</p>
                            <p><strong>Alamat:</strong> ${data.alamat}</p>
                            <p><strong>Nomor HP / WA:</strong> ${data.no_hp_wa}</p>
                            <p><strong>Status Pernikahan:</strong> ${data.status_pernikahan}</p>
                            <p><strong>Agama:</strong> ${data.agama}</p>
                            <hr>
                            <p><strong>Departemen:</strong> ${data.departemen}</p>
                            <p><strong>Jabatan:</strong> ${data.jabatan}</p>
                            <p><strong>Status Kerja:</strong> ${data.status_kerja}</p>
                            <p><strong>Tanggal Masuk:</strong> ${data.tanggal_masuk}</p>
                            <p><strong>Lokasi Kerja:</strong> ${data.lokasi_kerja}</p>
                            <hr>
                            <p><strong>Email:</strong> ${data.email}</p>
                            <p><strong>Role:</strong> ${data.role}</p>
                        `;
                    } else {
                        detailsContainer.innerHTML = "Data tidak ditemukan.";
                    }
                    viewModal.style.display = "block";
                }
            };
            xhr.send();
        }
        function closeViewModal() { viewModal.style.display = "none"; }

        // Modal Edit Karyawan
        var editModal = document.getElementById("editEmployeeModal");
        function openEditModal(nik) {
            var form = document.getElementById("editForm");
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "get_employee_details.php?nik=" + nik, true);
            xhr.onload = function () {
                if (this.status == 200) {
                    var data = JSON.parse(this.responseText);
                    if (data) {
                        document.getElementById("edit_nik_ktp").value = data.nik_ktp;
                        document.getElementById("edit_nama_lengkap").value = data.nama_lengkap;
                        document.getElementById("edit_jenis_kelamin").value = data.jenis_kelamin;
                        document.getElementById("edit_tempat_lahir").value = data.tempat_lahir;
                        document.getElementById("edit_tanggal_lahir").value = data.tanggal_lahir;
                        document.getElementById("edit_alamat").value = data.alamat;
                        document.getElementById("edit_no_hp").value = data.no_hp_wa;
                        document.getElementById("edit_status_pernikahan").value = data.status_pernikahan;
                        document.getElementById("edit_agama").value = data.agama;
                        document.getElementById("edit_nik_karyawan").value = data.nik_karyawan;
                        document.getElementById("edit_tanggal_masuk").value = data.tanggal_masuk;
                        document.getElementById("edit_departemen").value = data.departemen;
                        document.getElementById("edit_jabatan").value = data.jabatan;
                        document.getElementById("edit_status_kerja").value = data.status_kerja;
                        document.getElementById("edit_lokasi_kerja").value = data.lokasi_kerja;
                        document.getElementById("edit_npwp").value = data.npwp;
                        document.getElementById("edit_bpjs_kesehatan").value = data.bpjs_kesehatan;
                        document.getElementById("edit_bpjs_ketenagakerjaan").value = data.bpjs_ketenagakerjaan;
                        document.getElementById("edit_rekening").value = data.rekening;
                        document.getElementById("edit_nama_bank").value = data.nama_bank;
                        document.getElementById("edit_email").value = data.email;
                        document.getElementById("edit_role").value = data.role;

                        editModal.style.display = "block";
                    } else {
                        alert("Data karyawan tidak ditemukan.");
                    }
                }
            };
            xhr.send();
        }
        function closeEditModal() { editModal.style.display = "none"; }

        // Tombol Delete
        function confirmDelete(nik) {
            if (confirm("Apakah Anda yakin ingin menghapus karyawan dengan NIK " + nik + "?")) {
                window.location.href = "process_delete_employee.php?nik=" + nik;
            }
        }
    </script>
</body>

</html>