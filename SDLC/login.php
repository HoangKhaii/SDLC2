<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "asm2";
$connect = mysqli_connect($servername, $username, $password, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $user = $_POST["login-username"] ?? '';
    $pass = $_POST["login-password"] ?? '';

    // Cho phép đăng nhập bằng username hoặc email
    $sql = "SELECT * FROM users WHERE Username='$user' OR Email='$user'";
    $result = mysqli_query($connect, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($pass, $row['Password'])) {
            $_SESSION['user'] = $row['Username'];
            $_SESSION['role'] = $row['RoleID'];
            echo "<script>
                alert('Đăng nhập thành công!');
                window.location.href = 'index.html';
            </script>";
            exit();
        } else {
            echo "<script>alert('Sai mật khẩu!'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Không tìm thấy tài khoản!'); window.history.back();</script>";
    }
}
?>