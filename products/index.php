<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Danh sách sản phẩm</title>
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
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    $query = "SELECT image FROM products WHERE id = '$id'";
    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image = $row['image'];
        if (!empty($image)) {
            $image_path = "../uploads/" . $image;
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
    }
    $sql = "DELETE FROM products WHERE id = '$id'";
    if ($conn->query($sql)) {
        header("Location: index.php?deleted=true");
        exit();
    }
}
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
$products = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
$total_products = count($products);
$conn->close();
?>
<body>
  <?php include '../include/header.php'; ?>
  <main>
    <div class="containert">
      <h1>Danh sách sản phẩm</h1>
      <div class="product-count">Tổng số sản phẩm: <?php echo $total_products; ?></div>
      <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success">Đã xóa sản phẩm thành công!</div>
      <?php endif; ?>
      <table cellpadding="10">
        <thead>
          <tr>
            <th>ID</th>
            <th>Tên sản phẩm</th>
            <th>Giá</th>
            <th>Số lượng</th>
            <th>Mô tả</th>
            <th>Ảnh</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
              <tr>
                <td><?= htmlspecialchars($product['id']) ?></td>
                <td><?= htmlspecialchars($product['product_name']) ?></td>
                <td><?= number_format($product['price'], 0, ',', '.') ?>₫</td>
                <td><?= htmlspecialchars($product['total']) ?></td>
                <td><?= htmlspecialchars($product['description']) ?></td>
                <td>
                  <?php if (!empty($product['image'])): ?>
                    <img src="../uploads/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>" width="100">
                  <?php endif; ?>
                </td>
                <td>
                  <a href="update.php?id=<?= $product['id'] ?>">Sửa</a> |
                  <a href="index.php?action=delete&id=<?= $product['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7">Chưa có sản phẩm nào</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
      <a href="create.php"><button class="add-product">Thêm Sản Phẩm</button></a>
    </div>
  </main>
  <?php include '../include/footer.php'; ?>
</body>
</html>
