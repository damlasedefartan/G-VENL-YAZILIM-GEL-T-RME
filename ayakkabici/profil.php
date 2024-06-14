<?php

include 'session_check.php';

// Oturum açmış kullanıcıyı kontrol et
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profil</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        /* Sayfa Genel Stili */
        body {
            background-color: #f3e5f5; /* Açık pembe arka plan */
            color: #4A148C; /* Mor metin rengi */
            font-family: 'Helvetica Neue', Arial, sans-serif; /* Genel yazı tipi */
            margin: 0;
            padding: 0;
        }

        /* Header Stili */
        .header {
            background-color: #6A1B9A; /* Mor arka plan */
            color: #fff; /* Beyaz metin rengi */
            text-align: center;
            padding: 20px;
            font-size: 28px;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Navbar Stili */
        .navbar ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
            background-color: #4A148C; /* Koyu mor arka plan */
        }

        .navbar ul li {
            margin: 0 15px;
        }

        .navbar ul li a {
            color: #fff; /* Beyaz metin rengi */
            text-decoration: none;
            font-weight: bold;
            padding: 14px 20px;
            display: block;
            transition: background-color 0.3s ease;
        }

        .navbar ul li a:hover {
            background-color: #D1C4E9; /* Açık mor arka plan */
            color: #4A148C; /* Koyu mor metin rengi */
        }

        /* İçerik Stili */
        .content {
            background-color: #fff; /* Beyaz arka plan */
            color: #4A148C; /* Mor metin rengi */
            padding: 20px;
            margin: 20px auto;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            max-width: 800px;
        }

        /* Profil Bilgileri Stili */
        .profile-info {
            margin-top: 20px;
            padding: 20px;
            border: 2px solid #6A1B9A; /* Mor kenarlık */
            border-radius: 10px;
            background-color: #f3e5f5; /* Açık pembe arka plan */
            color: #4A148C; /* Mor metin rengi */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .profile-info p {
            margin-bottom: 15px;
        }

        /* Profil Bilgileri Başlık Stili */
        .profile-info h2 {
            color: #BA68C8; /* Açık mor renk */
            border-bottom: 2px solid #BA68C8; /* Açık mor alt çizgi */
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        /* Profil Bilgileri Detay Stili */
        .profile-info p strong {
            color: #4A148C; /* Mor metin rengi */
        }

        /* Footer Stili */
        .footer {
            text-align: center;
            padding: 10px;
            background-color: #6A1B9A; /* Mor arka plan */
            color: #fff; /* Beyaz metin rengi */
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
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
            <li><a href="kullaniciis.php">Kullanıcı İşlemleri</a></li>
            <li><a href="ayakkabiis.php">Ayakkabı İşlemleri</a></li>
            <li><a href="profil.php">Profil</a></li>
            <li><a href="urunler.php">Ürünler</a></li>
            <li><a href="cikis.php">Çıkış Yap</a></li>
        </ul>
    </div>
    <div class="content">
        <h1>Profil</h1>
        <div class="profile-info">
            <?php
            // Veritabanı bağlantısı
            include 'config.php';

            // Session'dan kullanıcı adını al
            $username = $_SESSION['username'];

            // Kullanıcı bilgilerini veritabanından al
            $query = "SELECT * FROM users WHERE username='$username'";
            $result = $conn->query($query);

            // Sonuçları kontrol et ve profil bilgilerini görüntüle
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo "<h2>Profil Bilgileri</h2>";
                echo "<p><strong>Kullanıcı Adı:</strong> " . $row['username'] . "</p>";
                echo "<p><strong>E-posta:</strong> " . $row['email'] . "</p>";
                echo "<p><strong>Doğum Tarihi:</strong> " . $row['do_tar'] . "</p>";
                echo "<p><strong>Rol:</strong> " . $row['role'] . "</p>";
            } else {
                echo "Profil bilgileri bulunamadı.";
            }

            // Veritabanı bağlantısını kapat
            $conn->close();
            ?>
        </div>
    </div>
    <div class="footer">
        &copy; 2024 Damla'nın Ayakkabıları. Tüm hakları saklıdır.
    </div>
</body>
</html>
