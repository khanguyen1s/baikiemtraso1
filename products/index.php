<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Danh sách sản phẩm</title>
  <link rel="stylesheet" href="../cssweb/styleWeb.css">
  <link rel="stylesheet" href="../cssweb/header.css">
  <link rel="stylesheet" href="../cssweb/footer.css">
</head>
<?php
  $productsFile = 'products.json';
  if (file_exists($productsFile)) {
      $jsonData = file_get_contents($productsFile);
      $products = json_decode($jsonData, true);
  } else {
      $products = array();
  }
  if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
      $id = $_GET['id'];
      foreach ($products as $key => $product) {
          if ($product['id'] == $id) {
              unset($products[$key]);
              $products = array_values($products);
              file_put_contents($productsFile, json_encode($products));
              break;
          }
      }
      header("Location: index.php");
      exit();
}
?>
<body>
  <?php include '../include/header.php'; ?>
  <div class="containert">
    <h1>Danh sách sản phẩm</h1>
    <table cellpadding="10">
      <thead>
        <tr>
          <th>ID</th>
          <th>Tên sản phẩm</th>
          <th>Giá</th>
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
              <td><?= htmlspecialchars($product['name']) ?></td>
              <td><?= htmlspecialchars($product['price']) ?></td>
              <td><?= htmlspecialchars($product['description']) ?></td>
              <td>
                <?php if (!empty($product['image'])): ?>
                  <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" width="100">
                <?php endif; ?>
              </td>
              <td>
                <a href="update.php?id=<?= htmlspecialchars($product['id']) ?>">Cập nhật</a> |
                <a href="index.php?action=delete&id=<?= htmlspecialchars($product['id']) ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?');">Xóa</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="6">Chưa có sản phẩm nào</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
    <a href="create.php"><button class="add-product">Thêm Sản Phẩm</button></a>
  </div>
  <?php include '../include/footer.php'; ?>
</body>
</html>
