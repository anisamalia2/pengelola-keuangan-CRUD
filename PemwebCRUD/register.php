<?php
session_start();
include 'koneksi.php';

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password1 = $_POST['password'];
    $password2 = $_POST['konfirmasi'];

    if ($password1 !== $password2) {
        $error = "Konfirmasi password tidak cocok!";
    } else {
        // Cek apakah username sudah ada
        $cek = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
        if (mysqli_num_rows($cek) > 0) {
            $error = "Username sudah digunakan.";
        } else {
            $hashedPassword = password_hash($password1, PASSWORD_DEFAULT);
            $insert = mysqli_query($conn, "INSERT INTO users (username, password) VALUES ('$username', '$hashedPassword')");
            if ($insert) {
                $_SESSION['username'] = $username;
                header("Location: index.php");
                exit;
            } else {
                $error = "Registrasi gagal. Silakan coba lagi.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MoneyMate - Aplikasi Keuangan Pribadi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>
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
    background-color:rgb(44, 129, 255);
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
    background-color:rgb(153, 192, 250);
  }

</style>

  </styl>
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
      <h4 class="mb-4 fw-semibold">Daftar Akun Baru</h4>

       <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>

      <form action="" method="POST" onsubmit="return cekPassword()">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" class="form-control" id="username" name="username" required />
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Kata Sandi</label>
          <input type="password" class="form-control" id="password" name="password" required />
        </div>
        <div class="mb-3">
            <label for="konfirmasi" class="form-label">Konfirmasi Sandi</label>
            <input type="password" class="form-control" id="konfirmasi" name="konfirmasi" required />
            <div id="peringatan" class="text-danger mt-1" style="display: none;">Konfirmasi sandi tidak cocok!</div>
        </div>
        <button type="submit" name="register" class="login-btn">Daftar</button>
      </form>
      <p class="mt-3"><a href="index.php">Kembali</a></p>
    </div>
  </div>
</div>

<script>
  function cekPassword() {
    const pass = document.getElementById("password").value;
    const konfirmasi = document.getElementById("konfirmasi").value;
    const peringatan = document.getElementById("peringatan");

    if (pass !== konfirmasi) {
      peringatan.style.display = "block"; 
      return false;  
    } else {
      peringatan.style.display = "none"; 
      return true;  
    }
  }
</script>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>