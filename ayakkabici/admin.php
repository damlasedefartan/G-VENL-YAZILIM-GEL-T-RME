
<?php


// Include session check after starting session
include 'session_check.php';

// Redirect to login if user is not authenticated
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="style.css?v=1.0">
</head>
<body>
    <div class="header">
        DAMLA'NIN AYAKKABILARI
    </div>
    <div class="navbar">
        <ul>
            <li><a href="viewer.php">Anasayfa</a></li>
            <li><a href="hakkimda.php">Hakkımda</a></li>
            <li><a href="iletisim.php">İletişim</a></li>
            <li><a href="profil.php">Profil</a></li>
            <li><a href="urunler.php">Ürünler</a></li>
            <li><a href="cikis.php">Çıkış Yap</a></li>
        </ul>
    </div>
    <div class="content">
        <h1>Ayakkabı Dükkanıma Hoşgeldiniz.</h1>
        <p>Merhaba, <?php echo htmlspecialchars($_SESSION['username']); ?>! Damla'nın Ayakkabıcısına hoş geldiniz.</p>
    </div>
    <div class="footer">
        &copy; 2024 Damla'nın Ayakkabıları. Tüm hakları saklıdır.
    </div>
</body>
</html>
