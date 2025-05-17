<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thêm sản phẩm</title>
    <link rel="stylesheet" href="../css/styleWeb.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
</head>
<body>
<?php
$servername = "localhost";
$username = "banhang";
$password = "12345";
$dbname = "thuong_mai_dien_tu";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$categoryResult = $conn->query("SELECT id, danh_muc FROM category");
$categories = [];
if ($categoryResult && $categoryResult->num_rows > 0) {
    while ($row = $categoryResult->fetch_assoc()) {
        $categories[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $categoryId     = $_POST['category_id'];
    $productName    = $_POST['product_name'];
    $description    = $_POST['product_description'];
    $price          = $_POST['price'];
    $total          = $_POST['total'];
    $status         = isset($_POST['status']) ? 1 : 0;
    $imagePath = "";
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $uploadDir = "../uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $imageName = time() . "_" . basename($_FILES["product_image"]["name"]);
        $targetFile = $uploadDir . $imageName;
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $targetFile)) {
            $imagePath = $imageName;
        }
    }
    $stmt = $conn->prepare("INSERT INTO products (category_id, product_name, description, price, total, image, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issdisi", $categoryId, $productName, $description, $price, $total, $imagePath, $status);

    if ($stmt->execute()) {
        echo "<div class='success-message'>Thêm sản phẩm thành công!</div>";
    } else {
        echo "<div class='error-message'>Lỗi: " . $stmt->error . "</div>";
    }
    $stmt->close();
}
?>
<?php include '../include/header.php'; ?>
<div class="contact-container">
    <h2>Thêm sản phẩm</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="category_id">Danh mục:</label>
        <select name="category_id" id="category_id" required>
            <option value="">-- Chọn danh mục --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['danh_muc']) ?></option>
            <?php endforeach; ?>
        </select><br><br>
        <label for="product_name">Tên sản phẩm:</label>
        <input type="text" name="product_name" id="product_name" required><br><br>
        <label for="product_description">Mô tả:</label>
        <textarea name="product_description" id="product_description"></textarea><br><br>
        <label for="price">Giá sản phẩm (VNĐ):</label>
        <input type="number" name="price" id="price" min="0" step="1000" required><br><br>
        <label for="total">Số lượng:</label>
        <input type="number" name="total" id="total" min="0" required><br><br>
        <label for="product_image">Ảnh sản phẩm:</label>
        <input type="file" name="product_image" id="product_image"><br><br>
        <label for="status">Hiển thị:</label>
        <input type="checkbox" name="status" id="status" checked><br><br>
        <button type="submit">Thêm sản phẩm</button>
    </form>
</div>
<?php include '../include/footer.php'; ?>
</body>
</html>
