<?php
// ============================================
// KONEKSI DATABASE
// ============================================
$host = "localhost";
$user = "root";
$pass = "";
$db   = "sipintar1_db";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// ============================================
// AMBIL PARAMETER HALAMAN
// ============================================
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// ============================================
// PROSES TAMBAH SISWA
// ============================================
if (isset($_POST['tambah_siswa'])) {
    $nis   = mysqli_real_escape_string($koneksi, trim($_POST['nis']));
    $nama  = mysqli_real_escape_string($koneksi, trim($_POST['nama']));
    $kelas = mysqli_real_escape_string($koneksi, trim($_POST['kelas']));
    $jk    = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);

    if (empty($nis) || empty($nama) || empty($kelas)) {
        $pesan = "<div class='alert alert-danger'> Semua field wajib diisi!</div>";
    } else {
        $cek = mysqli_query($koneksi, "SELECT id FROM siswa WHERE nis='$nis'");
        if (mysqli_num_rows($cek) > 0) {
            $pesan = "<div class='alert alert-danger'> NIS <strong>$nis</strong> sudah terdaftar!</div>";
        } else {
            mysqli_query($koneksi, "INSERT INTO siswa (nis, nama, kelas, jenis_kelamin) VALUES ('$nis', '$nama', '$kelas', '$jk')");
            $pesan = "<div class='alert alert-success'> Data siswa <strong>$nama</strong> berhasil ditambahkan!</div>";
        }
    }
    header("Location: index.php?page=siswa&pesan=" . urlencode($pesan));
    exit;
}

// ============================================
// PROSES UPDATE SISWA
// ============================================
if (isset($_POST['update_siswa'])) {
    $id    = $_POST['id'];
    $nis   = mysqli_real_escape_string($koneksi, trim($_POST['nis']));
    $nama  = mysqli_real_escape_string($koneksi, trim($_POST['nama']));
    $kelas = mysqli_real_escape_string($koneksi, trim($_POST['kelas']));
    $jk    = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);

    mysqli_query($koneksi, "UPDATE siswa SET nis='$nis', nama='$nama', kelas='$kelas', jenis_kelamin='$jk' WHERE id=$id");
    $pesan = "<div class='alert alert-success'> Data siswa berhasil diperbarui!</div>";
    header("Location: index.php?page=siswa&pesan=" . urlencode($pesan));
    exit;
}

// ============================================
// PROSES HAPUS SISWA
// ============================================
if (isset($_GET['hapus_siswa'])) {
    $id = $_GET['hapus_siswa'];
    mysqli_query($koneksi, "DELETE FROM siswa WHERE id=$id");
    $pesan = "<div class='alert alert-success'> Data siswa berhasil dihapus!</div>";
    header("Location: index.php?page=siswa&pesan=" . urlencode($pesan));
    exit;
}

// ============================================
// PROSES SIMPAN ABSENSI
// ============================================
if (isset($_POST['simpan_absensi'])) {
    $tanggal = $_POST['tanggal'];
    foreach ($_POST['status'] as $siswa_id => $status) {
        $cek = mysqli_query($koneksi, "SELECT id FROM absensi WHERE siswa_id=$siswa_id AND tanggal='$tanggal'");
        if (mysqli_num_rows($cek) > 0) {
            mysqli_query($koneksi, "UPDATE absensi SET status='$status' WHERE siswa_id=$siswa_id AND tanggal='$tanggal'");
        } else {
            mysqli_query($koneksi, "INSERT INTO absensi (siswa_id, tanggal, status) VALUES ($siswa_id, '$tanggal', '$status')");
        }
    }
    $pesan = "<div class='alert alert-success'> Absensi tanggal " . date('d-m-Y', strtotime($tanggal)) . " berhasil disimpan!</div>";
    header("Location: index.php?page=absensi&pesan=" . urlencode($pesan));
    exit;
}

// ============================================
// PROSES TAMBAH NILAI
// ============================================
if (isset($_POST['simpan_nilai'])) {
    $siswa_id = $_POST['siswa_id'];
    $mapel = mysqli_real_escape_string($koneksi, $_POST['mata_pelajaran']);
    $jenis = $_POST['jenis'];
    $nilai = $_POST['nilai'];
    mysqli_query($koneksi, "INSERT INTO nilai (siswa_id, mata_pelajaran, jenis, nilai) VALUES ($siswa_id, '$mapel', '$jenis', $nilai)");
    $pesan = "<div class='alert alert-success'> Nilai berhasil disimpan!</div>";
    header("Location: index.php?page=nilai&pesan=" . urlencode($pesan));
    exit;
}

// ============================================
// PROSES UPDATE NILAI
// ============================================
if (isset($_POST['update_nilai'])) {
    $id = $_POST['id'];
    $siswa_id = $_POST['siswa_id'];
    $mapel = mysqli_real_escape_string($koneksi, $_POST['mata_pelajaran']);
    $jenis = $_POST['jenis'];
    $nilai = $_POST['nilai'];
    mysqli_query($koneksi, "UPDATE nilai SET siswa_id=$siswa_id, mata_pelajaran='$mapel', jenis='$jenis', nilai=$nilai WHERE id=$id");
    $pesan = "<div class='alert alert-success'> Nilai berhasil diperbarui!</div>";
    header("Location: index.php?page=nilai&pesan=" . urlencode($pesan));
    exit;
}

// ============================================
// PROSES HAPUS NILAI
// ============================================
if (isset($_GET['hapus_nilai'])) {
    $id = $_GET['hapus_nilai'];
    mysqli_query($koneksi, "DELETE FROM nilai WHERE id=$id");
    $pesan = "<div class='alert alert-success'> Nilai berhasil dihapus!</div>";
    header("Location: index.php?page=nilai&pesan=" . urlencode($pesan));
    exit;
}

// ============================================
// AMBIL PESAN DARI URL
// ============================================
if (isset($_GET['pesan'])) {
    $pesan = $_GET['pesan'];
}

// ============================================
// AMBIL DATA UNTUK EDIT SISWA
// ============================================
$edit_siswa = null;
if (isset($_GET['edit_siswa'])) {
    $id_edit = $_GET['edit_siswa'];
    $result = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id=$id_edit");
    $edit_siswa = mysqli_fetch_assoc($result);
}

// ============================================
// AMBIL DATA UNTUK EDIT NILAI
// ============================================
$edit_nilai = null;
if (isset($_GET['edit_nilai'])) {
    $id_edit = $_GET['edit_nilai'];
    $result = mysqli_query($koneksi, "SELECT * FROM nilai WHERE id=$id_edit");
    $edit_nilai = mysqli_fetch_assoc($result);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPINTAR - Aplikasi Nilai & Absensi</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background: #f0f2f5; }
        .navbar { background: #2c3e50; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; }
        .navbar h1 { font-size: 20px; }
        .nav-links a { color: white; text-decoration: none; margin-left: 15px; padding: 8px 15px; border-radius: 5px; display: inline-block; }
        .nav-links a:hover, .nav-links a.active { background: #34495e; }
        .container { max-width: 1100px; margin: 30px auto; padding: 0 20px; }
        .card { background: white; border-radius: 10px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { margin-bottom: 20px; color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #2c3e50; color: white; }
        tr:hover { background: #f5f5f5; }
        .btn { display: inline-block; padding: 8px 16px; border-radius: 5px; text-decoration: none; color: white; border: none; cursor: pointer; font-size: 14px; }
        .btn-primary { background: #3498db; }
        .btn-success { background: #27ae60; }
        .btn-danger { background: #e74c3c; }
        .btn-warning { background: #f39c12; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; }
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; }
        .alert { padding: 12px; border-radius: 5px; margin-bottom: 15px; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-danger { background: #f8d7da; color: #721c24; }
        .stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 20px; }
        .stat-box { background: white; padding: 25px; border-radius: 10px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .stat-box h3 { font-size: 36px; color: #3498db; }
        .stat-box p { color: #777; margin-top: 5px; }

        @media print {
            .navbar, .no-print, .btn { display: none !important; }
            body { background: white !important; }
            .container { max-width: 100% !important; margin: 0 !important; padding: 0 !important; }
            .card { box-shadow: none !important; border: 1px solid #ccc !important; page-break-inside: avoid; margin-bottom: 15px !important; }
            table { font-size: 12px !important; }
            th, td { padding: 6px !important; }
            .print-title { display: block !important; text-align: center; margin-bottom: 20px; }
        }
        .print-title { display: none; }
        .ttd-area {
            display: none; 
        }
        @media print {
            .ttd-area {
                display: flex;
                justify-content: space-between;
                margin-top: 50px; 
                page-break-inside: avoid; 
            }
            .ttd-box {
                text-align: center;
                width: 250px;
            }
            .ttd-space {
                height: 70px; 
            }
        }
    </style>
</head>
<body>

<div class="navbar">
    <h1> SIPINTAR</h1>
    <div class="nav-links">
        <a href="index.php?page=dashboard" class="<?= $page=='dashboard'?'active':'' ?>">Dashboard</a>
        <a href="index.php?page=siswa" class="<?= $page=='siswa'?'active':'' ?>">Data Siswa</a>
        <a href="index.php?page=absensi" class="<?= $page=='absensi'?'active':'' ?>">Absensi</a>
        <a href="index.php?page=nilai" class="<?= $page=='nilai'?'active':'' ?>">Nilai</a>
        <a href="index.php?page=rekap" class="<?= $page=='rekap'?'active':'' ?>">Rekap</a>
    </div>
</div>

<div class="container">

<?php if (isset($pesan)) echo $pesan; ?>

<?php
// ============================================
// HALAMAN DASHBOARD
// ============================================
if ($page == 'dashboard'):
    $total_siswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM siswa"))['total'];
    $total_kelas = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(DISTINCT kelas) as total FROM siswa"))['total'];
    $total_nilai = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM nilai"))['total'];
?>

    <h2>Dashboard</h2>
    <div class="stats">
        <div class="stat-box"><h3><?= $total_siswa ?></h3><p>Total Siswa</p></div>
        <div class="stat-box"><h3><?= $total_kelas ?></h3><p>Total Kelas</p></div>
        <div class="stat-box"><h3><?= $total_nilai ?></h3><p>Data Nilai</p></div>
    </div>
    <div class="card">
        <h2>Selamat Datang di SIPINTAR</h2>
        <p style="margin-top: 10px; line-height: 1.8; color: #555;">
            SIPINTAR adalah aplikasi sederhana untuk membantu guru dalam mencatat nilai dan absensi siswa. 
            Gunakan menu di atas untuk mengelola data siswa, mencatat absensi harian, menginput nilai, dan melihat rekap.
        </p>
    </div>

<?php
// ============================================
// HALAMAN DATA SISWA
// ============================================
elseif ($page == 'siswa'):
    $siswa = mysqli_query($koneksi, "SELECT * FROM siswa ORDER BY kelas, nama");
?>

    <h2>Data Siswa</h2>
    <div class="card">
        <h2><?= $edit_siswa ? ' Edit Siswa' : ' Tambah Siswa' ?></h2>
        <form method="POST" action="index.php">
            <?php if ($edit_siswa): ?>
                <input type="hidden" name="id" value="<?= $edit_siswa['id'] ?>">
            <?php endif; ?>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label>NIS <span style="color:red;">*</span></label>
                    <input type="text" name="nis" value="<?= $edit_siswa ? htmlspecialchars($edit_siswa['nis']) : '' ?>" required>
                </div>
                <div class="form-group">
                    <label>Nama Lengkap <span style="color:red;">*</span></label>
                    <input type="text" name="nama" value="<?= $edit_siswa ? htmlspecialchars($edit_siswa['nama']) : '' ?>" required>
                </div>
                <div class="form-group">
                    <label>Kelas <span style="color:red;">*</span></label>
                    <input type="text" name="kelas" value="<?= $edit_siswa ? htmlspecialchars($edit_siswa['kelas']) : '' ?>" placeholder="MISAL: 4A" required>
                </div>
                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin">
                        <option value="L" <?= ($edit_siswa && $edit_siswa['jenis_kelamin'] == 'L') ? 'selected' : '' ?>>Laki-laki</option>
                        <option value="P" <?= ($edit_siswa && $edit_siswa['jenis_kelamin'] == 'P') ? 'selected' : '' ?>>Perempuan</option>
                    </select>
                </div>
            </div>
            <?php if ($edit_siswa): ?>
                <button type="submit" name="update_siswa" class="btn btn-warning"> Update Siswa</button>
                <a href="index.php?page=siswa" class="btn btn-primary"> Batal</a>
            <?php else: ?>
                <button type="submit" name="tambah_siswa" class="btn btn-success"> Simpan Siswa</button>
            <?php endif; ?>
        </form>
    </div>

    <div class="card">
        <h2> Daftar Siswa (<?= mysqli_num_rows($siswa) ?> siswa)</h2>
        <table>
            <thead><tr><th>No</th><th>NIS</th><th>Nama</th><th>Kelas</th><th>JK</th><th>Aksi</th></tr></thead>
            <tbody>
                <?php if (mysqli_num_rows($siswa) == 0): ?>
                    <tr><td colspan="6" style="text-align:center; color:#999;">Belum ada data siswa.</td></tr>
                <?php else: ?>
                    <?php $no = 1; while ($row = mysqli_fetch_assoc($siswa)): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nis']) ?></td>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                        <td><?= htmlspecialchars($row['kelas']) ?></td>
                        <td><?= $row['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                        <td>
                            <a href="index.php?page=siswa&edit_siswa=<?= $row['id'] ?>" class="btn btn-warning"> Edit</a>
                            <a href="index.php?page=siswa&hapus_siswa=<?= $row['id'] ?>" class="btn btn-danger" onclick="return confirm('Yakin hapus?')"> Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

<?php
// ============================================
// HALAMAN ABSENSI
// ============================================
elseif ($page == 'absensi'):
    $siswa = mysqli_query($koneksi, "SELECT * FROM siswa ORDER BY kelas, nama");
    $tanggal_absensi = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
    $hari_inggris = date('l', strtotime($tanggal_absensi));
    $hari_indonesia = [
        'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 
        'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
    ];
    $nama_hari = $hari_indonesia[$hari_inggris];
    // --------------------------
?>

    <h2>Input Absensi Harian</h2>
    <div class="card">
        <form method="POST" action="index.php">
            <div class="form-group">
            <label>Tanggal</label>
            <!-- Ganti baris input di bawah ini -->
            <input type="date" name="tanggal" value="<?= $tanggal_absensi ?>" 
                   onchange="window.location.href='index.php?page=absensi&tanggal=' + this.value" 
                   required style="margin-bottom: 5px;">
            
            <!--baris untuk menampilkan nama hari -->
            <div style="font-weight: bold; color: #2c3e50;">
                Hari: <span style="color: #3498db;"><?= $nama_hari ?></span>
            </div>
        </div>
            </div>
            <table>
                <thead><tr><th>No</th><th>NIS</th><th>Nama</th><th>Kelas</th><th>Status</th></tr></thead>
                <tbody>
                    <?php if (mysqli_num_rows($siswa) == 0): ?>
                        <tr><td colspan="5" style="text-align:center; color:#999;">Belum ada data siswa. Tambahkan siswa terlebih dahulu.</td></tr>
                    <?php else: ?>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($siswa)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $row['nis'] ?></td>
                            <td><?= $row['nama'] ?></td>
                            <td><?= $row['kelas'] ?></td>
                            <td>
                                <select name="status[<?= $row['id'] ?>]" style="padding: 5px; border-radius: 5px;">
                                    <option value="Hadir">Hadir</option>
                                    <option value="Sakit">Sakit</option>
                                    <option value="Izin">Izin</option>
                                    <option value="Alpa">Alpa</option>
                                </select>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <br>
            <button type="submit" name="simpan_absensi" class="btn btn-success"> Simpan Absensi</button>
        </form>
    </div>

<?php
// ============================================
// HALAMAN NILAI
// ============================================
elseif ($page == 'nilai'):
    $siswa = mysqli_query($koneksi, "SELECT * FROM siswa ORDER BY kelas, nama");
    $nilai = mysqli_query($koneksi, "SELECT n.*, s.nama, s.kelas FROM nilai n JOIN siswa s ON n.siswa_id = s.id ORDER BY n.id DESC");
?>

    <h2>Input Nilai Siswa</h2>
    <div class="card">
        <h2><?= $edit_nilai ? ' Edit Nilai' : ' Tambah Nilai' ?></h2>
        <form method="POST" action="index.php">
            <?php if ($edit_nilai): ?>
                <input type="hidden" name="id" value="<?= $edit_nilai['id'] ?>">
            <?php endif; ?>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label>Siswa</label>
                    <select name="siswa_id" required>
                        <option value="">-- Pilih Siswa --</option>
                        <?php while ($s = mysqli_fetch_assoc($siswa)): ?>
                        <option value="<?= $s['id'] ?>" <?= ($edit_nilai && $edit_nilai['siswa_id'] == $s['id']) ? 'selected' : '' ?>>
                            <?= $s['nis'] ?> - <?= $s['nama'] ?> (<?= $s['kelas'] ?>)
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Mata Pelajaran</label>
                    <input type="text" name="mata_pelajaran" value="<?= $edit_nilai ? htmlspecialchars($edit_nilai['mata_pelajaran']) : '' ?>" placeholder="Contoh: Matematika" required>
                </div>
                <div class="form-group">
                    <label>Jenis Penilaian</label>
                    <select name="jenis">
                        <option value="Ulangan Harian" <?= ($edit_nilai && $edit_nilai['jenis'] == 'Ulangan Harian') ? 'selected' : '' ?>>Ulangan Harian</option>
                        <option value="UTS" <?= ($edit_nilai && $edit_nilai['jenis'] == 'UTS') ? 'selected' : '' ?>>UTS</option>
                        <option value="UAS" <?= ($edit_nilai && $edit_nilai['jenis'] == 'UAS') ? 'selected' : '' ?>>UAS</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Nilai (0-100)</label>
                    <input type="number" name="nilai" min="0" max="100" step="0.01" value="<?= $edit_nilai ? $edit_nilai['nilai'] : '' ?>" required>
                </div>
            </div>
            <?php if ($edit_nilai): ?>
                <button type="submit" name="update_nilai" class="btn btn-warning"> Update Nilai</button>
                <a href="index.php?page=nilai" class="btn btn-primary"> Batal</a>
            <?php else: ?>
                <button type="submit" name="simpan_nilai" class="btn btn-success"> Simpan Nilai</button>
            <?php endif; ?>
        </form>
    </div>

    <div class="card">
        <h2> Daftar Nilai</h2>
        <table>
            <thead><tr><th>No</th><th>Nama</th><th>Kelas</th><th>Mapel</th><th>Jenis</th><th>Nilai</th><th>Aksi</th></tr></thead>
            <tbody>
                <?php if (mysqli_num_rows($nilai) == 0): ?>
                    <tr><td colspan="7" style="text-align:center; color:#999;">Belum ada data nilai.</td></tr>
                <?php else: ?>
                    <?php $no = 1; while ($row = mysqli_fetch_assoc($nilai)): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row['nama'] ?></td>
                        <td><?= $row['kelas'] ?></td>
                        <td><?= $row['mata_pelajaran'] ?></td>
                        <td><?= $row['jenis'] ?></td>
                        <td><strong><?= number_format($row['nilai'], 2) ?></strong></td>
                        <td>
                            <a href="index.php?page=nilai&edit_nilai=<?= $row['id'] ?>" class="btn btn-warning"> Edit</a>
                            <a href="index.php?page=nilai&hapus_nilai=<?= $row['id'] ?>" class="btn btn-danger" onclick="return confirm('Yakin hapus?')"> Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

<?php
// ============================================
// HALAMAN REKAP
// ============================================
elseif ($page == 'rekap'):
    $rekap_nilai = mysqli_query($koneksi, "
        SELECT s.nis, s.nama, s.kelas, 
               ROUND(AVG(n.nilai), 2) as rata_rata,
               COUNT(n.id) as jumlah_nilai
        FROM siswa s 
        LEFT JOIN nilai n ON s.id = n.siswa_id 
        GROUP BY s.id 
        ORDER BY s.kelas, s.nama
    ");
    $rekap_absensi = mysqli_query($koneksi, "
        SELECT s.nis, s.nama, s.kelas,
               SUM(CASE WHEN a.status='Hadir' THEN 1 ELSE 0 END) as hadir,
               SUM(CASE WHEN a.status='Sakit' THEN 1 ELSE 0 END) as sakit,
               SUM(CASE WHEN a.status='Izin' THEN 1 ELSE 0 END) as izin,
               SUM(CASE WHEN a.status='Alpa' THEN 1 ELSE 0 END) as alpa
        FROM siswa s 
        LEFT JOIN absensi a ON s.id = a.siswa_id 
        GROUP BY s.id 
        ORDER BY s.kelas, s.nama
    ");
?>

    <div class="print-title">
        <h2 style="margin-bottom: 5px;">LAPORAN REKAP NILAI & ABSENSI SISWA</h2>
        <p style="margin: 0;">SDN GAYAM — SIPINTAR</p>
        <p style="margin: 0; font-size: 12px;">Dicetak pada: <?= date('d-m-Y H:i') ?> WIB</p>
        <hr style="margin-top: 15px;">
    </div>

    <h2>Rekap Nilai & Absensi</h2>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" class="btn btn-primary"> Cetak / Print Laporan</button>
        <a href="index.php?page=rekap" class="btn btn-success"> Refresh Data</a>
    </div>

    <div class="card">
        <h2> Rekap Nilai (Rata-rata per Siswa)</h2>
        <table>
            <thead><tr><th>No</th><th>NIS</th><th>Nama</th><th>Kelas</th><th>Jumlah Nilai</th><th>Rata-rata</th></tr></thead>
            <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($rekap_nilai)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $row['nis'] ?></td>
                    <td><?= $row['nama'] ?></td>
                    <td><?= $row['kelas'] ?></td>
                    <td><?= $row['jumlah_nilai'] ?></td>
                    <td><strong><?= $row['rata_rata'] ? $row['rata_rata'] : '-' ?></strong></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="card">
        <h2> Rekap Absensi</h2>
        <table>
            <thead><tr><th>No</th><th>NIS</th><th>Nama</th><th>Kelas</th><th>Hadir</th><th>Sakit</th><th>Izin</th><th>Alpa</th></tr></thead>
            <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($rekap_absensi)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $row['nis'] ?></td>
                    <td><?= $row['nama'] ?></td>
                    <td><?= $row['kelas'] ?></td>
                    <td style="color: green;"><strong><?= $row['hadir'] ?></strong></td>
                    <td style="color: orange;"><?= $row['sakit'] ?></td>
                    <td style="color: blue;"><?= $row['izin'] ?></td>
                    <td style="color: red;"><?= $row['alpa'] ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="ttd-area">
        <!-- Bagian Kiri: Kepala Sekolah -->
        <div class="ttd-box">
            <p>Mengetahui,</p>
            <p>Kepala Sekolah</p>
            <div class="ttd-space"></div>
            <p style="font-weight: bold; text-decoration: underline;">..............</p>
            <p>NIP.............. </p>
        </div>

        <!-- Bagian Kanan: Guru Kelas -->
        <div class="ttd-box">
            <p>Penengahan, <?= date('d F Y') ?></p>
            <p>Guru Kelas</p>
            <div class="ttd-space"></div>
            <!-- Nama otomatis terisi jika kamu sudah pasang sistem login -->
            <p style="font-weight: bold; text-decoration: underline;">.....................</p>
            <p>NIP. .................... </p>
        </div>
    </div>

<?php endif; ?>

</div>
</body>
</html>