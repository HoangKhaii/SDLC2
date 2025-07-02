<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "asm2";
$connect = mysqli_connect($servername, $username, $password, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $name = trim($_POST["name"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $phone = trim($_POST["phone"] ?? '');
    $address = trim($_POST["address"] ?? '');
    $username = trim($_POST["username"] ?? '');
    $pass = $_POST["password"] ?? '';
    $role = 2; // Luôn là User

    // Validate dữ liệu cơ bản
    if ($name === '' || $email === '' || $phone === '' || $address === '' || $username === '' || $pass === '') {
        echo "<script>alert('Vui lòng nhập đầy đủ thông tin!'); window.history.back();</script>";
        exit();
    }

    // Kiểm tra email hoặc username đã tồn tại chưa
    $sql = "SELECT * FROM users WHERE Email=? OR Username=?";
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $email, $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Email hoặc Username đã tồn tại!'); window.history.back();</script>";
        exit();
    }

    // Mã hóa mật khẩu
    $hash = password_hash($pass, PASSWORD_DEFAULT);

    // Thêm user mới với thông tin đầy đủ, dùng prepared statement để tránh SQL injection
    $sql = "INSERT INTO users (RoleID, Name, Email, Phone, Address, Username, Password) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, "issssss", $role, $name, $email, $phone, $address, $username, $hash);
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Đăng ký thành công! Vui lòng đăng nhập.'); window.location.href = 'login.html';</script>";
    } else {
        echo "<script>alert('Đăng ký thất bại!'); window.history.back();</script>";
    }
}
?>