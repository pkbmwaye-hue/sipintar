<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPINTAR - Aplikasi Nilai & Absensi</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background: #f0f2f5; }
        .navbar { background: #2c3e50; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .navbar h1 { font-size: 20px; }
        .nav-links a { color: white; text-decoration: none; margin-left: 20px; padding: 8px 15px; border-radius: 5px; }
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
        .stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 20px; }
        .stat-box { background: white; padding: 25px; border-radius: 10px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .stat-box h3 { font-size: 36px; color: #3498db; }
        .stat-box p { color: #777; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="navbar">
        <h1> SIPINTAR</h1>
        <div class="nav-links">
            <a href="index.php">Dashboard</a>
            <a href="siswa.php">Data Siswa</a>
            <a href="absensi.php">Absensi</a>
            <a href="nilai.php">Nilai</a>
            <a href="rekap.php">Rekap</a>
        </div>
    </div>
    <div class="container">