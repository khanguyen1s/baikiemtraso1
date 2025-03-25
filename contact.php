<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, iusernametial-scale=1.0">
    <title>Liên Hệ</title>
    <link rel="stylesheet" href="./cssweb/styleWeb.css">
</head>
<?php
$errors = [];
$username = $email = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(trim($_POST['username'] ?? ""));
    $email = htmlspecialchars(trim($_POST['email'] ?? ""));
    if (empty($username)) {
        $errors['username'] = 'Vui lòng nhập họ tên.';
    }
    if (empty($email)) {
        $errors['email'] = 'Vui lòng nhập email.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email không hợp lệ.';
    }
    if (empty($errors)) {
        $username = $email = "";
    }
}
?>
<body>
    <?php require('./include/header.php')?>
<main>
    <div class="contact-container">
    <h2>Liên Hệ</h2>
        <form action="contact.php" method="post">
            <label for="username">Họ tên:</label>
            <input type="username" id="username" name="username">

            <label for="email">Email:</label>
            <input type="email" id="email" name="email">

            <label for="subject">Chủ đề:</label>
            <input type="text" id="subject" name="subject">

            <label for="message">Nội dung:</label>
            <textarea id="message" name="message" rows="5"></textarea>

            <button type="submit">Gửi</button>
        </form>
    </div>
</main>
    <?php require('./include/footer.php')?>
</body>
</html>
