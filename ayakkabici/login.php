<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Güvenli olmayan SQL sorgusu (SQL enjeksiyonuna açık)
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $row['role'];
        $_SESSION['login_time'] = time();
        $_SESSION['session_timeout'] = 600; // Oturum süresi 10 dakika

        // Rol bazlı yönlendirme
        if ($row['role'] == 'admin') {
            header("Location: admin.php");
        } elseif ($row['role'] == 'editor') {
            header("Location: editör.php");
        } elseif ($row['role'] == 'viewer') {
            header("Location: viewer.php");
        } else {
            header("Location: error.php");
        }
        exit;
    } else {
        echo "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
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
        <h2>Login</h2>
        <form method="POST" action="login.php">
            <label>Username:</label>
            <input type="text" name="username" required><br>
            <label>Password:</label>
            <input type="password" name="password" required><br>
            <input type="submit" value="Login">
        </form>
        <form action="register.php">
            <input type="submit" value="Kayıt Sayfasına Git">
        </form>
    </div>
</body>
</html>
<?php 

session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Güvenli SQL sorgusu (SQL enjeksiyonuna karşı korumalı)
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $row['role'];
        $_SESSION['login_time'] = time();
        $_SESSION['session_timeout'] = 600; // Oturum süresi 10 dakika

        // Rol bazlı yönlendirme
        if ($row['role'] == 'admin') {
            header("Location: admin.php");
        } elseif ($row['role'] == 'editor') {
            header("Location: editor.php");
        } elseif ($row['role'] == 'viewer') {
            header("Location: viewer.php");
        } else {
            header("Location: error.php");
        }
        exit;
    } else {
        echo "Invalid username or password";
    }

    $stmt->close();
    $conn->close();
}
?>
