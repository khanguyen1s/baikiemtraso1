<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng kí</title>
    <link rel="stylesheet" href="./css/styleWeb.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
</head>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$conn = new mysqli("localhost", "banhang", "12345", "thuong_mai_dien_tu");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
$errors = [];
if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));
    $repeat_password = htmlspecialchars(trim($_POST['repeat-password']));
    if (empty($username)) {
        $errors['username'] = 'Vui lòng nhập họ tên';
    } else {
        $_SESSION["username"] = $username;
    }
    if (empty($email)) {
        $errors['email'] = 'Vui lòng nhập email.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email không hợp lệ';
    } else {
        $stmt = $conn->prepare("SELECT id FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors['email'] = 'Email đã được sử dụng';
        } else {
            $_SESSION["email"] = $email;
        }
        $stmt->close();
    }
    if (empty($password)) {
        $errors['password'] = 'Vui lòng nhập mật khẩu';
    } elseif (strlen($password) < 6) {
        $errors['password'] = 'Mật khẩu phải có ít nhất 6 ký tự';
    } elseif ($password !== $repeat_password) {
        $errors['repeat-password'] = 'Mật khẩu xác nhận không đúng';
    } else {
        $_SESSION["password"] = $password;
    }
    if (count($errors) < 1) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO user (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;
            $_SESSION["user_id"] = $user_id;
            header('Location: dashboard.php');
            exit();
        }
        $stmt->close();
    }
}
?>
<body>
    <?php require('./include/header.php') ?>
<main>
    <div class="container"> 
        <div class="form-container">
            <h2>Đăng Ký</h2>
            <p>Hãy điền thông tin để tạo tài khoản</p>
            <form method="POST">
                <input type="text" name="username" placeholder="Họ tên" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ($_SESSION['username'] ?? '') ?>">
                <span class="error"><?php echo $errors['username'] ?? ''; ?></span>
                <input type="email" name="email" placeholder="Email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ($_SESSION['email'] ?? '') ?>">
                <span class="error"><?php echo $errors['email'] ?? ''; ?></span>
                <input type="password" name="password" placeholder="Mật khẩu" value="<?php echo isset($_POST['password']) ? $_POST['password'] : ($_SESSION['password'] ?? '') ?>">
                <span class="error"><?php echo $errors['password'] ?? ''; ?></span>
                <input type="password" name="repeat-password" placeholder="Xác nhận mật khẩu" value="<?php echo isset($_POST['repeat-password']) ? $_POST['repeat-password'] : '' ?>">
                <span class="error"><?php echo $errors['repeat-password'] ?? ''; ?></span>
                <button type="submit" class="btn">Đăng ký</button>
            </form>
            <a href="login.php" class="login-link">Đã có tài khoản?</a>
        </div>
    </div>
</main>
    <?php require('./include/footer.php') ?>
</body>
</html>
