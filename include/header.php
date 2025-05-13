<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $baseUrl ?>css/header.css">
</head>
<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();}
    $user = isset($_SESSION['username']) ? $_SESSION['username'] : null;
    $baseUrl = str_contains($_SERVER['PHP_SELF'], '/products/') ? '../' : '';
    $imgUrl = str_contains($_SERVER['PHP_SELF'], '/webbanhang/') ? '../' : '';
    if (isset($_GET['logout'])) {
        session_destroy();
        header("Location: login.php");
        exit;
    }
?>
<?php
$servername = "localhost";
$username = "banhang";
$password = "12345";
$dbname = "thuong_mai_dien_tu";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>
<body>
<header>
    <div class="container">
        <div class="logo">
            <a href="<?= $baseUrl ?><?= $user ? 'dashboard.php' : 'index.php' ?>">
                <img src="<?= $baseUrl ?>img/logo.jpg" alt="Logo">
            </a>
        </div>

        <form class="search-box">
            <input type="text" placeholder="Tìm kiếm...">
            <button type="submit">Search</button>
        </form>

        <nav>
            <ul>
                <?php if ($user): ?>
                    <li><a href="<?= $baseUrl ?>profile.php"><?= htmlspecialchars($user) ?></a></li>
                    <li><a href="?logout=true">Đăng xuất</a></li>
                <?php else: ?>
                    <li><a href="<?= $baseUrl ?>login.php">Đăng nhập</a></li>
                    <li><a href="<?= $baseUrl ?>register.php">Đăng ký</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>

<nav class="main-nav">
    <ul>
        <li><a href="<?= $baseUrl ?><?= $user ? 'dashboard.php' : 'index.php' ?>">Trang chủ</a></li>
        <li><a href="<?= $baseUrl ?>#">Giới thiệu</a></li>
        <li><a href="<?= $baseUrl ?>contact.php">Liên hệ</a></li>
    </ul>
</nav>
</body>
</html>
