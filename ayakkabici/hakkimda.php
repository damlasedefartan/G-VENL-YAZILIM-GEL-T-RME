<?php
include 'session_check.php';
//session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Hakkımda</title>
    <link rel="stylesheet" type="text/css" href="style.css">
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
        <h1>Hakkımda</h1>
        <p>Merhaba ve hoş geldiniz! Biz Damla'nın Ayakkabıları olarak, adımlarınıza zarafet ve konfor katmak için buradayız. Kurulduğumuz günden bu yana, kalite, stil ve müşteri memnuniyetini ön planda tutarak, her yaştan ve her zevkten müşterimize hizmet vermekten gurur duyuyoruz.</p>

        <p>Damla'nın Ayakkabıları, modayı ve rahatlığı bir araya getirerek her adımınızı özel kılar. Geniş ürün yelpazemizde yer alan ayakkabılarımız, hem klasik hem de modern tarzda seçenekler sunar. İşe giderken, özel bir davete katılırken ya da günlük yaşamda her anınızda size eşlik edecek mükemmel ayakkabıları burada bulabilirsiniz.</p>

        <h1>Neden Biz?</h1>
        <p>Kalite ve Konfor: En yüksek kaliteli malzemelerden üretilen ayakkabılarımız, uzun süreli rahatlık ve dayanıklılık sağlar.</p>
        <p>Geniş Ürün Yelpazesi: Kadın, erkek ve çocuklar için her türden ayakkabı modeline sahibiz.</p>
        <p>Müşteri Memnuniyeti: Müşterilerimizin memnuniyeti bizim için her şeyden önce gelir. Size en iyi alışveriş deneyimini sunmak için buradayız.</p>
        <p>Moda ve Şıklık: Sezonun en trend modelleri ve klasik şıklık, her zevke hitap eden seçeneklerimizle sizlerle.</p>

        <h1>Damla'nın Ayakkabıları: Adımlarınızda Şıklık ve Konfor!</h1>
        <p>Bizi tercih ettiğiniz için teşekkür ederiz. Damla'nın Ayakkabıları ailesinin bir parçası olmaktan mutluluk duyacağız. Haydi, adımlarınıza zarafet katmak için en güzel ayakkabılarımızı keşfedin!</p>
    </div>

    <div class="footer">
        &copy; 2024 Damla'nın Ayakkabıları. Tüm hakları saklıdır.
    </div>
</body>
</html>
