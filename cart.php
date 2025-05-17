<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="./css/styleWeb.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <style>
        .cart-container {
            max-width: 900px;
            margin: 20px auto 40px auto;
            background: white;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            font-family: Arial, sans-serif;
            color: #333;
        }
        h2 {
            color: #7B002C;
            margin-bottom: 15px;
            font-weight: 700;
        }
        h3, h4 {
            color: #5E3C2B;
            margin: 8px 0;
        }
        hr {
            border: none;
            border-top: 1px solid #ddd;
            margin: 30px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 12px 15px;
            text-align: center;
            font-size: 15px;
        }
        table th {
            background-color: #5E3C2B;
            color: white;
            font-weight: 600;
        }
        a {
            color: #c0392b;
            font-weight: bold;
            text-decoration: none;
        }
        a:hover {
            color: #e74c3c;
        }
        button {
            background-color: #C9A227;
            border: none;
            padding: 12px 25px;
            color: white;
            font-weight: 700;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            margin: 8px 0;
            box-shadow: 0 3px 6px rgba(0,0,0,0.15);
            transition: 0.3s ease;
        }
        button:hover {
            background-color: #9b7d1d;
        }
        .btn-action {
            color: white;
            display: inline-block;
            padding: 12px 25px;
            font-weight: 700;
            font-size: 16px;
            border-radius: 6px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.15);
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: 0.3s ease;
            margin-right: 10px;
        }
        .btn-back {
            background-color: var(--button-background);
        }
        .btn-back:hover {
            background-color: var(--button-hover);
        }
    </style>
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

$user_id = $_SESSION['user_id'] ?? 0;
if ($user_id === 0) die("Bạn chưa đăng nhập.");

$stmt_user = $conn->prepare("SELECT username, email FROM user WHERE id = ?");
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$user_info = $stmt_user->get_result()->fetch_assoc();

$user_name = $user_info['username'] ?? '';
$user_email = $user_info['email'] ?? '';

$get_guest = $conn->prepare("SELECT id, full_name, number_phone, address FROM guest WHERE user_id = ?");
$get_guest->bind_param("i", $user_id);
$get_guest->execute();
$guest = $get_guest->get_result()->fetch_assoc();

$guest_id = $guest['id'] ?? 0;
$guest_full_name = $guest['full_name'] ?? '';
$guest_phone = $guest['number_phone'] ?? '';
$guest_address = $guest['address'] ?? '';

if (isset($_GET['add_id'])) {
    $add_id = intval($_GET['add_id']);
    $add_quantity = max(1, intval($_GET['quantity'] ?? 1));
    $found = false;

    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $add_id) {
            $item['quantity'] += $add_quantity;
            $found = true;
            break;
        }
    }
    if (!$found) {
        $_SESSION['cart'][] = ['product_id' => $add_id, 'quantity' => $add_quantity];
    }

    header("Location: cart.php");
    exit;
}

if (isset($_GET['remove_id'])) {
    $_SESSION['cart'] = array_filter($_SESSION['cart'], fn($item) => $item['product_id'] != $_GET['remove_id']);
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    header("Location: cart.php");
    exit;
}

if (isset($_POST['mua_hang'])) {
    if ($guest_id === 0) {
        echo "<h4>Bạn chưa có thông tin khách hàng, vui lòng cập nhật.</h4>";
    } elseif (!empty($_SESSION['cart'])) {
        $create_order = $conn->prepare("INSERT INTO manage_order (guest_id, status) VALUES (?, 'Chờ xác nhận')");
        $create_order->bind_param("i", $guest_id);
        $create_order->execute();
        $order_id = $conn->insert_id;

        $insert = $conn->prepare("INSERT INTO order_details (product_id, quantity, selling_price, order_id) VALUES (?, ?, ?, ?)");

        foreach ($_SESSION['cart'] as $item) {
            $product_id = $item['product_id'];
            $quantity = $item['quantity'];

            $price_stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
            $price_stmt->bind_param("i", $product_id);
            $price_stmt->execute();
            $price = $price_stmt->get_result()->fetch_assoc()['price'];

            $insert->bind_param("iiii", $product_id, $quantity, $price, $order_id);
            $insert->execute();
        }

        unset($_SESSION['cart']);
        echo "<h4>Đặt hàng thành công! Đơn hàng số #$order_id</h4>";
    }
}

if (isset($_GET['cancel_order'])) {
    $order_id = intval($_GET['cancel_order']);

    $del1 = $conn->prepare("DELETE FROM order_details WHERE order_id = ?");
    $del1->bind_param("i", $order_id);
    $del1->execute();

    $del2 = $conn->prepare("DELETE FROM manage_order WHERE id = ?");
    $del2->bind_param("i", $order_id);
    $del2->execute();

    echo "<h4>Đã hủy đơn hàng #$order_id</h4>";
    header("Refresh:2; url=cart.php");
    exit;
}

if (isset($_GET['cancel_order_detail'])) {
    $order_detail_id = intval($_GET['cancel_order_detail']);
    $del = $conn->prepare("DELETE FROM order_details WHERE id = ?");
    $del->bind_param("i", $order_detail_id);
    $del->execute();

    echo "<h4>Đã hủy sản phẩm trong đơn hàng</h4>";
    header("Refresh:2; url=cart.php?order_id=" . intval($_GET['order_id'] ?? 0));
    exit;
}
?>
    <?php require('./include/header.php'); ?>
<body>
<div class="cart-container">
    <h2>Thông tin khách hàng</h2>
    <h4><strong>Họ tên:</strong> <?= htmlspecialchars($guest_full_name ?: $user_name) ?></h4>
    <h4><strong>Email:</strong> <?= htmlspecialchars($user_email) ?></h4>
    <h4><strong>Điện thoại:</strong> <?= htmlspecialchars($guest_phone) ?></h4>
    <h4><strong>Địa chỉ:</strong> <?= htmlspecialchars($guest_address) ?></h4>
    <h5><a href="profile.php" class="btn-action btn-back">Chỉnh sửa</a></h5>
    <hr>
    <h2>Giỏ hàng</h2>
    <?php if (empty($_SESSION['cart'])): ?>
        <h4>Chưa có sản phẩm nào trong giỏ hàng.</h4>
    <?php else: ?>
        <table>
            <tr>
                <th>Tên</th><th>Giá</th><th>Số lượng</th><th>Thành tiền</th><th>Xóa</th>
            </tr>
            <?php
            $tong = 0;
            foreach ($_SESSION['cart'] as $item):
                $stmt = $conn->prepare("SELECT product_name, price FROM products WHERE id = ?");
                $stmt->bind_param("i", $item['product_id']);
                $stmt->execute();
                $product = $stmt->get_result()->fetch_assoc();

                $subtotal = $product['price'] * $item['quantity'];
                $tong += $subtotal;
            ?>
            <tr>
                <td><?= htmlspecialchars($product['product_name']) ?></td>
                <td><?= number_format($product['price']) ?> đ</td>
                <td><?= $item['quantity'] ?></td>
                <td><?= number_format($subtotal) ?> đ</td>
                <td><a href="?remove_id=<?= $item['product_id'] ?>" onclick="return confirm('Xóa sản phẩm này?')">❌</a></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3"><strong>Tổng:</strong></td>
                <td colspan="2"><?= number_format($tong) ?> đ</td>
            </tr>
        </table>
        <form method="post">
            <button type="submit" name="mua_hang">Mua hàng</button>
        </form>
        <h5><a href="store.php" class="btn-action btn-back">Quay lại mua hàng</a></h5>
    <?php endif; ?>
    <hr>
    <h2>Đơn hàng đã đặt</h2>
    <?php
        $orders_stmt = $conn->prepare("SELECT id, created_at, status FROM manage_order WHERE guest_id = ? ORDER BY created_at DESC");
        $orders_stmt->bind_param("i", $guest_id);
        $orders_stmt->execute();
        $orders = $orders_stmt->get_result();
    if ($orders->num_rows === 0): ?>
        <h4>Chưa có đơn hàng nào.</h4>
    <?php else: ?>
        <?php while ($order = $orders->fetch_assoc()): ?>
            <div style="border: 1px solid #ccc; border-radius: 8px; padding: 15px; margin-bottom: 25px;">
                <h3>Đơn hàng #<?= $order['id'] ?> - <?= date("d/m/Y H:i", strtotime($order['created_at'])) ?></h3>
                <h4>Trạng thái: <span style="color:<?= $order['status'] === 'Chờ xác nhận' ? 'orange' : 'green' ?>; font-weight: bold;">
                    <?= htmlspecialchars($order['status']) ?>
                </span></h4>
                <table>
                    <tr>
                        <th>Tên SP</th><th>Giá bán</th><th>Số lượng</th><th>Thành tiền</th><th>Hủy</th>
                    </tr>
                    <?php
                    $details_stmt = $conn->prepare("SELECT od.id, p.product_name, od.quantity, od.selling_price 
                                                    FROM order_details od 
                                                    JOIN products p ON od.product_id = p.id 
                                                    WHERE od.order_id = ?");
                    $details_stmt->bind_param("i", $order['id']);
                    $details_stmt->execute();
                    $details = $details_stmt->get_result();
                    $total = 0;
                    while ($d = $details->fetch_assoc()):
                        $line_total = $d['selling_price'] * $d['quantity'];
                        $total += $line_total;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($d['product_name']) ?></td>
                        <td><?= number_format($d['selling_price']) ?> đ</td>
                        <td><?= $d['quantity'] ?></td>
                        <td><?= number_format($line_total) ?> đ</td>
                        <td>
                            <a href="?cancel_order_detail=<?= $d['id'] ?>&order_id=<?= $order['id'] ?>" 
                               onclick="return confirm('Hủy sản phẩm này khỏi đơn hàng?')">Hủy</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <tr>
                        <td colspan="3"><strong>Tổng đơn:</strong></td>
                        <td colspan="2"><?= number_format($total) ?> đ</td>
                    </tr>
                </table>
                <form method="get" onsubmit="return confirm('Bạn chắc chắn muốn hủy toàn bộ đơn hàng này?');">
                    <input type="hidden" name="cancel_order" value="<?= $order['id'] ?>">
                    <button style="margin-top: 10px;">Hủy toàn bộ đơn</button>
                </form>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>
</body>
    <?php require('./include/footer.php'); ?>
</html>
