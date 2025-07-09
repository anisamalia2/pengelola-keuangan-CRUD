<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    $query = "DELETE FROM transaksi WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>alert('Data berhasil dihapus.'); window.location='tampil_transaksi.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data.'); window.location='tampil_transaksi.php';</script>";
    }
} else {
    echo "<script>alert('Parameter tidak lengkap.'); window.location='tampil_transaksi.php';</script>";
}
?>
