<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm sản phẩm</title>
    <link rel="stylesheet" href="../cssweb/styleWeb.css">
    <link rel="stylesheet" href="../cssweb/header.css">
    <link rel="stylesheet" href="../cssweb/footer.css">
</head>
<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $productName        = $_POST['product_name'];
        $productPrice       = $_POST['product_price'];
        $productDescription = $_POST['product_description'];
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $targetDir = "products/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $targetFile = $targetDir . basename($_FILES["product_image"]["name"]);
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $targetFile)) {
            $imagePath = $targetFile;
        } else {
            $error = "Có lỗi khi tải ảnh lên";
        }
    } else {
        $imagePath = "";
    }
    $productData = array(
        'id'          => time(),  
        'name'        => $productName,
        'price'       => $productPrice,
        'description' => $productDescription,
        'image'       => $imagePath
    );
    $productsFile = 'products.json';
    if (file_exists($productsFile)) {
        $jsonData = file_get_contents($productsFile);
        $products = json_decode($jsonData, true);
    } else {
        $products = array();
    }
    $products[] = $productData;
    file_put_contents($productsFile, json_encode($products));
    header("Location: index.php");
    exit();
}
?>
<body>
    <?php include '../include/header.php'; ?>
    <div class="contact-container">
        <h2>Thêm sản phẩm</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="product_name">Tên sản phẩm:</label>
            <input type="text" name="product_name" id="product_name" required><br><br>
            
            <label for="product_price">Giá:</label>
            <input type="number" name="product_price" id="product_price" required><br><br>
            
            <label for="product_description">Mô tả:</label>
            <textarea name="product_description" id="product_description"></textarea><br><br>
            
            <label for="product_image">Ảnh sản phẩm:</label>
            <input type="file" name="product_image" id="product_image"><br><br>
            
            <button type="submit">Thêm sản phẩm</button>
        </form>
    </div>
    <?php include '../include/footer.php'; ?>
</body>
</html>
