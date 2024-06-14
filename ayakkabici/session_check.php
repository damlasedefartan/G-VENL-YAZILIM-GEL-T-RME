<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Oturum süresini kontrol et
if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] > 600)) {
    session_destroy();
    header("Location: login.php");
    exit;
} else {
    $_SESSION['login_time'] = time();
}
?>
