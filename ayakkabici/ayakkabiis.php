<?php
session_start();
include 'config.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
// Kullanıcı rolünü kontrol et
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'editor') {
    header("Location: viewer.php"); // Ana sayfaya yönlendir
    exit;
}

// Veritabanı bağlantısı ve ayakkabı işlemleri
function getShoes() {
    global $conn;
    $sql = "SELECT * FROM ayakkabilar";
    $result = $conn->query($sql);
    $shoes = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $shoes[] = $row;
        }
    }
    return $shoes;
}

function addShoe($marka, $fiyat, $turu) {
    global $conn;
    $sql = "INSERT INTO ayakkabilar (marka, fiyat, turu) VALUES ('$marka', '$fiyat', '$turu')";
    return $conn->query($sql);
}

function deleteShoe($id) {
    global $conn;
    $sql = "DELETE FROM ayakkabilar WHERE id=$id";
    return $conn->query($sql);
}

function updateShoe($id, $marka, $fiyat, $turu) {
    global $conn;
    $sql = "UPDATE ayakkabilar SET marka='$marka', fiyat='$fiyat', turu='$turu' WHERE id=$id";
    return $conn->query($sql);
}

if(isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM ayakkabilar WHERE marka LIKE '%$search%' OR turu LIKE '%$search%'";
    $result = $conn->query($sql);
    $shoes = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $shoes[] = $row;
        }
    }
    displayShoes($shoes);
    exit;
}

if(isset($_POST['add'])) {
    $marka = $_POST['marka'];
    $fiyat = $_POST['fiyat'];
    $turu = $_POST['turu'];
    if(addShoe($marka, $fiyat, $turu)) {
        echo "Ayakkabı başarıyla eklendi.";
    } else {
        echo "Ayakkabı eklenirken bir hata oluştu.";
    }
    exit;
}

if(isset($_POST['delete'])) {
    $id = $_POST['delete'];
    if(deleteShoe($id)) {
        echo "Ayakkabı başarıyla silindi.";
    } else {
        echo "Ayakkabı silinirken bir hata oluştu.";
    }
    exit;
}

if(isset($_POST['update'])) {
    $id = $_POST['id'];
    $marka = $_POST['marka'];
    $fiyat = $_POST['fiyat'];
    $turu = $_POST['turu'];
    if(updateShoe($id, $marka, $fiyat, $turu)) {
        echo "Ayakkabı başarıyla güncellendi.";
    } else {
        echo "Ayakkabı güncellenirken bir hata oluştu.";
    }
    exit;
}

if (isset($_GET['fetchShoes'])) {
    $shoes = getShoes();
    displayShoes($shoes);
    exit;
}

function displayShoes($shoes) {
    foreach ($shoes as $shoe) {
        echo "<tr>";
        echo "<td>{$shoe['id']}</td>";
        echo "<td>{$shoe['marka']}</td>";
        echo "<td>{$shoe['fiyat']}</td>";
        echo "<td>{$shoe['turu']}</td>";
        echo "<td><button onclick=\"deleteShoe({$shoe['id']})\">Sil</button>";
        echo "<button onclick=\"openUpdateForm({$shoe['id']}, '{$shoe['marka']}', '{$shoe['fiyat']}', '{$shoe['turu']}')\">Güncelle</button></td>";
        echo "</tr>";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Ayakkabı İşlemleri</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
      /* Table styles */
table {
    width: 100%;
    border-collapse: collapse;
    background-color: #f3e5f5; /* Light pink background */
    color: #4A148C; /* Dark purple text color */
}

th, td {
    border: 1px solid #4A148C; /* Dark purple border */
    padding: 8px;
    text-align: left;
    color: #4A148C; /* Dark purple text color */
}

th {
    background-color: #BA68C8; /* Light purple background for headers */
    color: #4A148C; /* Dark purple text color for headers */
}

tr:nth-child(even) {
    background-color: #D1C4E9; /* Light purple for even rows */
}

tr:hover {
    background-color: #BA68C8; /* Light purple highlight on hover */
}

/* Button styles */
#shoe-list button {
    background-color: #BA68C8; /* Light purple background for buttons */
    border: none;
    color: #4A148C; /* Dark purple text color for buttons */
    padding: 5px 10px;
    cursor: pointer;
}

#shoe-list button:hover {
    background-color: #D1C4E9; /* Light purple on hover */
}



    </style>
</head>
<body>
    <div class="header">
        Damla'ın Ayakkabıcısı
    </div>
    <div class="navbar">
        <ul>
            <li><a href="admin.php">Anasayfa</a></li>
            <li><a href="hakkimda.php">Hakkımda</a></li>
            <li><a href="iletisim.php">İletişim</a></li>
            <li><a href="urunler.php">Ürünler</a></li>
            <li><a href="kullaniciis.php">Kullanıcı İşlemleri</a></li>
            <li><a href="ayakkabiis.php">Ayakkabı İşlemleri</a></li>
            <li><a href="profil.php">Profil</a></li>
            <li><a href="cikis.php">Çıkış Yap</a></li>
        </ul>
    </div>
    <div class="content">
        <h2>Ayakkabı İşlemleri</h2>

        <!-- Ayakkabı Arama Formu -->
        <form id="search-form">
            <input type="text" id="search" name="search" placeholder="Marka veya Tür ile arama yap">
            <button type="submit">Ara</button>
        </form>

        <!-- Ayakkabı Ekleme Formu -->
        <form id="add-form">
            <input type="text" id="marka" name="marka" placeholder="Marka" required>
            <input type="number" id="fiyat" name="fiyat" placeholder="Fiyat" required>
            <input type="text" id="turu" name="turu" placeholder="Türü" required>
            <button type="submit" name="add">Ayakkabı Ekle</button>
        </form>

        <!-- Ayakkabı Listesi -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Marka</th>
                    <th>Fiyat</th>
                    <th>Türü</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody id="shoe-list">
            </tbody>
        </table>

        <!-- Ayakkabı Güncelleme Formu -->
        <div id="update-form" style="display: none;">
            <h3>Ayakkabı Bilgisini Güncelle</h3>
            <form id="update-shoe-form">
                <input type="hidden" id="update-id" name="id">
                <input type="text" id="update-marka" name="marka" placeholder="Yeni Marka" required>
                <input type="number" id="update-fiyat" name="fiyat" placeholder="Yeni Fiyat" required>
                <input type="text" id="update-turu" name="turu" placeholder="Yeni Türü" required>
                <button type="submit" name="update">Güncelle</button>
                <button type="button" id="cancel-update">İptal</button>
            </form>
        </div>

        <script>
        $(document).ready(function(){
            fetchShoes();
        });

        $('#search-form').submit(function(e) {
            e.preventDefault();
            var search = $('#search').val();
            $.ajax({
                url: 'ayakkabiis.php?search=' + search,
                type: 'GET',
                success: function(response) {
                    $('#shoe-list').html(response);
                }
            });
        });

        $('#add-form').submit(function(e) {
            e.preventDefault();
            var marka = $('#marka').val();
            var fiyat = $('#fiyat').val();
            var turu = $('#turu').val();
            $.ajax({
                url: 'ayakkabiis.php',
                type: 'POST',
                data: {
                    add: true,
                    marka: marka,
                    fiyat: fiyat,
                    turu: turu
                },
                success: function(response) {
                    alert(response);
                    fetchShoes();
                }
            });
        });

        function deleteShoe(id) {
            if (confirm('Ayakkabıyı silmek istediğinizden emin misiniz?')) {
                $.ajax({
                    url: 'ayakkabiis.php',
                    type: 'POST',
                    data: { delete: id },
                    success: function(response) {
                        alert(response);
                        fetchShoes();
                    }
                });
            }
        }

        function openUpdateForm(id, marka, fiyat, turu) {
            $('#update-id').val(id);
            $('#update-marka').val(marka);
            $('#update-fiyat').val(fiyat);
            $('#update-turu').val(turu);
            $('#update-form').show();
        }

        $('#update-shoe-form').submit(function(e) {
            e.preventDefault();
            var id = $('#update-id').val();
            var marka = $('#update-marka').val();
            var fiyat = $('#update-fiyat').val();
            var turu = $('#update-turu').val();
            $.ajax({
                url: 'ayakkabiis.php',
                type: 'POST',
                data: {
                    update: true,
                    id: id,
                    marka: marka,
                    fiyat: fiyat,
                    turu: turu
                },
                success: function(response) {
                    alert(response);
                    fetchShoes();
                    $('#update-form').hide();
                }
            });
        });

        $('#cancel-update').click(function() {
            $('#update-form').hide();
        });

        function fetchShoes() {
            $.ajax({
                url: 'ayakkabiis.php?fetchShoes=true',
                type: 'GET',
                success: function(response) {
                    $('#shoe-list').html(response);
                }
            });
        }
        </script>
    </div>
    <div class="footer">
        &copy; 2024 Damla'nın Ayakkabıcısı. Tüm hakları saklıdır.
    </div>
</body>
</html>
