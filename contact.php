<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên hệ</title>
    <link rel="stylesheet" href="./css/styleWeb.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
</head>
<?php
$servername = "localhost";
$username = "banhang";
$password = "12345";
$dbname = "thuong_mai_dien_tu";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
$errors = [];
$username = $email = $subject = $message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(trim($_POST['username'] ?? ""));
    $email = htmlspecialchars(trim($_POST['email'] ?? ""));
    $subject = htmlspecialchars(trim($_POST['subject'] ?? ""));
    $message = htmlspecialchars(trim($_POST['message'] ?? ""));
    if (empty($username)) {
        $errors['username'] = 'Vui lòng nhập họ tên.';
    }
    if (empty($email)) {
        $errors['email'] = 'Vui lòng nhập email.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email không hợp lệ.';
    }
    if (empty($errors)) {
        $username = $email = $subject = $message = "";
        $success = "Gửi liên hệ thành công!";
    }
}
?>
<body>
    <?php require('./include/header.php') ?>
<main>
    <div class="contact-container">
        <h2>Liên Hệ</h2>
        <?php if (!empty($success)) : ?>
            <p class="success"><?= $success ?></p>
        <?php endif; ?>
        <form action="contact.php" method="post">
            <label for="username">Họ tên:</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($username) ?>">
            <?php if (!empty($errors['username'])): ?>
                <p class="error"><?= $errors['username'] ?></p>
            <?php endif; ?>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>">
            <?php if (!empty($errors['email'])): ?>
                <p class="error"><?= $errors['email'] ?></p>
            <?php endif; ?>
            <label for="subject">Chủ đề:</label>
            <input type="text" id="subject" name="subject" value="<?= htmlspecialchars($subject) ?>">
            <label for="message">Nội dung:</label>
            <textarea id="message" name="message" rows="5"><?= htmlspecialchars($message) ?></textarea>
            <button type="submit">Gửi</button>
        </form>
    </div>
</main>
    <?php require('./include/footer.php') ?>
</body>
</html>
