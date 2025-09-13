<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard HRD</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="company-brand">
                <img src="image/manu.png" alt="Logo PT Mandiri Andalan Utama" class="company-logo">
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
                    <li class="active"><a href="#"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="#"><i class="fas fa-file-alt"></i> Rekap Absensi</a></li>
                    <li><a href="./pengajuan/kelola_pengajuan.php"><i class="fas fa-edit"></i> Kelola Pengajuan</a></li>
                    <li><a href="#"><i class="fas fa-calendar-alt"></i> Monitoring Kontrak</a></li>
                    <li><a href="./payslip/e_payslip_hrd.php"><i class="fas fa-money-check-alt"></i> E-Pay Slip</a></li>
                    <li><a href="data_karyawan.php"><i class="fas fa-users"></i> Data Karyawan</a></li>
                </ul>
            </nav>
            <div class="logout-link">
                <a href="#"><i class="fas fa-sign-out-alt"></i> Keluar</a>
            </div>
        </aside>

        <main class="main-content">
            <header class="main-header">
                <h1>Dashboard HRD</h1>
                <p class="current-date">Jumat, 12 September 2025</p>
            </header>
            <div class="content-placeholder">
                </div>
        </main>
    </div>
</body>
</html>