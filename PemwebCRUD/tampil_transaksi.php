<?php
include "koneksi.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil parameter filter
$cari = isset($_GET['cari']) ? $_GET['cari'] : '';
$bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : date('m');
$tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : date('Y');

// Filter query
$where = "WHERE user_id = $user_id AND MONTH(tanggal) = $bulan AND YEAR(tanggal) = $tahun";
if (!empty($cari)) {
    $cari = mysqli_real_escape_string($conn, $cari);
    $where .= " AND (deskripsi LIKE '%$cari%' OR jumlahTransaksi LIKE '%$cari%')";
}

// Pagination
$batas = 5;
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

$query_total = mysqli_query($conn, "SELECT * FROM transaksi $where");
$total_data = mysqli_num_rows($query_total);
$total_halaman = ceil($total_data / $batas);

$query = mysqli_query($conn, "SELECT * FROM transaksi $where ORDER BY tanggal DESC LIMIT $halaman_awal, $batas");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>
    <style>
        body {
            background: linear-gradient(to right, #92bcfa 0%, #bde3fb);
            font-family: 'Poppins';
            padding-top: 40px;
            min-height: 100vh;
        }
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 12px 25px rgba(0,0,0,0.1);
        }
        h2 {
            color: rgb(44, 129, 255);
            font-weight: 600;
        }
        thead {
            background-color: rgb(153, 192, 250);
            color: white;
        }
        .btn-pink {
            background-color: rgb(44, 129, 255);
            color: white;
        }
        .btn-pink:hover {
            background-color: rgb(153, 192, 250);
            color: white;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4 text-center">Daftar Transaksi</h2>

    <!-- Form Filter -->
    <form method="GET" class="mb-3 row g-2">
        <div class="col-md-4">
            <input type="text" name="cari" class="form-control" placeholder="Cari deskripsi atau jumlah..." value="<?= htmlspecialchars($cari) ?>">
        </div>
        <div class="col-md-3">
            <select name="bulan" class="form-select">
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <option value="<?= $i ?>" <?= ($bulan == $i) ? 'selected' : '' ?>>
                        <?= date('F', mktime(0, 0, 0, $i, 1)) ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="tahun" class="form-select">
                <?php for ($y = date('Y'); $y >= 2022; $y--): ?>
                    <option value="<?= $y ?>" <?= ($tahun == $y) ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100" type="submit">Tampilkan</button>
        </div>
    </form>

    <!-- Tabel Transaksi -->
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jenis</th>
                <th>Deskripsi</th>
                <th>Jumlah</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $no = $halaman_awal + 1;
        if (mysqli_num_rows($query) > 0) {
            while ($row = mysqli_fetch_assoc($query)) {
                echo "<tr>
                    <td>$no</td>
                    <td>{$row['tanggal']}</td>
                    <td>{$row['jenisTransaksi']}</td>
                    <td>{$row['deskripsi']}</td>
                    <td>Rp " . number_format($row['jumlahTransaksi'], 0, ',', '.') . "</td>
                    <td>
                        <a href='update_transaksi.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                        <a href='hapus_transaksi.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Yakin ingin hapus?')\">Hapus</a>
                    </td>
                </tr>";
                $no++;
            }
        } else {
            echo "<tr><td colspan='6' class='text-center text-muted'>Belum ada data transaksi.</td></tr>";
        }
        ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <nav>
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $total_halaman; $i++): ?>
                <li class="page-item <?= ($i == $halaman) ? 'active' : '' ?>">
                    <a class="page-link" href="?halaman=<?= $i ?>&cari=<?= urlencode($cari) ?>&bulan=<?= $bulan ?>&tahun=<?= $tahun ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>

    <div class="d-flex justify-content-between">
        <a href="form_transaksi.php" class="btn btn-pink">Tambah Transaksi</a>
        <a href="home.php" class="btn btn-secondary">Kembali ke Dashboard</a>
    </div>
</div>
</body>
</html>
