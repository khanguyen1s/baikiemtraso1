<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Mật Khẩu</title>
    <link rel="stylesheet" href="./cssweb/styleWeb.css">
</head>
<?php
$email = isset($_COOKIE['email']) ? $_COOKIE['email'] : '';
  $errors = [];
  if ($_SERVER["REQUEST_METHOD"] == 'POST'){
if (empty(htmlspecialchars($_POST['email']))== $email) {
      $errors['email'] = 'Vui lòng nhập email.';
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = 'Email không hợp lệ.';
    }if(count($errors)<1){
    $_POST['email'] = '';
    header('location:./login.php');
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
