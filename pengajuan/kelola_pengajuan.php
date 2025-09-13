<?php
// koneksi database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hrd_system"; // ganti sesuai DB kamu

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// ambil data pengajuan join karyawan
$sql = "SELECT p.id_pengajuan, p.jenis_pengajuan, p.tanggal_mulai, p.tanggal_selesai,
               p.durasi, p.alasan, p.status, p.tanggal_pengajuan,
               k.nik_karyawan, k.nama_lengkap, k.jabatan, k.departemen
        FROM pengajuan p
        INNER JOIN karyawan k ON p.nik_karyawan = k.nik_karyawan
        ORDER BY p.tanggal_pengajuan DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengajuan - HRD System</title>
    <link rel="stylesheet" href="../style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #f8f9fa;
        }

        .btn {
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 13px;
            margin: 2px;
            display: inline-block;
        }

        .btn-approve {
            background: #28a745;
            color: #fff;
        }

        .btn-reject {
            background: #dc3545;
            color: #fff;
        }

        .btn-view {
            background: #007bff;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Sidebar -->
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
                    <li class="active"><a href="#"><i class="fas fa-edit"></i> Kelola Pengajuan</a></li>
                    <li><a href="#"><i class="fas fa-calendar-alt"></i> Monitoring Kontrak</a></li>
                    <li><a href="../payslip/e_payslip_hrd.php"><i class="fas fa-money-check-alt"></i> E-Pay Slip</a>
                    </li>
                    <li><a href="../data_karyawan/data_karyawan.php"><i class="fas fa-users"></i> Data Karyawan</a></li>
                </ul>
            </nav>
            <div class="logout-link">
                <a href="#"><i class="fas fa-sign-out-alt"></i> Keluar</a>
            </div>
        </aside>

        <!-- Main content -->
        <main class="main-content">
            <header class="main-header">
                <h1>Kelola Pengajuan</h1>
                <p class="current-date"><?php echo date("l, d F Y"); ?></p>
            </header>

            <section class="content">
                <table>
                    <tr>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Departemen</th>
                        <th>Jenis Pengajuan</th>
                        <th>Tanggal</th>
                        <th>Durasi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            ?>
                            <tr>
                                <td><?= $row['nik_karyawan'] ?></td>
                                <td><?= $row['nama_lengkap'] ?></td>
                                <td><?= $row['jabatan'] ?></td>
                                <td><?= $row['departemen'] ?></td>
                                <td><?= $row['jenis_pengajuan'] ?></td>
                                <td><?= $row['tanggal_mulai'] ?> s/d <?= $row['tanggal_selesai'] ?></td>
                                <td><?= $row['durasi'] ?> Hari</td>
                                <td><?= $row['status'] ?></td>
                                <td>
                                    <?php
                                    if ($row['status'] == 'pending') {
                                        echo "<a href='setujui_pengajuan.php?id=" . $row['id_pengajuan'] . "' class='btn btn-approve'>✔ Setujui</a>";
                                        echo "<a href='tolak_pengajuan.php?id=" . $row['id_pengajuan'] . "' class='btn btn-reject'>✖ Tolak</a>";
                                    } elseif ($row['status'] == 'disetujui') {
                                        echo "<span style='color:green;font-weight:bold;'>Disetujui</span>";
                                    } elseif ($row['status'] == 'ditolak') {
                                        echo "<span style='color:red;font-weight:bold;'>Ditolak</span>";
                                    }
                                    ?>
                                    <a href='detail_pengajuan.php?id=<?= $row['id_pengajuan'] ?>' class='btn btn-view'>👁
                                        Lihat</a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='9'>Belum ada pengajuan</td></tr>";
                    }
                    ?>

                </table>
            </section>
        </main>
    </div>
</body>

</html>

<?php $conn->close(); ?>