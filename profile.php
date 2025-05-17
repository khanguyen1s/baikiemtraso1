<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trang Cá Nhân</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/styleWeb.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
</head>
<style>
    input[type="text"], input[type="email"] {
        width: 100%;
        padding: 10px;
        margin-top: 6px;
        margin-bottom: 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        box-sizing: border-box;
    }
</style>
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

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success_msg = "";
$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $sql_delete_guest = "DELETE FROM guest WHERE user_id = ?";
    $stmt_delete_guest = $conn->prepare($sql_delete_guest);
    $stmt_delete_guest->bind_param("i", $user_id);
    $stmt_delete_guest->execute();
    $stmt_delete_guest->close();

    $sql_delete_user = "DELETE FROM user WHERE id = ?";
    $stmt_delete_user = $conn->prepare($sql_delete_user);
    $stmt_delete_user->bind_param("i", $user_id);
    if ($stmt_delete_user->execute()) {
        session_destroy();
        header("Location: login.php?msg=deleted");
        exit();
    } else {
        $error_msg = "Lỗi khi xóa tài khoản: " . $conn->error;
    }
    $stmt_delete_user->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $new_name = trim($_POST['name']);
    $fullname = trim($_POST['fullname']);
    $address = trim($_POST['address']);
    $number_phone = trim($_POST['number_phone']);

    if (!empty($new_name)) {
        $sql_update = "UPDATE user SET username = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("si", $new_name, $user_id);
        $stmt_update->execute();
        $stmt_update->close();

        $check_guest = $conn->prepare("SELECT id FROM guest WHERE user_id = ?");
        $check_guest->bind_param("i", $user_id);
        $check_guest->execute();
        $result_check = $check_guest->get_result();

        if ($result_check->num_rows > 0) {
            $sql_guest_update = "UPDATE guest SET full_name = ?, address = ?, number_phone = ? WHERE user_id = ?";
            $stmt_guest = $conn->prepare($sql_guest_update);
            $stmt_guest->bind_param("sssi", $fullname, $address, $number_phone, $user_id);
        } else {
            $sql_guest_insert = "INSERT INTO guest (user_id, full_name, address, number_phone) VALUES (?, ?, ?, ?)";
            $stmt_guest = $conn->prepare($sql_guest_insert);
            $stmt_guest->bind_param("isss", $user_id, $fullname, $address, $number_phone);
        }

        if ($stmt_guest->execute()) {
            $_SESSION['username'] = $new_name;
            $success_msg = "Cập nhật thông tin thành công!";
        } else {
            $error_msg = "Lỗi khi cập nhật thông tin khách: " . $conn->error;
        }

        $stmt_guest->close();
        $check_guest->close();
    } else {
        $error_msg = "Vui lòng điền tên.";
    }
}

$sql = "SELECT username, email FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
} else {
    echo "Không tìm thấy người dùng.";
    exit();
}
$stmt->close();

$sql_guest = "SELECT full_name, address, number_phone FROM guest WHERE user_id = ?";
$stmt_guest = $conn->prepare($sql_guest);
$stmt_guest->bind_param("i", $user_id);
$stmt_guest->execute();
$result_guest = $stmt_guest->get_result();
$guest = [];
if ($result_guest->num_rows > 0) {
    $guest = $result_guest->fetch_assoc();
}
$stmt_guest->close();
$conn->close();
?>
<body>
<?php require('./include/header.php'); ?>
<main>
    <div class="container">
        <div class="left">
            <h1>Chào, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
            <p>Email: <?php echo htmlspecialchars($_SESSION['email']); ?></p>
            <?php if (!empty($success_msg)) echo "<p style='color:green;'>$success_msg</p>"; ?>
            <?php if (!empty($error_msg)) echo "<p style='color:red;'>$error_msg</p>"; ?>
            <form method="POST" action="profile.php" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài khoản không?');">
                <button type="submit" name="delete" class="btn-delete">Xóa tài khoản</button>
            </form>
        </div>
        <div class="right">
            <div class="login-box">
                <h2>Cập nhật thông tin</h2>
                <form method="POST" action="profile.php">
                    <label for="name">Username</label>
                    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" required>
                    <label for="fullname">Họ và tên</label>
                    <input type="text" name="fullname" id="fullname" value="<?php echo isset($guest['full_name']) ? htmlspecialchars($guest['full_name']) : ''; ?>">
                    <label for="address">Địa chỉ</label>
                    <input type="text" name="address" id="address" value="<?php echo isset($guest['address']) ? htmlspecialchars($guest['address']) : ''; ?>">
                    <label for="number_phone">Số điện thoại</label>
                    <input type="text" name="number_phone" id="number_phone" value="<?php echo isset($guest['number_phone']) ? htmlspecialchars($guest['number_phone']) : ''; ?>">
                    <button type="submit" name="update" class="btn">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
</main>
<?php require('./include/footer.php'); ?>
</body>
</html>
