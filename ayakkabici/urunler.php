<?php
include 'session_check.php';
include 'config.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Ayakkabıları veritabanından al
function getAyakkabilar($limit = 4, $search = '') {
    global $conn;
    $sql = "SELECT * FROM ayakkabilar";
    if ($search) {
        $sql .= " WHERE marka LIKE '%$search%' OR turu LIKE '%$search%'";
    }
    $sql .= " LIMIT $limit";
    $result = $conn->query($sql);
    $ayakkabilar = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ayakkabilar[] = $row;
        }
    }
    return $ayakkabilar;
}

$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

// Kullanıcı girişini temizle
$safe_search = htmlspecialchars($search);

// Eğer kullanıcı arama kutusuna belirli bir JavaScript kodu yazdıysa
if (strpos($search, "<script>alert('XSS')") !== false) {
    echo "<script>alert('XSS saldırısı algılandı!'); window.location.href = 'urunler.php';</script>";
    exit; // XSS bildirimi gösterildiği için işlemi sonlandır
}

$ayakkabilar = getAyakkabilar(4, $safe_search);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ayakkabılar</title>
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
        <h1>Ayakkabılar</h1>
        <form method="get" action="urunler.php">
            <input type="text" name="search" placeholder="Marka veya tür ara" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Ara</button>
        </form>
        <?php if (count($ayakkabilar) > 0): ?>
            <?php foreach ($ayakkabilar as $ayakkabi): ?>
                <div class="ayakkabi">
                    <h3><?php echo htmlspecialchars($ayakkabi['marka']); ?></h3>
                    <p><strong>Fiyat:</strong> <?php echo htmlspecialchars($ayakkabi['fiyat']); ?> TL</p>
                    <p><strong>Türü:</strong> <?php echo htmlspecialchars($ayakkabi['turu']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Ayakkabı bulunamadı.</p>
        <?php endif; ?>
    </div>
    <div class="footer">
        &copy; 2024 Damla'nın Ayakkabı Mağazası. Tüm hakları saklıdır.
    </div>
</body>
</html>
