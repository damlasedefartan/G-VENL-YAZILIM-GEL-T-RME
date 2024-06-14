<?php
include 'session_check.php';
include 'config.php'; // Database connection

// Oturum kontrolü
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Kullanıcıları getirme fonksiyonu
function getUsers() {
    global $conn;
    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);
    $users = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
    return $users;
}

// Kullanıcı silme fonksiyonu
function deleteUser($id) {
    global $conn;
    $sql = "DELETE FROM users WHERE id=$id";
    return $conn->query($sql);
}

// Arama işlevi
if(isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM users WHERE username LIKE '%$search%' OR email LIKE '%$search%'";
    $result = $conn->query($sql);
    $users = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
    displayUsers($users);
    exit;
}

// Kullanıcı silme işlemi
if(isset($_POST['delete'])) {
    $id = $_POST['delete'];
    if(deleteUser($id)) {
        echo "Kullanıcı başarıyla silindi.";
    } else {
        echo "Kullanıcı silinirken bir hata oluştu.";
    }
    exit;
}

// Tüm kullanıcıları getirme işlemi
if (isset($_GET['fetchUsers'])) {
    $users = getUsers();
    displayUsers($users);
    exit;
}

// Kullanıcıları ekrana yazdırma fonksiyonu
function displayUsers($users) {
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>{$user['id']}</td>";
        echo "<td>{$user['username']}</td>";
        echo "<td>{$user['email']}</td>";
        echo "<td>{$user['do_tar']}</td>";
        echo "<td>{$user['role']}</td>";
        echo "<td><button onclick=\"deleteUser({$user['id']})\">Sil</button></td>";
        echo "</tr>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Kullanıcı İşlemleri</title>
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
    button {
        background-color: #BA68C8; /* Light purple background for buttons */
        border: none;
        color: #4A148C; /* Dark purple text color for buttons */
        padding: 5px 10px;
        cursor: pointer;
    }

    button:hover {
        background-color: #D1C4E9; /* Light purple on hover */
    }
    </style>
</head>
<body>
    <h2>Kullanıcı İşlemleri</h2>

    <!-- Kullanıcı Arama Formu -->
    <form id="search-form">
        <input type="text" id="search" name="search" placeholder="Kullanıcı Adı veya E-posta ile arama yap">
        <button type="submit">Ara</button>
    </form>

    <!-- Kullanıcı Listesi -->
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Kullanıcı Adı</th>
                <th>E-posta</th>
                <th>Doğum Tarihi</th>
                <th>Rol</th>
                <th>İşlem</th>
            </tr>
        </thead>
        <tbody id="user-list">
        </tbody>
    </table>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
    // Sayfa yüklendiğinde kullanıcıları getir
    $(document).ready(function(){
        fetchUsers();
    });

    // Kullanıcı arama işlemi
    $('#search-form').submit(function(e) {
        e.preventDefault();
        var search = $('#search').val();
        $.ajax({
            url: 'kullaniciis.php?search=' + search,
            type: 'GET',
            success: function(response) {
                $('#user-list').html(response);
            }
        });
    });

    // Kullanıcı silme
    function deleteUser(id) {
        if (confirm('Kullanıcıyı silmek istediğinizden emin misiniz?')) {
            $.ajax({
                url: 'kullaniciis.php',
                type: 'POST',
                data: { delete: id },
                success: function(response) {
                    alert(response);
                    fetchUsers();
                }
            });
        }
    }

    // Kullanıcıları getirme
    function fetchUsers() {
        $.ajax({
            url: 'kullaniciis.php?fetchUsers=true',
            type: 'GET',
            success: function(response) {
                $('#user-list').html(response);
            }
        });
    }
    </script>
</body>
</html>
<?php

/*
include 'session_check.php';
include 'config.php'; // Include your database connection

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
// Kullanıcı rolünü kontrol et
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'editor') {
    header("Location: error.php"); // Ana sayfaya yönlendir
    exit;
}
// Oturum kontrolü
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    echo "Bu sayfaya erişim yetkiniz yok.";
    exit;
}


// Kullanıcıları getirme fonksiyonu
function getUsers() {
    global $conn;
    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);
    $users = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
    return $users;
}

// Yeni kullanıcı ekleme fonksiyonu
function addUser($username, $password, $email, $do_tar, $role) {
    global $conn;
    $sql = "INSERT INTO users (username, password, email, do_tar, role) VALUES ('$username', '$password', '$email', '$do_tar', '$role')";
    return $conn->query($sql);
}

// Kullanıcı silme fonksiyonu
function deleteUser($id) {
    global $conn;
    $sql = "DELETE FROM users WHERE id=$id";
    return $conn->query($sql);
}

// Kullanıcı güncelleme fonksiyonu
function updateUser($id, $username, $password, $email, $do_tar, $role) {
    global $conn;
    $sql = "UPDATE users SET username='$username', password='$password', email='$email', do_tar='$do_tar', role='$role' WHERE id=$id";
    return $conn->query($sql);
}

// Arama işlevi
if(isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM users WHERE username LIKE '%$search%' OR email LIKE '%$search%'";
    $result = $conn->query($sql);
    $users = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
    displayUsers($users);
    exit;
}

// Kullanıcı ekleme işlemi
if(isset($_POST['add'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $do_tar = $_POST['do_tar'];
    $role = $_POST['role'];
    if(addUser($username, $password, $email, $do_tar, $role)) {
        echo "Kullanıcı başarıyla eklendi.";
    } else {
        echo "Kullanıcı eklenirken bir hata oluştu.";
    }
    exit;
}

// Kullanıcı silme işlemi
if(isset($_POST['delete'])) {
    $id = $_POST['delete'];
    if(deleteUser($id)) {
        echo "Kullanıcı başarıyla silindi.";
    } else {
        echo "Kullanıcı silinirken bir hata oluştu.";
    }
    exit;
}

// Kullanıcı güncelleme işlemi
if(isset($_POST['update'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $do_tar = $_POST['do_tar'];
    $role = $_POST['role'];
    if(updateUser($id, $username, $password, $email, $do_tar, $role)) {
        echo "Kullanıcı başarıyla güncellendi.";
    } else {
        echo "Kullanıcı güncellenirken bir hata oluştu.";
    }
    exit;
}

// Tüm kullanıcıları getirme işlemi
if (isset($_GET['fetchUsers'])) {
    $users = getUsers();
    displayUsers($users);
    exit;
}

// Kullanıcıları ekrana yazdırma fonksiyonu
function displayUsers($users) {
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>{$user['id']}</td>";
        echo "<td>{$user['username']}</td>";
        echo "<td>{$user['password']}</td>";
        echo "<td>{$user['email']}</td>";
        echo "<td>{$user['do_tar']}</td>";
        echo "<td>{$user['role']}</td>";
        echo "<td><button onclick=\"deleteUser({$user['id']})\">Sil</button>";
        echo "<button onclick=\"openUpdateForm({$user['id']}, '{$user['username']}', '{$user['password']}', '{$user['email']}', '{$user['do_tar']}', '{$user['role']}')\">Güncelle</button></td>";
        echo "</tr>";
    }
}
*/
?>