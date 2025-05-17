<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý danh mục</title>
    <link rel="stylesheet" href="./css/styleWeb.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        form.inline {
            display: inline;
        }
    </style>
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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $name = $conn->real_escape_string($_POST['category_name']);
    if (!empty($name)) {
        $conn->query("INSERT INTO category (danh_muc) VALUES ('$name')");
    }
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM category WHERE id = $id");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id = (int)$_POST['category_id'];
    $name = $conn->real_escape_string($_POST['category_name']);
    if (!empty($name)) {
        $conn->query("UPDATE category SET danh_muc = '$name' WHERE id = $id");
    }
}
$result = $conn->query("SELECT * FROM category ORDER BY id DESC");
?>
<body>
    <?php include './include/header.php';?>
<div class="contact-container">
    <h2>Quản lý danh mục</h2>
    <form method="POST">
        <label for="category_name">Tên danh mục mới:</label>
        <input type="text" name="category_name" id="category_name" required>
        <button type="submit" name="add">Thêm</button>
    </form>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên danh mục</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td>
                    <form method="POST" class="inline">
                        <input type="hidden" name="category_id" value="<?= $row['id'] ?>">
                        <input type="text" name="category_name" value="<?= htmlspecialchars($row['danh_muc']) ?>" required>
                        <button type="submit" name="update">Cập nhật</button>
                    </form>
                </td>
                <td>
                    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Bạn chắc chắn muốn xoá?')">Xoá</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
    <?php include('./include/footer.php'); ?>
</body>
</html>
<?php $conn->close(); ?>
