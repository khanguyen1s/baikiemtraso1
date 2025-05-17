<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết sản phẩm</title>
    <link rel="stylesheet" href="./css/styleWeb.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <style>
                        .product {
            background-color: white;
            max-width: 1000px;
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: row;
            overflow: hidden;
            margin: 20px auto;
        }

        .product img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            object-fit: cover;
        }

        .product-image-container {
            width: 50%;
            padding: 20px;
            box-sizing: border-box;
            background-color: #fafafa;
        }

        .product-info {
            padding: 30px 25px;
            width: 50%;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .product-info h2 {
            font-size: 26px;
            margin-bottom: 15px;
            color: black;
        }

        .product-info p {
            color: black;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .price {
            font-size: 22px;
            color: var(--first-color);
            font-weight: bold;
            margin-bottom: 25px;
        }

        .quantity-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .quantity-container input {
            width: 60px;
            padding: 5px;
            margin-right: 10px;
            text-align: center;
        }

        .buy-btn {
            background-color: var(--button-background);
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            text-transform: uppercase;
            align-self: flex-start;
        }

        .buy-btn:hover {
            background-color: var(--button-hover);
            transition: 0.3s ease;
        }

        @media (max-width: 768px) {
            .product {
                flex-direction: column;
            }

            .product-image-container,
            .product-info {
                width: 100%;
            }

            .product-info h2 {
                font-size: 22px;
            }

            .price {
                font-size: 20px;
            }
        }
    </style>
</head>
<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
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
    <?php require('./include/header.php'); ?>
<main>
    <?php if ($product_id > 0): ?>
        <?php
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        ?>
        <?php if ($result->num_rows > 0): ?>
            <?php $row = $result->fetch_assoc(); ?>
            <div class="product">
                <div class="product-image-container">
                    <?php if (!empty($row['image'])): ?>
                        <img src="./uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>">
                    <?php else: ?>
                        <img src="placeholder.png" alt="No image">
                    <?php endif; ?>
                </div>

                <div class="product-info">
                    <h2><?php echo htmlspecialchars($row["product_name"]); ?></h2>
                    <p><?php echo htmlspecialchars($row["description"]); ?></p>
                    <div class="price"><?php echo number_format($row["price"], 0, ',', '.') . ' VNĐ'; ?></div>
                    <form method="GET" action="cart.php">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="add_id" value="<?php echo $row['id']; ?>">
                        <div class="quantity-container">
                            <input type="number" name="quantity" value="1" min="1">
                            <span>Còn lại: <?php echo $row["total"]; ?> sản phẩm</span>
                        </div>
                        <button class="buy-btn" type="submit">Mua ngay</button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <p>Không tìm thấy sản phẩm với ID này</p>
        <?php endif; ?>
        <?php $stmt->close(); ?>
    <?php else: ?>
        <p>Không có ID sản phẩm</p>
    <?php endif; ?>
</main>
    <?php require('./include/footer.php'); ?>
</body>
</html>
