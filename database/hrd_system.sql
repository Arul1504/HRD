-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 13, 2025 at 05:13 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hrd_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `gaji`
--

CREATE TABLE `gaji` (
  `id` int(11) NOT NULL,
  `nik_karyawan` varchar(50) NOT NULL,
  `periode_gaji` varchar(7) DEFAULT NULL,
  `gaji_pokok` decimal(10,2) NOT NULL,
  `tunjangan_tetap` decimal(10,2) DEFAULT NULL,
  `tunjangan_tidak_tetap` decimal(10,2) DEFAULT NULL,
  `lembur` decimal(10,2) DEFAULT NULL,
  `potongan_absensi` decimal(10,2) DEFAULT NULL,
  `potongan_lain` decimal(10,2) DEFAULT NULL,
  `bpjs_ketenagakerjaan` decimal(10,2) DEFAULT NULL,
  `bpjs_kesehatan` decimal(10,2) DEFAULT NULL,
  `pph_21` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gaji`
--

INSERT INTO `gaji` (`id`, `nik_karyawan`, `periode_gaji`, `gaji_pokok`, `tunjangan_tetap`, `tunjangan_tidak_tetap`, `lembur`, `potongan_absensi`, `potongan_lain`, `bpjs_ketenagakerjaan`, `bpjs_kesehatan`, `pph_21`, `created_at`) VALUES
(9, '1234567890123456', '2025-02', '89999999.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2025-09-12 15:22:19'),
(10, '1234567890123456', '2025-06', '599999.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2025-09-12 15:22:35'),
(11, '3330300303030030', '2025-07', '7999999.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2025-09-12 15:27:26');

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE `karyawan` (
  `nik_ktp` varchar(16) NOT NULL COMMENT 'NIK dari KTP, sebagai primary key',
  `nama_lengkap` varchar(100) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` text,
  `no_hp_wa` varchar(20) NOT NULL,
  `status_pernikahan` enum('Lajang','Menikah','Cerai') NOT NULL,
  `agama` varchar(20) NOT NULL,
  `foto_path` varchar(255) DEFAULT NULL COMMENT 'Path atau URL ke file foto',
  `serumah_nama` varchar(100) DEFAULT NULL,
  `serumah_no_hp` varchar(20) DEFAULT NULL,
  `serumah_alamat` text,
  `serumah_hubungan` varchar(50) DEFAULT NULL,
  `tidaksesumah_nama` varchar(100) DEFAULT NULL,
  `tidaksesumah_no_hp` varchar(20) DEFAULT NULL,
  `tidaksesumah_alamat` text,
  `tidaksesumah_hubungan` varchar(50) DEFAULT NULL,
  `nik_karyawan` varchar(20) NOT NULL COMMENT 'Nomor Induk Karyawan perusahaan',
  `tanggal_masuk` date NOT NULL,
  `departemen` varchar(50) NOT NULL,
  `jabatan` varchar(50) NOT NULL,
  `status_kerja` enum('Kontrak','Tetap','Magang','Outsourcing') NOT NULL,
  `lokasi_kerja` varchar(100) NOT NULL,
  `npwp` varchar(30) DEFAULT NULL,
  `bpjs_kesehatan` varchar(30) DEFAULT NULL,
  `bpjs_ketenagakerjaan` varchar(30) DEFAULT NULL,
  `rekening` varchar(50) DEFAULT NULL,
  `nama_bank` varchar(50) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL COMMENT 'Password yang di-hash',
  `role` enum('Karyawan','HRD','Manager') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tanggal_akhir_kontrak` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`nik_ktp`, `nama_lengkap`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `no_hp_wa`, `status_pernikahan`, `agama`, `foto_path`, `serumah_nama`, `serumah_no_hp`, `serumah_alamat`, `serumah_hubungan`, `tidaksesumah_nama`, `tidaksesumah_no_hp`, `tidaksesumah_alamat`, `tidaksesumah_hubungan`, `nik_karyawan`, `tanggal_masuk`, `departemen`, `jabatan`, `status_kerja`, `lokasi_kerja`, `npwp`, `bpjs_kesehatan`, `bpjs_ketenagakerjaan`, `rekening`, `nama_bank`, `email`, `password_hash`, `role`, `created_at`, `tanggal_akhir_kontrak`) VALUES
('1234567890123456', 'Budi Santoso', 'Laki-laki', 'Jakarta', '1990-05-15', 'Jl. Sudirman No. 10, Jakarta', '081234567890', 'Menikah', 'Islam', 'uploads/budi_santoso.jpg', 'Ani Santoso', '081234567891', 'Jl. Sudirman No. 10, Jakarta', 'Istri', 'Joko Santoso', '081298765432', 'Jl. Merdeka No. 5, Bogor', 'Ayah', 'HRD001', '2022-01-10', 'IT &amp; Jaringan', 'Staff IT', 'Kontrak', 'Kantor Pusat', '123456789012345', '123456789012345', '123456789012345', '1234567890', 'BCA', 'budi.santoso@mandiriutama.com', '$2y$10$wT/p.S.f1X1.p.S.f1X1.p.S.f1X1.p.S.f1X1.p.S.f1X1.p.S', 'Karyawan', '2025-09-12 11:14:03', NULL),
('1234567890123457', 'Siti Aminah', 'Perempuan', 'Jakarta', '2009-12-30', 'Jl. Ciledug Raya No.168, RT.10/RW.4, Ulujami, Kec. Pesanggrahan, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12250', '09876432123', 'Menikah', 'Islam', 'uploads/siti_aminah.jpg', NULL, NULL, NULL, NULL, 'Ahmad Amin', '081398765432', 'Jl. Kembang No. 12, Jakarta', 'Ayah', 'HRD002', '2023-05-25', 'HRD', 'HR Staff', 'Kontrak', 'Kantor Pusat', '78999987', '278290300392', '8788977676', '1234567890', 'BCA', 'arul@gmail.com', '$2y$10$wT/p.S.f1X1.p.S.f1X1.p.S.f1X1.p.S.f1X1.p.S.f1X1.p.S', 'Karyawan', '2025-09-12 11:14:03', NULL),
('3330300303030030', 'adin', 'Laki-laki', 'Cilacap', '2001-06-04', 'Jl. Ciledug Raya No.168, RT.10/RW.4, Ulujami, Kec. Pesanggrahan, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12250', '09876432123', 'Lajang', 'Islam', NULL, 'arul', '0987665567', 'Jl. Ciledug Raya No.168, RT.10/RW.4, Ulujami, Kec. Pesanggrahan, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12250', 'adek', 'dimas', '09876766777', 'Jl. Ciledug Raya No.168, RT.10/RW.4, Ulujami, Kec. Pesanggrahan, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12250', 'teman', '4567890', '2025-02-13', 'IT', 'Manager', 'Kontrak', 'gandaria', '78999987', '278290300392', '8788977676', '7878877788', 'bca', 'rahmadansyahrul214@gmail.com', '$2y$10$VDxBs08lsWpEpktJmpr/2el1z9Z1yUdXxZsRMdUSTfsNFVnCLKFr6', 'Karyawan', '2025-09-12 12:22:40', '2026-06-17');

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan`
--

CREATE TABLE `pengajuan` (
  `id_pengajuan` int(11) NOT NULL,
  `nik_karyawan` varchar(20) NOT NULL,
  `jenis_pengajuan` enum('Cuti','Izin','Lembur') NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `durasi` int(11) NOT NULL,
  `alasan` text,
  `lampiran` varchar(255) DEFAULT NULL,
  `status` enum('Menunggu','Disetujui','Ditolak') DEFAULT 'Menunggu',
  `tanggal_pengajuan` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pengajuan`
--

INSERT INTO `pengajuan` (`id_pengajuan`, `nik_karyawan`, `jenis_pengajuan`, `tanggal_mulai`, `tanggal_selesai`, `durasi`, `alasan`, `lampiran`, `status`, `tanggal_pengajuan`) VALUES
(1, 'HRD001', 'Cuti', '2025-09-15', '2025-09-17', 3, 'Acara keluarga', NULL, 'Disetujui', '2025-09-11 17:00:00'),
(2, 'HRD002', 'Izin', '2025-09-20', '2025-09-20', 1, 'Urusan mendesak', NULL, 'Disetujui', '2025-09-11 17:00:00'),
(3, '4567890', '', '2025-09-10', '2025-09-12', 3, 'Demam tinggi', NULL, 'Disetujui', '2025-09-08 17:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gaji`
--
ALTER TABLE `gaji`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gaji_ibfk_1` (`nik_karyawan`);

--
-- Indexes for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`nik_ktp`),
  ADD UNIQUE KEY `nik_karyawan` (`nik_karyawan`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `pengajuan`
--
ALTER TABLE `pengajuan`
  ADD PRIMARY KEY (`id_pengajuan`),
  ADD KEY `nik_karyawan` (`nik_karyawan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gaji`
--
ALTER TABLE `gaji`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `pengajuan`
--
ALTER TABLE `pengajuan`
  MODIFY `id_pengajuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `gaji`
--
ALTER TABLE `gaji`
  ADD CONSTRAINT `gaji_ibfk_1` FOREIGN KEY (`nik_karyawan`) REFERENCES `karyawan` (`nik_ktp`);

--
-- Constraints for table `pengajuan`
--
ALTER TABLE `pengajuan`
  ADD CONSTRAINT `pengajuan_ibfk_1` FOREIGN KEY (`nik_karyawan`) REFERENCES `karyawan` (`nik_karyawan`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
