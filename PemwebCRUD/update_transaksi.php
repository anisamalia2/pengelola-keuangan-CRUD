<?php
include 'koneksi.php';
session_start();

// Cek login
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

// Ambil ID transaksi
if (!isset($_GET['id'])) {
    echo "<script>alert('ID tidak ditemukan.'); window.location.href='tampil_transaksi.php';</script>";
    exit;
}

$id = (int) $_GET['id'];
$query = "SELECT * FROM transaksi WHERE id = $id";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan.'); window.location.href='tampil_transaksi.php';</script>";
    exit;
}

// Proses update saat form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggalBaru = trim($_POST['tanggalTransaksi']);
    $jenisBaru = trim($_POST['jenisTransaksi']);
    $jumlahBaru = (int) $_POST['jumlah'];
    $deskripsiBaru = trim($_POST['deskripsi']);

    if ($tanggalBaru && $jenisBaru && $deskripsiBaru && $jumlahBaru > 0) {
        $updateQuery = "UPDATE transaksi SET 
                        jenisTransaksi = '$jenisBaru', 
                        jumlahTransaksi = '$jumlahBaru', 
                        deskripsi = '$deskripsiBaru', 
                        tanggal = '$tanggalBaru' 
                        WHERE id = $id";
        $result = mysqli_query($conn, $updateQuery);

        if ($result) {
            echo "<script>alert('Data berhasil diperbarui.'); window.location.href='tampil_transaksi.php';</script>";
            exit;
        } else {
            echo "<script>alert('Gagal memperbarui data.');</script>";
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
    <title>Edit Transaksi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>
    <style>
        body {
            background: linear-gradient(135deg, #92bcfa 0%, #bde3fb 100%);
            font-family: 'Poppins';
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
            color:rgb(153, 192, 250);
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
    <h4 class="mb-4 text-center"><i class="fa-solid fa-pen-to-square me-2"></i>Edit Transaksi</h4>
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
                <option value="pemasukan" <?= $data['jenisTransaksi'] === 'pemasukan' ? 'selected' : '' ?>>Pemasukan</option>
                <option value="pengeluaran" <?= $data['jenisTransaksi'] === 'pengeluaran' ? 'selected' : '' ?>>Pengeluaran</option>
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
                value="<?= htmlspecialchars($data['jumlahTransaksi']) ?>"
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
            ><?= htmlspecialchars($data['deskripsi']) ?></textarea>
        </div>
        <div class="d-flex justify-content-between mt-4">
            <a href="tampil_transaksi.php" class="btn btn-secondary px-4">Batal</a>
            <button type="submit" class="btn btn-pink px-4">Simpan</button>
        </div>
    </form>
</div>
</body>
</html>
