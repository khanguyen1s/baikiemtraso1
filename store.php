<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Danh sách sản phẩm</title>
    <link rel="stylesheet" href="./css/styleWeb.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <style>
        .product-wrapper {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 40px;
        }

        .product-card {
            background-color: var(--white-color);
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 16px;
            width: 250px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s;
            text-decoration: none;
            color: inherit;
        }

        .product-card:hover {
            transform: scale(1.03);
        }

        .product-name {
            font-weight: bold;
            font-size: 18px;
            color: var(--first-color);
            margin-bottom: 8px;
        }

        .product-note {
            font-size: 14px;
            color: #555;
            margin-bottom: 10px;
        }

        .product-price {
            color: red;
            font-weight: bold;
            font-size: 16px;
        }

        .product-image {
            width: 100%;
            height: auto;
            border-radius: 4px;
            margin-bottom: 8px;
        }
    </style>
<?php
    $servername = "localhost";
    $username = "banhang";
    $password = "12345";
    $dbname = "thuong_mai_dien_tu";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }
    $sql = "SELECT id, product_name, description, price, image FROM products";
    $result = $conn->query($sql);
?>
</head>
<body>
    <?php include './include/header.php'; ?>
<main>
    <div class="containert">
        <h2 class="h2">Danh sách sản phẩm</h2>
        <div class="product-wrapper">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <a href="product.php?id=<?= $row['id'] ?>" class="product-card">
                        <?php if (!empty($row['image'])): ?>
                            <img src="./uploads/<?= htmlspecialchars($row['image']) ?>" 
                                 alt="<?= htmlspecialchars($row['product_name']) ?>" 
                                 class="product-image">
                        <?php endif; ?>
                        <div class="product-name"><?= htmlspecialchars($row['product_name']) ?></div>
                        <div class="description"><?= htmlspecialchars($row['description']) ?></div>
                        <div class="product-price">₫<?= number_format($row['price'], 0, ',', '.') ?></div>
                    </a>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Không có sản phẩm nào.</p>
            <?php endif; ?>
            <?php $conn->close(); ?>
        </div>
    </div>
</main>
    <?php include('./include/footer.php'); ?>
</body>
</html>
