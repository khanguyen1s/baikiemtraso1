<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>index</title>
    <link rel="stylesheet" href="./css/styleWeb.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
</head>
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
    <?php require('./include/header.php')?>
<main>
    <div class="index-container">
        <p class="small-text">RƯỢU NGON THÀNH TỊNH</p>
        <h1>Đăng kí để sử dụng</h1>
        <p class="description">
            Biến những giấc mơ tuyệt vời nhất của bạn thành hiện thực với hơn 20 loại và tham quan.
        </p>
        <div class="buttons">
            <a href="register.php" class="btn primary">Đăng kí</a>
            <a href="login.php" class="btn secondary">Bạn đã đăng kí?</a>
        </div>
    </div>
</main>
    <?php require('./include/footer.php')?>
</body>
</html>