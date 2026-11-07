CREATE DATABASE IF NOT EXISTS sipintar1_db;
USE sipintar1_db;

CREATE TABLE siswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nis VARCHAR(20) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    kelas VARCHAR(20) NOT NULL,
    jenis_kelamin ENUM('L','P') NOT NULL
);

CREATE TABLE absensi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    siswa_id INT NOT NULL,
    tanggal DATE NOT NULL,
    status ENUM('Hadir','Sakit','Izin','Alpa') NOT NULL,
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE
);

CREATE TABLE nilai (
    id INT AUTO_INCREMENT PRIMARY KEY,
    siswa_id INT NOT NULL,
    mata_pelajaran VARCHAR(50) NOT NULL,
    jenis ENUM('Ulangan Harian','UTS','UAS') NOT NULL,
    nilai DECIMAL(5,2) NOT NULL,
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE
);

-- Data contoh Siswa
INSERT INTO siswa (nis, nama, kelas, jenis_kelamin) VALUES
('2024001', 'Ahmad Fauzi', '1A', 'L'),
('2024002', 'Siti Aminah', '2A', 'P'),
('2024003', 'Budi Santoso', '3A', 'L'),
('2024004', 'Dewi Lestari', '4B', 'P');