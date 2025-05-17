<?php
session_start();

$conn = new mysqli("localhost", "banhang", "12345", "thuong_mai_dien_tu");
if ($conn->connect_error) die("Kết nối thất bại: " . $conn->connect_error);

if (!isset($_SESSION['email'])) {
    die("Vui lòng đăng nhập để xem đơn hàng.");
}

$email = $conn->real_escape_string($_SESSION['email']);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['order_id'], $_POST['status'])) {
    $orderId = (int)$_POST['order_id'];
    $status = $conn->real_escape_string($_POST['status']);
    $conn->query("UPDATE manage_order SET status = '$status' WHERE id = $orderId");
    header("Location: manager-cart.php");
    exit();
}

$sql = "SELECT o.id AS order_id, o.status, g.full_name, g.number_phone, g.address, o.created_at 
        FROM manage_order o 
        JOIN guest g ON o.guest_id = g.id 
        ORDER BY o.created_at DESC";
$orders = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý đơn hàng</title>
    <link rel="stylesheet" href="./css/styleWeb.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <style>
        h2 {
            color: #7B002C;
            margin-bottom: 20px;
        }
        .order-box {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 8px;
        }
        .order-box h3 {
            margin: 0 0 10px;
            color: #5E3C2B;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table, th, td {
            border: 1px solid #bbb;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #5E3C2B;
            color: white;
        }
        select, button {
            padding: 6px 12px;
            font-size: 14px;
            margin-left: 10px;
        }
        .status-form {
            margin-top: 10px;
        }
    </style>
</head>
    <?php include './include/header.php';?>
<body>
    <div class="containert">
        <h2 class="h2">Quản lý đơn hàng</h2>
        <?php while ($order = $orders->fetch_assoc()): ?>
            <div class="order-box">
                <h3 style="color: var(--first-color);">Đơn hàng #<?= $order['order_id'] ?> - <?= $order['status'] ?>
                <h4><strong>Ngày tạo:</strong> <?= $order['created_at'] ?></h4>
                <h4><strong>Khách hàng:</strong> <?= htmlspecialchars($order['full_name']) ?> - <?= $order['number_phone'] ?> - <?php echo htmlspecialchars($_SESSION['email']); ?></h4>
                <h4><strong>Địa chỉ giao hàng:</strong> <?= htmlspecialchars($order['address']) ?></h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th class="">Tên sản phẩm</th>
                            <th class="">Số lượng</th>
                            <th class="">Đơn giá</th>
                            <th class="">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $orderId = $order['order_id'];
                        $items = $conn->query("SELECT p.product_name, od.quantity, od.selling_price 
                                               FROM order_details od
                                               JOIN products p ON od.product_id = p.id
                                               WHERE od.order_id = $orderId");
                        $total = 0;
                        while ($item = $items->fetch_assoc()):
                            $line_total = $item['quantity'] * $item['selling_price'];
                            $total += $line_total;
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($item['product_name']) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td><?= number_format($item['selling_price']) ?> đ</td>
                            <td><?= number_format($line_total) ?> đ</td>
                        </tr>
                    <?php endwhile; ?>
                        <tr>
                            <td colspan="3"><strong>Tổng đơn:</strong></td>
                            <td><strong><?= number_format($total) ?> đ</strong></td>
                        </tr>
                    </tbody>
                </table>
                <form method="POST" class="status-form">
                    <input type="hidden" name="order_id" value="<?= $orderId ?>">
                    <label for="status">Trạng thái:</label>
                    <select name="status">
                        <?php
                        $statuses = ['Chờ xác nhận', 'Đang giao', 'Đã giao', 'Đã hủy'];
                        foreach ($statuses as $s) {
                            $selected = ($order['status'] === $s) ? "selected" : "";
                            echo "<option value=\"$s\" $selected>$s</option>";
                        }
                        ?>
                    </select>
                    <button type="submit" class="button add-product">Cập nhật</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</body>
    <?php include('./include/footer.php'); ?>
</html>
<?php $conn->close(); ?>
