<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="./cssweb/styleWeb.css">
</head>
<?php
    $productsFile = './products/products.json';
    $jsonData   = file_get_contents($productsFile);
    $products   = json_decode($jsonData, true);
    $totalProducts = count($products);
?>
<body>
    <?php include './include/header.php';?>
<main>
    <section class="dashboard">
        <h2>Bảng Điều Khiển</h2>
        <div class="gauges">
            <div class="gauge-container">
                
                <p>Sản phẩm: <strong><?php echo $totalProducts; ?></strong></p>
            </div>
            <div class="gauge-container">
                
                <p>Người dùng: <strong>#</strong></p>
            </div>
            <div class="gauge-container">
                
                <p>Đơn hàng: <strong>#</strong></p>
            </div>
        </div>
        <div class="bottom-section">
            <div class="left-section">
                <h3>Đơn hàng</h3>
                <p>Có # đơn hàng cần xử lý</p>
            </div>
            <div class="right-section">
                <h3>Sản Phẩm</h3>
                <button onclick="location.href='products/index.php'">Chuyển Trang</button>
            </div>
        </div>
    </section>
</main>
    <?php include('./include/footer.php')?>
</body>