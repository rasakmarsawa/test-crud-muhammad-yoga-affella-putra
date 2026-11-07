-- Seed master tables: division, permit, karyawan
-- Run: docker compose exec -T db mysql -uapp -papp test_crud < seed_master.sql

SET NAMES utf8;

-- division
INSERT INTO `division` (`division_name`, `divisi_code`, `description`) VALUES
('Human Resources', 'HR', 'Human Resources'),
('Finance', 'FIN', 'Finance & Accounting'),
('Information Technology', 'IT', 'IT & Systems'),
('Operations', 'OPS', 'Operations')
ON DUPLICATE KEY UPDATE
  `division_name` = VALUES(`division_name`),
  `description` = VALUES(`description`);

-- permit
INSERT INTO `permit` (`permit_name`) VALUES
('Cuti Tahunan'),
('Sakit'),
('Izin'),
('Cuti Melahirkan');

-- karyawan (FK division_id resolved by divisi_code)
INSERT INTO `karyawan` (`username`, `password`, `fullname`, `division_id`, `email`, `NIK`, `gender`, `address`)
SELECT 'budi.santoso', 'password123', 'Budi Santoso', d.id, 'budi.santoso@example.com', '3201010101010001', 'L', 'Jakarta'
FROM `division` d WHERE d.`divisi_code` = 'HR'
ON DUPLICATE KEY UPDATE
  `fullname` = VALUES(`fullname`), `division_id` = VALUES(`division_id`), `email` = VALUES(`email`), `NIK` = VALUES(`NIK`), `gender` = VALUES(`gender`), `address` = VALUES(`address`);

INSERT INTO `karyawan` (`username`, `password`, `fullname`, `division_id`, `email`, `NIK`, `gender`, `address`)
SELECT 'siti.aminah', 'password123', 'Siti Aminah', d.id, 'siti.aminah@example.com', '3201010101010002', 'P', 'Bandung'
FROM `division` d WHERE d.`divisi_code` = 'FIN'
ON DUPLICATE KEY UPDATE
  `fullname` = VALUES(`fullname`), `division_id` = VALUES(`division_id`), `email` = VALUES(`email`), `NIK` = VALUES(`NIK`), `gender` = VALUES(`gender`), `address` = VALUES(`address`);

INSERT INTO `karyawan` (`username`, `password`, `fullname`, `division_id`, `email`, `NIK`, `gender`, `address`)
SELECT 'agus.pratama', 'password123', 'Agus Pratama', d.id, 'agus.pratama@example.com', '3201010101010003', 'L', 'Surabaya'
FROM `division` d WHERE d.`divisi_code` = 'IT'
ON DUPLICATE KEY UPDATE
  `fullname` = VALUES(`fullname`), `division_id` = VALUES(`division_id`), `email` = VALUES(`email`), `NIK` = VALUES(`NIK`), `gender` = VALUES(`gender`), `address` = VALUES(`address`);

INSERT INTO `karyawan` (`username`, `password`, `fullname`, `division_id`, `email`, `NIK`, `gender`, `address`)
SELECT 'dewi.lestari', 'password123', 'Dewi Lestari', d.id, 'dewi.lestari@example.com', '3201010101010004', 'P', 'Yogyakarta'
FROM `division` d WHERE d.`divisi_code` = 'OPS'
ON DUPLICATE KEY UPDATE
  `fullname` = VALUES(`fullname`), `division_id` = VALUES(`division_id`), `email` = VALUES(`email`), `NIK` = VALUES(`NIK`), `gender` = VALUES(`gender`), `address` = VALUES(`address`);
