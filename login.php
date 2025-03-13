<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="login.css">
</head>
<?php
$username = isset($_COOKIE['username']) ? $_COOKIE['username'] : '';
$email = isset($_COOKIE['email']) ? $_COOKIE['email'] : '';
$password = isset($_COOKIE['password']) ? $_COOKIE['password'] : '';
  $errors = [];
  if ($_SERVER["REQUEST_METHOD"] == 'POST'){
    if (empty(htmlspecialchars($_POST['email']))== $email) {
      $errors['email'] = 'Vui lòng nhập email.';
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = 'Email không hợp lệ.';
    }elseif (empty(htmlspecialchars($_POST['password']))) {
      $errors['password'] = 'Vui lòng nhập mật khẩu.';
    } elseif (strlen($_POST['password']) == $password) {
      $errors['password'] = 'Sai mật khẩu';
    }if(count($errors)<1){
    $_POST['email'] = $_POST['password'] = '';
    header('location:#');
  }
  } 
?>
<body>
  <?php require('./includes/header.php')?>
    <main>
        <div class="container">
        <div class="left">
            <h1>ten web</h1>
            <p>noi dung</p>
        </div>
        <div class="right">
            <form class="login-box" method="POST">
            <input type="email" name="email" placeholder="Email" value="<?php echo isset($_POST['email'])? $_POST['email'] : ''?>">
            <span class="error"><?php echo $errors['email']?? ''; ?></span>
            <input type="password" name="password" placeholder="Mật khẩu" value="<?php echo isset($_POST['password'])? $_POST['password'] : ''?>">
            <span class="error"><?php echo $errors['password'] ?? ''; ?></span>
            <input type="submit" value="Đăng nhập">
            <a href="#">Quên mật khẩu?</a>
            <button class="create-account"><a href="register.php">Tạo tài khoản mới</a></button>
            </form>
        </div>
        </div>
    </main>
    <?php require('./includes/footer.php')?>
</body>
</html>
