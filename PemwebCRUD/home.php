<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

include 'koneksi.php'; 

$username = $_SESSION['username'];

$getUser = $conn->query("SELECT id FROM users WHERE username = '$username'");
$userData = $getUser->fetch_assoc();
$userId = $userData['id'];

$resultPemasukan = $conn->query("SELECT SUM(jumlahTransaksi) as total_pemasukan FROM transaksi WHERE user_id = $userId AND jenisTransaksi = 'pemasukan'");
$totalPemasukan = $resultPemasukan->fetch_assoc()['total_pemasukan'] ?? 0;

$resultPengeluaran = $conn->query("SELECT SUM(jumlahTransaksi) as total_pengeluaran FROM transaksi WHERE user_id = $userId AND jenisTransaksi = 'pengeluaran'");
$totalPengeluaran = $resultPengeluaran->fetch_assoc()['total_pengeluaran'] ?? 0;

$saldo = $totalPemasukan - $totalPengeluaran;
$persentase = ($totalPemasukan > 0) ? round(($totalPengeluaran / $totalPemasukan) * 100, 1) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_unset(); 
    session_destroy(); 
    header("Location: index.php"); 
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard - MoneyMate</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>
  <style>
    body {
    background-color: rgb(153, 192, 250);
    font-family: 'Poppins';
    color: #333;
    }
    .navbar-custom {
    background-color:  #d6eaff;
    justify-content: center;
    text-align: center;
    font-weight: 0;
    }
    .navbar-custom .navbar-brand,
    .navbar-custom .navbar-nav .nav-link {
    color: black;
    font-weight: 0;
    }
    .welcome-card {
    background-color: #d6eaff;
    color: black;
    border-radius: 15px;
    font-size: 1.1rem;
    }
    .summary-card {
    border-radius: 15px;
    box-shadow: 0 4px 8px #d6eaff;
    transition: all 0.3s ease;
    font-weight: 0;
    }
    .summary-card:hover {
    transform: scale(1.03);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    cursor: pointer;
    }
    .summary-card:active {
    transform: scale(0.98);
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
    }
    .transaction-item {
    transition: transform 0.2s, box-shadow 0.3s;
    font-size: 0.95rem;
    }
    .transaction-item:hover {
    transform: scale(1.02);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .progress-bar {
    font-weight: 0;
    font-size: 0.95rem;
    }
    .card-title {
    font-size: 1.2rem;
    font-weight: 0;
    }
    .card-text {
    font-size: 1.1rem;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom">
  <div class="container-fluid">
    <!-- Kiri (Logo / Brand) -->
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="logo1.png" alt="Logo" width="50" height="40" class="d-inline-block align-text-top" />
      MoneyMate
    </a>

    <!-- Toggle untuk layar kecil -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" 
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Tengah (Menu) & Kanan (Logout) -->
    <div class="collapse navbar-collapse justify-content-between" id="navbarSupportedContent">
      <!-- Tengah: menu di tengah -->
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" href="home.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="profil.php">Profil Saya</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="transaksiDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Transaksi
          </a>
          <ul class="dropdown-menu" aria-labelledby="transaksiDropdown">
            <li><a class="dropdown-item" href="form_transaksi.php">Tambah Transaksi</a></li>
            <li><a class="dropdown-item" href="tampil_transaksi.php">Daftar Transaksi</a></li>
          </ul>
        </li>
      </ul>

      <!-- Kanan: Logout -->
      <form class="d-flex" method="post" action="">
        <button class="btn btn-light" type="submit" name="logout">Logout</button>
      </form>
    </div>
  </div>
</nav>


<div class="container my-5">
  <div class="card welcome-card mb-4 p-4">
   <h4>Selamat datang, <?= htmlspecialchars($username) ?>!</h4>
    <h6><p class="mb-0">MoneyMate here! Yuk kelola keuanganmu dengan bijak</p></h6>
  </div>

 <h4 class="mb-4">Ringkasan Keuangan</h4>
  <div class="row">
    <div class="col-md-4 mb-3">
      <div class="card summary-card border-success">
        <div class="card-body">
          <h5 class="card-title">💰 Saldo Saat Ini</h5>
          <p class="card-text fs-4 text-success">Rp <?= number_format($saldo, 0, ',', '.') ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-3">
      <div class="card summary-card border-success">
        <div class="card-body">
          <h5 class="card-title">📈 Total Pemasukan</h5>
          <p class="card-text fs-4 text-primary">Rp <?= number_format($totalPemasukan, 0, ',', '.') ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-3">
      <div class="card summary-card border-success">
        <div class="card-body">
          <h5 class="card-title">📉 Total Pengeluaran</h5>
          <p class="card-text fs-4 text-danger">Rp <?= number_format($totalPengeluaran, 0, ',', '.') ?></p>
        </div>
      </div>
    </div>
  </div>

  <!-- Progress Bar -->
  <div class="mt-4">
    <h4>Persentase Pengeluaran dari Pemasukan</h4>
    <div class="progress" style="height: 20px;">
      <div class="progress-bar bg-danger" role="progressbar" style="width: <?= $persentase ?>%;" aria-valuenow="<?= $persentase ?>" aria-valuemin="0" aria-valuemax="100">
        <?= $persentase ?>%
      </div>
    </div>
  </div>

  <!-- Transaksi Terakhir -->
  <div class="row mt-5">
    <div class="col-md-6 mb-4">
      <h4 class="mb-3">Transaksi Terakhir</h4>
      <div class="card h-100 p-3 shadow-sm" style="border-radius: 15px; background-color:  #d6eaff">
        <?php
          $sql = "SELECT * FROM transaksi WHERE user_id = $userId ORDER BY tanggal DESC LIMIT 5";
          $result = $conn->query($sql);
          if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              $isPengeluaran = ($row['jenisTransaksi'] === 'pengeluaran');
              $ikon = $isPengeluaran ? '⬇️' : '⬆️';
              $warnaText = $isPengeluaran ? 'text-danger' : 'text-success';
              $bg = $isPengeluaran ? 'bg-light' : 'bg-white';

              echo '<div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded transaction-item ' . $bg . '">';
                  echo '<div>';
                    echo htmlspecialchars($row['deskripsi']) . '<br>';
                    echo '<small class="text-muted">' . date('d M Y', strtotime($row['tanggal'])) . '</small>';
                  echo '</div>';
                     echo '<div class="ms-3 ' . $warnaText . ' text-end">';
                      echo $ikon . ' Rp ' . number_format($row['jumlahTransaksi'], 0, ',', '.');
                    echo '</div>';
                  echo '</div>';
            }
          } else {
            echo "<p class='text-muted mb-0'>Belum ada transaksi.</p>";
          }
        ?>
      </div>
    </div>

    <!-- Motivasi 1 -->
    <div class="col-md-3 mb-4">
      <h4 class="mb-3">Quotes-Anti Bokek</h4>
      <div class="card h-100 p-4 shadow-sm" style="border-radius: 15px; background: #d6eaff">
        <div class="d-flex flex-column align-items-center text-center h-100">
          <img src="meme1.jpeg" alt="motivasi1" class="mb-3" style="max-width: 100%;">
          <p class="mt-auto mb-auto">
            Scroll Shopee boleh, checkout juga gapapa. Tapi budgeting dulu, biar akhir bulan gak nangis depan ATM.
          </p>
        </div>
      </div>
    </div>

    <!-- Motivasi 2 -->
    <div class="col-md-3 mb-4">
      <h4 class="mb-3 invisible">Quotes-Anti Bokek</h4>
      <div class="card h-100 p-4 shadow-sm" style="border-radius: 15px; background:  #d6eaff">
        <div class="d-flex flex-column align-items-center text-center h-100">
          <img src="meme2.jpeg" alt="motivasi2" class="mb-3" style="max-width: 100%;">
          <p class="mt-auto mb-auto">
            Korban jajan random & lupa nyatet, nich. Saatnya tracking biar gak saldo nol terus
          </p>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</script>
</body>
</html>
