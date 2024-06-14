<?php 
include 'session_check.php'; 
include 'config.php'; 
 
// Kullanıcı bilgilerini al 
function getUserInfo($username) { 
    global $conn; 
    $sql = "SELECT * FROM users WHERE username = '$username'"; 
    $result = $conn->query($sql); 
    return $result->fetch_assoc(); 
} 
 
// Yorum ekleme işlemi 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'])) { 
    // Oturumdan kullanıcı adını al 
    $username = $_SESSION['username']; 
     
    // Yorumu al (XSS saldırılarına karşı temizleme yapılmamış) 
    $comment = $_POST['comment'];  // XSS saldırısına açık 
     
    // Yorumu veritabanına eklemek için güvenli bir şekilde SQL sorgusu hazırla 
    $sql = "INSERT INTO comments (username, comment) VALUES (?, ?)"; 
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("ss", $username, $comment); 
     
    // Sorguyu çalıştır 
    if ($stmt->execute()) { 
        // Yorum başarıyla eklendi, sayfayı yenile 
        header("Refresh:0"); 
        exit(); 
    } else { 
        // Hata durumunda hata mesajını ekrana yazdır 
        echo "Yorum eklenirken bir hata oluştu: " . $conn->error; 
    } 
} 
 
// Oturumdan kullanıcı bilgilerini al 
$user = getUserInfo($_SESSION['username']); 
 
// Veritabanından yorumları al 
$sql = "SELECT * FROM comments"; 
$result = $conn->query($sql); 
$comments = $result->fetch_all(MYSQLI_ASSOC); 
?> 
<!DOCTYPE html> 
<html> 
<head> 
    <title>İletişim</title> 
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
        <h1>İletişim</h1> 
        <p>Burada iletişim bilgileri yer alacak.</p> 
        <h2>Kullanıcı Bilgileri</h2> 
        <p><strong>Kullanıcı Adı:</strong> <?php echo $user['username']; ?></p> 
        <p><strong>Email:</strong> <?php echo $user['email']; ?></p> 
        <p><strong>Doğum Tarihi:</strong> <?php echo $user['do_tar']; ?></p> 
        <p><strong>Rol:</strong> <?php echo $user['role']; ?></p> 
 
        <h2>Yorum Yap</h2> 
        <form method="post" action="iletisim.php"> 
            <!-- XSS saldırısına açık bir alan --> 
            <textarea name="comment" rows="4" cols="50" placeholder="Yorumunuzu yazın"></textarea><br> 
            <button type="submit">Gönder</button> 
        </form> 
 
        <h2>Yorumlar</h2> 
        <?php if (count($comments) > 0): ?> 
            <?php foreach ($comments as $comment): ?> 
                <div class="comment"> 
                    <!-- XSS saldırısına açık alan --> 
                    <p><strong><?php echo $comment['username']; ?>:</strong> <?php echo $comment['comment']; ?></p> 
                </div> 
            <?php endforeach; ?> 
        <?php else: ?> 
            <p>Henüz yorum yapılmamış.</p> 
        <?php endif; ?> 
    </div> 
    <div class="footer"> 
        &copy; 2024 Damla'nın Ayakkabıları. Tüm hakları saklıdır. 
    </div> 
</body> 
</html>
<?php 
//-------------------------------------------------------GÜVENLİ---------------------------------------------------------
/*
include 'session_check.php'; 
include 'config.php'; 
 
// Kullanıcı bilgilerini al 
function getUserInfo($username) { 
    global $conn; 
    $sql = "SELECT * FROM users WHERE username = ?"; 
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("s", $username); 
    $stmt->execute(); 
    $result = $stmt->get_result(); 
    return $result->fetch_assoc(); 
} 
 
// Yorum ekleme işlemi 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'])) { 
    // Oturumdan kullanıcı adını al 
    $username = $_SESSION['username']; 
     
    // Yorumu al (XSS saldırılarına karşı temizleme) 
    $comment = htmlspecialchars($_POST['comment']);  // XSS saldırılarından korunaklı hale getirildi 
     
    // Yorumu veritabanına eklemek için güvenli bir şekilde SQL sorgusu hazırla 
    $sql = "INSERT INTO comments (username, comment) VALUES (?, ?)"; 
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("ss", $username, $comment); 
     
    // Sorguyu çalıştır 
    if ($stmt->execute()) { 
        // Yorum başarıyla eklendi, sayfayı yenile 
        header("Refresh:0"); 
        exit(); 
    } else { 
        // Hata durumunda hata mesajını ekrana yazdır 
        echo "Yorum eklenirken bir hata oluştu: " . $conn->error; 
    } 
} 
 
// Oturumdan kullanıcı bilgilerini al 
$user = getUserInfo($_SESSION['username']); 
 
// Veritabanından yorumları al 
$sql = "SELECT * FROM comments"; 
$result = $conn->query($sql); 
$comments = $result->fetch_all(MYSQLI_ASSOC); 
?> 
<!DOCTYPE html> 
<html> 
<head> 
    <title>İletişim</title> 
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
        <h1>İletişim</h1> 
        <p>Burada iletişim bilgileri yer alacak.</p> 
        <h2>Kullanıcı Bilgileri</h2> 
        <p><strong>Kullanıcı Adı:</strong> <?php echo htmlspecialchars($user['username']); ?></p> 
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p> 
        <p><strong>Doğum Tarihi:</strong> <?php echo htmlspecialchars($user['do_tar']); ?></p> 
        <p><strong>Rol:</strong> <?php echo htmlspecialchars($user['role']); ?></p> 
 
        <h2>Yorum Yap</h2> 
        <form method="post" action="iletisim.php"> 
            <textarea name="comment" rows="4" cols="50" placeholder="Yorumunuzu yazın"></textarea><br> 
            <button type="submit">Gönder</button> 
        </form> 
 
        <h2>Yorumlar</h2> 
        <?php if (count($comments) > 0): ?> 
            <?php foreach ($comments as $comment): ?> 
                <div class="comment"> 
                    <p><strong><?php echo htmlspecialchars($comment['username']); ?>:</strong> <?php echo htmlspecialchars($comment['comment']); ?></p> 
                </div> 
            <?php endforeach; ?> 
        <?php else: ?> 
            <p>Henüz yorum yapılmamış.</p> 
        <?php endif; ?> 
    </div> 
    <div class="footer"> 
        &copy; 2024 Damla'nın Ayakkabıları. Tüm hakları saklıdır. 
    </div> 
</body> 
</html>
*/
?>
