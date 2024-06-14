<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $email = $_POST['email'];
    $do_tar = $_POST['do_tar'];

    // Admin ve Editor için sayaçları kontrol et
    if ($role == 'admin' || $role == 'editor') {
        $count_query = "SELECT count FROM role_counters WHERE role = '$role'";
        $count_result = $conn->query($count_query);

        if ($count_result->num_rows > 0) {
            $row = $count_result->fetch_assoc();
            $count = $row['count'];

            if ($count >= 1) {
                echo ucfirst($role) . " rolü için kayıt limiti dolmuştur.";
                exit;
            }
        }
    }

    // Kullanıcıyı ekle (SQL enjeksiyonuna açık şekilde)
    $insert_query = "INSERT INTO users (username, password, email, do_tar, role) 
                     VALUES ('$username', '$password', '$email', '$do_tar', '$role')";
    if ($conn->query($insert_query) === TRUE) {
        echo "Kayıt başarılı!";
    } else {
        echo "Error: " . $insert_query . "<br>" . $conn->error;
    }

    // Admin ve Editor için sayaçları güncelle
    if ($role == 'admin' || $role == 'editor') {
        $update_query = "UPDATE role_counters SET count = count + 1 WHERE role = '$role'";
        $conn->query($update_query);
    }

    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kayıt</title>
    <style>
/* Genel Stil */
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background: linear-gradient(to right, #6a11cb, #2575fc); /* Arka plan için mor tonlarında degrade */
    color: #333;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    overflow: hidden; /* Yıldızların taşmasını engelle */
}

/* Yıldız Efekti */
.stars {
    position: absolute;
    width: 100%;
    height: 100%;
    background: transparent;
    z-index: 0;
}

.stars::before, .stars::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: transparent;
    animation: animStars 100s linear infinite;
}

.stars::after {
    background: transparent;
    animation-delay: 50s;
}

@keyframes animStars {
    0% { transform: translateY(0); }
    100% { transform: translateY(-1000px); }
}

/* Konteyner */
.container {
    background: rgba(255, 255, 255, 0.9);
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    text-align: center;
    width: 300px;
    position: relative;
    z-index: 1;
}

/* Başlık */
.container h2 {
    margin-bottom: 20px;
    color: #6a11cb;
}

/* Etiketler */
.container label {
    display: block;
    margin-bottom: 10px;
    color: #666;
    font-weight: bold;
}

/* Girdi Kutuları */
.container input[type="text"],
.container input[type="password"] {
    width: calc(100% - 20px);
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-sizing: border-box;
}

/* Butonlar */
.container input[type="submit"] {
    background: #6a11cb;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.container input[type="submit"]:hover {
    background: #8e2de2;
}

.container form {
    margin-bottom: 20px;
}


        </style>
</head>
<body>
    <div class="container">
        <h2>Kayıt Ol</h2>
        <form method="POST" action="register.php">
            <label>Kullanıcı Adı:</label>
            <input type="text" name="username" required><br>
            <label>Şifre:</label>
            <input type="password" name="password" required><br>
            <label>E-posta:</label>
            <input type="email" name="email" required><br>
            <label>Doğum Tarihi:</label>
            <input type="date" name="do_tar" required><br>
            <label>Rol:</label>
            <select name="role" required>
                <option value="viewer">Viewer</option>
                <option value="editor">Editor</option>
                <option value="admin">Admin</option>
            </select><br>
            <input type="submit" value="Kayıt Ol">
        </form>
        <form action="login.php">
            <input type="submit" value="Giriş Sayfası">
        </form>
    </div>
</body>
</html>
