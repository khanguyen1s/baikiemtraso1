<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="cssweb/styleWeb.css">
</head>
<?php
$email = isset($_COOKIE['email']) ? $_COOKIE['email'] : '';
$password = isset($_COOKIE['password']) ? $_COOKIE['password'] : '';
  $errors = [];
  if ($_SERVER["REQUEST_METHOD"] == 'POST'){
    if (empty(htmlspecialchars($_POST['email']))== $email) {
      $errors['email'] = 'Email không hợp lệ.';
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = 'Email không hợp lệ.';
    }elseif (empty(htmlspecialchars($_POST['password']))) {
      $errors['password'] = 'Vui lòng nhập mật khẩu.';
    } elseif (strlen($_POST['password']) == $password) {
      $errors['password'] = 'Sai mật khẩu';
    }if(count($errors)<1){
    $_POST['email'] = $_POST['password'] = '';
    header('location:./dashboard.php');
  }
  } 
?>
<body>
  <?php require('./include/header.php')?>
    <main>
        <div class="container">
        <div class="left">
            <h1>RƯỢU NGON THÀNH TỊNH</h1>
            <p>Nơi thỏa sức đam mê với các loại rượu</p>
        </div>
        <div class="right">
            <form class="login-box" method="POST">
            <input type="email" name="email" placeholder="Email" value="<?php echo isset($_POST['email'])? $_POST['email'] : ''?>">
            <span class="error"><?php echo $errors['email']?? ''; ?></span>
            <input type="password" name="password" placeholder="Mật khẩu" value="<?php echo isset($_POST['password'])? $_POST['password'] : ''?>">
            <span class="error"><?php echo $errors['password'] ?? ''; ?></span>
            <input type="submit" value="Đăng nhập">
            <a href="./reset-password.php">Quên mật khẩu?</a>
            <button class="create-account"><a href="register.php">Tạo tài khoản mới</a></button>
            </form>
        </div>
        </div>
    </main>
    <?php require('./include/footer.php')?>
</body>
</html>
