<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
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
    $error = "";
    $email = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);

        if (!empty($email) && !empty($password)) {
            $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user["password"])) {
                    $_SESSION["user_id"] = $user["id"];
                    $_SESSION["username"] = $user["username"];
                    header("Location: dashboard.php");
                    exit;
                } else {
                    $error = "Sai mật khẩu!";
                }
            } else {
                $error = "Sai email!";
            }

            $stmt->close();
        } else {
            $error = "Vui lòng nhập đầy đủ thông tin.";
        }
    }
?>
<body>
    <?php require('./include/header.php'); ?>
<main>
    <div class="container">
        <div class="left">
            <h1>RƯỢU NGON THÀNH TỊNH</h1>
        </div>
        <div class="right">
            <form class="login-box" method="POST" action="">
                <?php if (!empty($error)): ?>
                    <div class="error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>" required>
                <input type="password" name="password" placeholder="Mật khẩu" required>
                <input type="submit" value="Đăng nhập">
                <a href="./reset-password.php">Quên mật khẩu?</a>
                <button class="create-account">
                    <a href="register.php">Tạo tài khoản mới</a>
                </button>
            </form>
        </div>
    </div>
</main>
<?php require('./include/footer.php'); ?>
</body>
</html>
