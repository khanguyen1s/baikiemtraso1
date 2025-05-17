<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật sản phẩm</title>
    <link rel="stylesheet" href="../css/styleWeb.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
</head>
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
if (!isset($_GET['id'])) {
    header("Location: list_products.php");
    exit();
}
$id = $conn->real_escape_string($_GET['id']);
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Sản phẩm không tồn tại";
    exit();
}
$productToUpdate = $result->fetch_assoc();
$stmt->close();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productName = $conn->real_escape_string($_POST['product_name']);
    $productPrice = (int)$_POST['product_price'];
    $productDescription = $conn->real_escape_string($_POST['product_description']);
    $productTotal = (int)$_POST['total'];
    $productStatus = isset($_POST['status']) ? 1 : 0;
    $imagePath = $productToUpdate['image'];
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $targetDir = "../uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        $imageName = time() . '_' . basename($_FILES["product_image"]["name"]);
        $targetFile = $targetDir . $imageName;
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $targetFile)) {
            if (!empty($productToUpdate['image']) && file_exists($targetDir . $productToUpdate['image'])) {
                unlink($targetDir . $productToUpdate['image']);
            }
            $imagePath = $imageName;
        }
    }
    $updateSql = "UPDATE products SET 
                 product_name = ?, 
                 price = ?, 
                 description = ?, 
                 total = ?, 
                 image = ?, 
                 status = ? 
                 WHERE id = ?";
    
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("sisisii", $productName, $productPrice, $productDescription, $productTotal, $imagePath, $productStatus, $id);
    
    if ($stmt->execute()) {
        header("Location: index.php?update=success");
        exit();
    } else {
        echo "Lỗi khi cập nhật: " . $conn->error;
    }
    $stmt->close();
}
$conn->close();
?>
<body>
    <?php include '../include/header.php'; ?>
    <div class="contact-container">
        <h2>Cập nhật sản phẩm</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="product_name">Tên sản phẩm:</label>
            <input type="text" name="product_name" id="product_name" 
                   value="<?= htmlspecialchars($productToUpdate['product_name']) ?>" required><br><br>
            
            <label for="product_price">Giá (VNĐ):</label>
            <input type="number" name="product_price" id="product_price" min="0"
                   value="<?= htmlspecialchars($productToUpdate['price']) ?>" required><br><br>
            
            <label for="product_description">Mô tả:</label>
            <textarea name="product_description" id="product_description"><?= htmlspecialchars($productToUpdate['description']) ?></textarea><br><br>
            
            <label for="total">Số lượng:</label>
            <input type="number" name="total" id="total" min="0"
                   value="<?= htmlspecialchars($productToUpdate['total']) ?>" required><br><br>
            
            <label for="product_image">Ảnh sản phẩm:</label>
            <input type="file" name="product_image" id="product_image"><br><br>
            
            <?php if (!empty($productToUpdate['image'])): ?>
                <img src="../uploads/<?= htmlspecialchars($productToUpdate['image']) ?>" 
                     alt="<?= htmlspecialchars($productToUpdate['product_name']) ?>" 
                     width="100"><br><br>
            <?php endif; ?>
            
            <label for="status">Trạng thái:</label>
            <input type="checkbox" name="status" id="status" 
                   <?= $productToUpdate['status'] == 1 ? 'checked' : '' ?> value="1"> Hiển thị<br><br>
            
            <button type="submit">Cập nhật sản phẩm</button>
        </form>
    </div>
    <?php include '../include/footer.php'; ?>
</body>
</html>