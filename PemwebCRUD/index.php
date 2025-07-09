<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION['username']) && isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

$error = isset($_GET['error']) && $_GET['error'] == 1;
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MoneyMate - Aplikasi Keuangan Pribadi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
  background: linear-gradient(to right, #92bcfa 0%, #bde3fb);
  font-family: 'Poppins';
  }

  .center-box {
    background-color: #f4f4f4;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    width: 700px;
    max-width: 100%;
  }

  .left-box {
    border-right: 1px solid #ddd;
    padding-right: 30px;
  }

  .right-box {
    padding-left: 30px;
  }

  .login-btn {
    width: 100%;
    background-color: rgb(44, 129, 255);
    border: none;
    color: white;
    transition: all 0.2s ease-in-out;
    padding: 10px;
    font-weight: bold;
    border-radius: 20px;
  }

  .login-btn:active {
    background-color:rgb(153, 192, 250);
    box-shadow: 0 0 10px rgb(153, 192, 250);
  }

  .login-btn:hover {
    background-color: rgb(153, 192, 250);
  }

</style>
</head>
<body>

<div class="container-fluid d-flex justify-content-center align-items-center" style="height: 100vh;">
  <div class="center-box row">
   
    <div class="col-md-6 left-box d-flex flex-column justify-content-center align-items-center text-center">
      <img src="logo1.png" alt="Logo MoneyMate" width="100" height="80" class="mb-3">
      <h2 class="fw-bold">MoneyMate</h2>
      <p class="fs-6 fst-italic text-secondary">Kelola keuanganmu, raih masa depan cemerlang!</p>
    </div>

    <div class="col-md-6 right-box">
      <h4 class="mb-4 fw-semibold">Masuk ke Akunmu</h4>

      <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
      <div class="alert alert-danger" role="alert">
       Username atau password salah.
      </div>
      <?php endif; ?>

      <form action="proses_login.php" method="POST">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" class="form-control" id="username" name="username" required />
        </div>
        <div div class="mb-3">
          <label for="password" class="form-label">Kata Sandi</label>
          <input type="password" class="form-control" id="password" name="password" required />
        </div>
        <button type="submit" class="login-btn">Login</button>
      </form>
      <p class="mt-3">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>
