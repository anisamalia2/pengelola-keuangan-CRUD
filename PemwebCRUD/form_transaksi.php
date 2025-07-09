<?php
session_start();
include 'fungsi.php';

redirectIfNotLoggedIn();

$tanggal = '';
$jenisTransaksi = '';
$deskripsi = '';
$jumlahTransaksi = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal = trim($_POST['tanggalTransaksi'] ?? '');
    $jenisTransaksi = trim($_POST['jenisTransaksi'] ?? '');
    $jumlahTransaksi = (int) ($_POST['jumlah'] ?? 0);
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    if ($tanggal && $jenisTransaksi && $deskripsi && $jumlahTransaksi > 0) {
        if (tambahTransaksi($tanggal, $jenisTransaksi, $deskripsi, $jumlahTransaksi)) {
            header("Location: tampil_transaksi.php");
            exit();
        } else {
            echo "<script>alert('Gagal menambahkan transaksi. Silakan coba lagi.');</script>";
        }
    } else {
        echo "<script>alert('Semua field wajib diisi dan jumlah harus lebih dari 0.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Tambah Transaksi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>
    <style>
        body {
            background: linear-gradient(135deg, #92bcfa 0%, #bde3fb 100%);
            font-family: 'Poppins', sans-serif;
            padding-top: 60px;
            min-height: 100vh;
        }

        .form-box {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            max-width: 550px;
            margin: auto;
            box-shadow: 0 12px 25px rgba(0,0,0,0.1);
        }
        h4 {
            font-weight: 600;
            color:rgb(44, 129, 255);
        }
        label {
            font-weight: 500;
        }
        .btn-pink {
            background-color:rgb(44, 129, 255);
            color: white;
            border: none;
        }
        .btn-pink:hover {
            background-color:rgb(153, 192, 250);
        }
    </style>
</head>
<body>

<div class="form-box">
    <h4 class="mb-4 text-center"><i class="fa-solid fa-pen-to-square me-2"></i>Tambah Transaksi</h4>
    <form method="post" novalidate>
        <div class="mb-3">
            <label for="tanggal">Tanggal</label>
            <input 
                type="date" 
                name="tanggalTransaksi" 
                id="tanggal" 
                class="form-control" 
                required
                value="<?= htmlspecialchars(date('Y-m-d', strtotime($data['tanggal']))) ?>"
            >
        </div>
        <div class="mb-3">
            <label for="jenisTransaksi">Jenis Transaksi</label>
            <select name="jenisTransaksi" id="jenisTransaksi" class="form-select" required>
                <option value="">-- Pilih Jenis --</option>
                <option value="pemasukan" <?= $jenisTransaksi === 'pemasukan' ? 'selected' : '' ?>>Pemasukan</option>
                <option value="pengeluaran" <?= $jenisTransaksi === 'pengeluaran' ? 'selected' : '' ?>>Pengeluaran</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="jumlah">Jumlah</label>
            <input 
                type="number" 
                name="jumlah" 
                id="jumlah" 
                class="form-control" 
                required
                min="1"
                value="<?= htmlspecialchars($jumlahTransaksi) ?>"
            >
        </div>
        <div class="mb-3">
            <label for="deskripsi">Deskripsi</label>
            <textarea 
                name="deskripsi" 
                id="deskripsi" 
                class="form-control" 
                rows="2" 
                required
            ><?= htmlspecialchars($deskripsi) ?></textarea>
        </div>
        <div class="d-flex justify-content-between mt-4">
            <a href="index.php" class="btn btn-secondary px-4">Kembali</a>
            <button type="submit" class="btn btn-pink px-4">Simpan</button>
        </div>
    </form>
</div>

</body>
</html>
