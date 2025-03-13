<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="register.css">
</head>
<?php
  $errors = [];
  if ($_SERVER["REQUEST_METHOD"] == 'POST'){
    if (empty(htmlspecialchars(trim($_POST['username'])))) {
      $errors['username'] = 'Vui lòng nhập họ tên';
    }
    else{
      setcookie("username", $_POST['username'] , time() + (86400 * 30), "/");
    }
    if (empty(htmlspecialchars(trim($_POST['email'])))) {
      $errors['email'] = 'Vui lòng nhập email.';
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = 'Email không hợp lệ';
    }
    else{
      setcookie("email", $_POST['email'] , time() + (86400 * 30), "/");
    }
    if (empty(htmlspecialchars(trim($_POST['password'])))) {
      $errors['password'] = 'Vui lòng nhập mật khẩu';
    } elseif (strlen($_POST['password']) < 6) {
      $errors['password'] = 'Mật khẩu phải có ít nhất 6 ký tự';
    }
    elseif (htmlspecialchars(trim($_POST['password']!== $_POST['repeat-password']))) {
      $errors['repeat-password'] = 'Mật khẩu xác nhận không đúng';
    }
    else{
      setcookie("password", $_POST['password'] , time() + (86400 * 30), "/");
    }
    if (count($errors)<1){
      echo "Đăng ký thành công";
      $_POST['username'] = $_POST['email'] = $_POST['password'] = $_POST['repeat-password'] = '';

      header('location:login.php');
    }
  }
?>
<body>
<?php require('./includes/header.php')?>
<main>
  <div class="container"> 
      <div class="form-container">
          <h2>Đăng Ký</h2>
          <p>Hãy điền thông tin để tạo tài khoản</p>
          <form method="POST">
              <input type="text" name="username" placeholder="Họ tên" value="<?php echo isset($_POST['username'])? $_POST['username'] : ''?>">
              <span class="error"><?php echo $errors['username'] ?? ''; ?></span>
              <input type="email" name="email" placeholder="Email" value="<?php echo isset($_POST['email'])? $_POST['email'] : ''?>">
              <span class="error"><?php echo $errors['email']?? ''; ?></span>
              <input type="password" name="password" placeholder="Mật khẩu" value="<?php echo isset($_POST['password'])? $_POST['password'] : ''?>">
              <span class="error"><?php echo $errors['password'] ?? ''; ?></span>
              <input type="password" name="repeat-password" placeholder="Xác nhận mật khẩu" value="<?php echo isset($_POST['repeat-password'])? $_POST['repeat-password'] : ''?>">
              <span class="error"><?php echo $errors['repeat-password'] ?? ''; ?></span>
              <button type="submit" class="btn">Đăng ký</button>
          </form>
          <a href="login.php" class="login-link">Đã có tài khoản?</a>
      </div>
  </div>
</main>
<?php require('./includes/footer.php')?>
</body>
</html>