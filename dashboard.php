<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="./css/styleWeb.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
</head>
<body>
<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $servername = "localhost";
    $username = "banhang";
    $password = "12345";
    $dbname = "thuong_mai_dien_tu";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }
    $sql = "SELECT COUNT(*) AS total FROM products";
    $result = $conn->query($sql);
    $totalProducts = 0;
    if ($result && $row = $result->fetch_assoc()) {
        $totalProducts = $row['total'];
    }
?>
    <?php include './include/header.php';?>
<main>
    <section class="dashboard">
        <h2>Bảng Điều Khiển</h2>
            <div class="bottom-section">
                <div class="left-section">
                    <h3>Đơn hàng</h3>
                    <button onclick="location.href='store.php'">Chuyển Trang</button>
                </div>
                <div class="right-section">
                    <h3>Sản Phẩm</h3>
                    <h4>Có <?php echo $totalProducts; ?> sản phẩm</h4>
                    <button onclick="location.href='products/index.php'">Chuyển Trang</button>
                </div>
            </div>
    </section>
</main>
    <?php include('./include/footer.php'); ?>
</body>
</html>
