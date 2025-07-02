<?php
session_start();
if (!isset($_SESSION['user']) || !isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header('Location: ../index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - FoodX Delivery</title>
  <link rel="stylesheet" href="../style.css">
  <style>
    .admin-nav { max-width: 600px; margin: 60px auto; background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); padding: 40px; text-align: center; }
    .admin-nav h2 { color: #00b14f; margin-bottom: 30px; }
    .admin-links { display: flex; flex-direction: column; gap: 22px; }
    .admin-links a { display: block; background: #00b14f; color: #fff; padding: 18px; border-radius: 8px; font-size: 20px; font-weight: 600; text-decoration: none; transition: background 0.2s; }
    .admin-links a:hover { background: #008f3a; }
  </style>
</head>
<body>
  <header>
    <div class="container header-flex">
      <img src="../logo-grabfood2.svg" alt="Logo" class="logo">
      <nav>
        <ul>
          <li><a href="../index.php">Home</a></li>
          <li><span>Welcome, <b><?php echo htmlspecialchars($_SESSION['user']); ?></b> (Admin)</span></li>
          <li><a href="../logout.php">Log out</a></li>
        </ul>
      </nav>
    </div>
  </header>
  <main>
    <div class="admin-nav">
      <h2>Admin Dashboard</h2>
      <div class="admin-links">
        <a href="manage_dishes.php">Quản lý món ăn</a>
        <a href="manage_orders.php">Quản lý đơn hàng</a>
        <a href="manage_users.php">Quản lý người dùng</a>
      </div>
    </div>
  </main>
</body>
</html> 