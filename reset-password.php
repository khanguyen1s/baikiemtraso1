<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu</title>
    <link rel="stylesheet" href="./css/styleWeb.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
</head>
<?php
    if (session_status() == PHP_SESSION_NONE) {
      session_start();}
      $servername = "localhost";
      $username = "banhang";
      $password = "12345";
      $dbname = "thuong_mai_dien_tu";
      $conn = new mysqli($servername, $username, $password, $dbname);
      if ($conn->connect_error) {
          die("Kết nối thất bại: " . $conn->connect_error);
      }
?>
<?php
$errors = [];
if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $email = htmlspecialchars(trim($_POST['email']));
    if (empty($email)) {
        $errors['email'] = 'Vui lòng nhập email.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email không hợp lệ.';
    }
    if (count($errors) < 1) {
        header('Location: ./login.php');
        exit();
    }
}
?>
  <?php require('./include/header.php')?>
<main>
    <div class="reset-container">
        <h2>Reset Mật Khẩu</h2>
        <p>Nhập email tài khoản của bạn để đặt lại mật khẩu.</p>
        <form action="reset-password.php" method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <button type="submit">Gửi Yêu Cầu</button>
        </form>
</div>
</main>
  <?php require('./include/footer.php')?>
</html>
