<!DOCTYPE html>
<html lang="vi">
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
session_start();
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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {////xlusbid
    $sql_delete = "DELETE FROM user WHERE id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $user_id);
    if ($stmt_delete->execute()) {
        session_destroy();
        header("Location: login.php?msg=deleted");
        exit();
    } else {
        $error_msg = "Lỗi khi xóa tài khoản: " . $conn->error;
    }
    $stmt_delete->close();
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {//cstt
    $new_name = trim($_POST['name']);
    $new_email = trim($_POST['email']);
    if (!empty($new_name) && !empty($new_email)) {
        $sql_update = "UPDATE user SET username = ?, email = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ssi", $new_name, $new_email, $user_id);
        if ($stmt_update->execute()) {
            $_SESSION['username'] = $new_name;
            $_SESSION['email'] = $new_email;
            $success_msg = "Cập nhật thông tin thành công!";
        } else {
            $error_msg = "Lỗi khi cập nhật: " . $conn->error;
        }
        $stmt_update->close();
    } else {
        $error_msg = "Vui lòng điền đầy đủ tên và email.";
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
            <div class="login-box" >
                <h2>Cập nhật thông tin</h2>
                <form method="POST" action="profile.php">
                    <label for="name">Tên</label>
                    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" required>
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" required>

                    <button type="submit" name="update" class="btn">Cập nhật</button>
                </form>

            </div>
        </div>
    </div>
</main>
<?php require('./include/footer.php'); ?>
</body>
</html>
