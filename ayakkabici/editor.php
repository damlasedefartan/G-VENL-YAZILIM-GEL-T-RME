<?php
include 'session_check.php';
include 'config.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Kullanıcı rolünü veritabanından al
$username = $_SESSION['username'];
$sql = "SELECT role FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$role = $user['role'];
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="header">
        DAMLA'NIN AYAKKABILARI
    </div>
    <div class="navbar">
        <ul>
            <li><a href="admin.php">Anasayfa</a></li>
            <li><a href="hakkimda.php">Hakkımda</a></li>
            <li><a href="iletisim.php">İletişim</a></li>
            <li><a href="urunler.php">Ürünler</a></li>
            <?php if ($role == 'admin' || $role == 'editor'): ?>
                <li><a href="kullaniciis.php">Kullanıcı İşlemleri</a></li>
                <li><a href="ayakkabiis.php">Ayakkabı İşlemleri</a></li>
            <?php endif; ?>
            <li><a href="profil.php">Profil</a></li>
            <li><a href="cikis.php">Çıkış Yap</a></li>
        </ul>
    </div>
    <div class="content">
        <h1>Kitapçımıza Hoşgeldiniz.</h1>
        <p>Merhaba, <?php echo htmlspecialchars($_SESSION['username']); ?>! Bahar'ın kitap sayfasına hoş geldiniz.</p>
    </div>
    <div class="footer">
        &copy; 2024 Damla'nın Ayakkabıları. Tüm hakları saklıdır.
    </div>
</body>
</html>