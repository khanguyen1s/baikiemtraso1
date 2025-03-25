<?php
session_start();
$username = $_SESSION['username'] ?? $_COOKIE['username'] ?? null;
$baseUrl = str_contains($_SERVER['PHP_SELF'], '/products/') ? '../' : '';
$imgUrl = str_contains($_SERVER['PHP_SELF'], '/webbanhang/') ? '../' : '';
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    setcookie("email", "", time() - 3600, "/");
    setcookie("username", "", time() - 3600, "/");
    setcookie("password", "", time() - 3600, "/");
    session_regenerate_id(true);
    header("Location: " . $baseUrl . "login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $baseUrl ?>cssweb/header.css">
</head>
<body>
<header>
    <div class="container">
        <div class="logo">
            <a href="<?= $baseUrl ?><?= $username ? 'dashboard.php' : 'index.php' ?>">
                <img src="<?= $baseUrl ?>img/logo.jpg" alt="Logo">
            </a>
        </div>

        <form class="search-box">
            <input type="text" placeholder="Tìm kiếm...">
            <button type="submit">Search</button>
        </form>

        <nav>
            <ul>
                <?php if ($username): ?>
                    <li><a href="#"><?= htmlspecialchars($username) ?></a></li>
                    <li><a href="?logout=true">Logout</a></li>
                <?php else: ?>
                    <li><a href="<?= $baseUrl ?>login.php">Sign In</a></li>
                    <li><a href="<?= $baseUrl ?>register.php">Sign Up</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <nav class="main-nav">
        <ul>
            <li><a href="<?= $baseUrl ?><?= $username ? 'dashboard.php' : 'index.php' ?>">Trang chủ</a></li>
            <li><a href="<?= $baseUrl ?>#">Giới Thiệu</a></li>
            <li><a href="<?= $baseUrl ?>contact.php">Liên hệ</a></li>
        </ul>
    </nav>
</header>
</body>
</html>
