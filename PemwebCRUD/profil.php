<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_unset(); 
    session_destroy(); 
    header("Location: index.php"); 
    exit;
}
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$username = $_SESSION['username'];

// Ambil data user
$getUser = $conn->query("SELECT id, username FROM users WHERE username = '$username'");
$userData = $getUser->fetch_assoc();
$userId = $userData['id'];

// Path folder
$profileDir = 'uploads/';
if (!is_dir($profileDir)) {
    mkdir($profileDir, 0755, true);
}

$message = '';

// Cek apakah ada foto profil
$profilePic = '';
$found = false;
foreach (['jpg', 'jpeg', 'png', 'gif'] as $ext) {
    $path = $profileDir . "profile_{$userId}.$ext";
    if (file_exists($path)) {
        $profilePic = $path;
        $found = true;
        break;
    }
}
if (!$found) {
    $profilePic = "default-profile.png";
}

// Handle POST (upload/update username)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Hapus Foto Profil
if (isset($_POST['delete_photo'])) {
    $deleted = false;
    foreach (['jpg', 'jpeg', 'png', 'gif'] as $ext) {
        $file = $profileDir . "profile_{$userId}.$ext";
        if (file_exists($file)) {
            unlink($file);
            $deleted = true;
        }
    }
    if ($deleted) {
        $message = "Foto profil berhasil dihapus.";
        $profilePic = "default-profile.png";
    } else {
        $message = "Tidak ada foto profil yang bisa dihapus.";
    }
}

    // Update Username
    if (isset($_POST['update_username'])) {
        $newUsername = trim($_POST['username']);
        if ($newUsername === '') {
            $message = "Username tidak boleh kosong.";
        } else {
            $stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
            $stmt->bind_param('si', $newUsername, $userId);
            if ($stmt->execute()) {
                $_SESSION['username'] = $newUsername;
                $username = $newUsername;
                $message = "Username berhasil diupdate.";
            } else {
                $message = "Gagal mengupdate username.";
            }
            $stmt->close();
        }
    }

    // Upload Foto Profil
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile_pic']['tmp_name'];
        $fileName = $_FILES['profile_pic']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExtension, $allowedExts)) {
            $newFileName = "profile_{$userId}." . $fileExtension;
            $destPath = $profileDir . $newFileName;

            // Hapus semua foto lama user (agar tidak dobel format)
            foreach (['jpg', 'jpeg', 'png', 'gif'] as $ext) {
                $oldPath = $profileDir . "profile_{$userId}.$ext";
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $message = "Foto profil berhasil diupload.";
                $profilePic = $destPath;
            } else {
                $message = "Gagal mengupload foto profil.";
            }
        } else {
            $message = "Format file tidak didukung. Gunakan jpg, jpeg, png, atau gif.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Profil Saya - MoneyMate</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>
  <style>
    body {
       background: rgb(153, 192, 250);
       font-family: 'Poppins';
    }
    .profile-img {
      width: 150px;
      height: 150px;
      object-fit: cover;
      object-position: center;
      border-radius: 50%;
      border: 3px solid #f4f4f4;
      background-color: #fff;
    }
    .navbar-custom {
      background-color:  #d6eaff;
    }
    .navbar-custom .navbar-brand,
    .navbar-custom .navbar-nav .nav-link {
      color: black;
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
  <h2>Profil Saya</h2>

  <?php if ($message): ?>
    <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <div class="mb-4">
    <img src="<?= htmlspecialchars($profilePic) ?>" alt="Foto Profil" class="profile-img" id="currentPic" />
  </div>

  <form method="post" enctype="multipart/form-data" class="mb-4">
    <div class="mb-3">
      <label for="profile_pic" class="form-label">Upload Foto Profil</label>
      <input class="form-control" type="file" name="profile_pic" id="profile_pic" accept=".jpg,.jpeg,.png,.gif" onchange="previewImage(event)" />
    </div>
    <img id="preview" class="profile-img d-none mb-3" />
    <button type="submit" class="btn btn-primary">Upload Foto</button>
    <form method="post" class="mb-4">
    <button type="submit" name="delete_photo" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus foto profil?')">Hapus Foto Profil</button>
</form>

  </form>

  <form method="post">
    <div class="mb-3">
      <label for="username" class="form-label">Username</label>
      <input type="text" name="username" id="username" class="form-control" value="<?= htmlspecialchars($username) ?>" required />
    </div>
    <button type="submit" name="update_username" class="btn btn-success">Update Username</button>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
<script>
function previewImage(event) {
  const preview = document.getElementById('preview');
  const file = event.target.files[0];
  if (file) {
    preview.src = URL.createObjectURL(file);
    preview.classList.remove('d-none');
  }
}
</script>
</body>
</html>
