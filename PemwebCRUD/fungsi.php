<?php
// fungsi.php
include 'koneksi.php'; 

// Fungsi cek user login
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}

// Fungsi tambah transaksi dengan prepared statement
function tambahTransaksi($tanggal, $jenisTransaksi, $deskripsi, $jumlahTransaksi) {
    global $conn;
    $user_id = $_SESSION['user_id'];

    $stmt = mysqli_prepare($conn, "INSERT INTO transaksi (tanggal, jenisTransaksi, deskripsi, jumlahTransaksi, user_id) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssii", $tanggal, $jenisTransaksi, $deskripsi, $jumlahTransaksi, $user_id);
    $execute = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $execute;
}

// Fungsi ambil semua transaksi user
function ambilSemuaTransaksi() {
    global $conn;
    $user_id = $_SESSION['user_id'];

    $stmt = mysqli_prepare($conn, "SELECT * FROM transaksi WHERE user_id = ? ORDER BY tanggal DESC");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $data;
}

// Fungsi hapus transaksi
function hapusTransaksi($id) {
    global $conn;

    $stmt = mysqli_prepare($conn, "DELETE FROM transaksi WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    $execute = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $execute;
}

// Fungsi ambil satu transaksi by id dan user_id
function ambilTransaksiById($id) {
    global $conn;
    $user_id = $_SESSION['user_id'];

    $stmt = mysqli_prepare($conn, "SELECT * FROM transaksi WHERE id = ? AND user_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $data;
}

// Fungsi update transaksi
function updateTransaksi($id, $tanggal, $jenis, $jumlah, $deskripsi) {
    global $conn;

    $stmt = mysqli_prepare($conn, "UPDATE transaksi SET tanggal = ?, jenisTransaksi = ?, jumlahTransaksi = ?, deskripsi = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ssisi", $tanggal, $jenis, $jumlah, $deskripsi, $id);
    $execute = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $execute;
}
?>
