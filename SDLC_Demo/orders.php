<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "asm2";
$connect = mysqli_connect($servername, $username, $password, $dbname);

// Nếu chưa đăng nhập, chuyển hướng login
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
// Lấy userID
$user_query = mysqli_query($connect, "SELECT UserID FROM users WHERE Username='" . mysqli_real_escape_string($connect, $_SESSION['user']) . "'");
$user_row = mysqli_fetch_assoc($user_query);
$user_id = $user_row['UserID'];

// Lấy danh sách đơn hàng
$sql = "SELECT * FROM orders WHERE UserID = $user_id ORDER BY OrderDate DESC";
$result = mysqli_query($connect, $sql);
$orders = [];
while ($row = mysqli_fetch_assoc($result)) {
    $orders[] = $row;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Orders - FoodX Delivery</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .orders-container { max-width: 1000px; margin: 40px auto; background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); padding: 40px; }
    .orders-title { color: #00b14f; text-align: center; margin-bottom: 30px; font-size: 2rem; }
    .orders-table { width: 100%; border-collapse: collapse; margin: 30px 0; }
    .orders-table th, .orders-table td { padding: 14px; border-bottom: 1px solid #eee; text-align: center; }
    .orders-table th { background: #00b14f; color: #fff; }
    .orders-table tr:hover { background: #f6fff8; }
    .view-btn { background: #00b14f; color: #fff; border: none; padding: 7px 18px; border-radius: 5px; cursor: pointer; font-size: 15px; }
    .view-btn:hover { background: #008f3a; }
    .no-orders { text-align: center; color: #888; font-size: 22px; margin: 60px 0; }
    @media (max-width: 700px) {
      .orders-container { padding: 10px; }
      .orders-table th, .orders-table td { padding: 7px; font-size: 14px; }
      .orders-title { font-size: 1.2rem; }
    }
  </style>
</head>
<body>
  <header>
    <div class="container header-flex">
      <img src="logo-grabfood2.svg" alt="Logo" class="logo">
      <nav>
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="cart.php">Shopping Cart <span class="cart-count"><?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?></span></a></li>
          <li><span>Welcome, <b><?php echo htmlspecialchars($_SESSION['user']); ?></b></span></li>
          <li><a href="logout.php">Log out</a></li>
        </ul>
      </nav>
    </div>
  </header>
  <main>
    <div class="orders-container">
      <h2 class="orders-title">📦 Your Orders</h2>
      <?php if (empty($orders)): ?>
        <div class="no-orders">You have not placed any orders yet. <a href="index.php">Order now!</a></div>
      <?php else: ?>
        <table class="orders-table">
          <tr>
            <th>Order ID</th>
            <th>Date</th>
            <th>Status</th>
            <th>Delivery</th>
            <th>Total</th>
            <th>Details</th>
          </tr>
          <?php foreach ($orders as $order): ?>
          <tr>
            <td><?php echo $order['OrderID']; ?></td>
            <td><?php echo $order['OrderDate']; ?></td>
            <td><?php echo htmlspecialchars($order['Status']); ?></td>
            <td><?php echo htmlspecialchars($order['DeliveryStatus']); ?></td>
            <td><?php echo number_format($order['TotalAmount'], 0, ',', '.'); ?>đ</td>
            <td><a href="order_detail.php?id=<?php echo $order['OrderID']; ?>" class="view-btn">View</a></td>
          </tr>
          <?php endforeach; ?>
        </table>
      <?php endif; ?>
    </div>
  </main>
  <footer>
    <div class="footer-main">
      <div class="footer-cols">
        <div class="footer-col footer-col-brand">
          <img src="logo-grabfood2.svg" alt="FoodX Delivery" class="footer-logo">
          <div class="footer-slogan">Deliver delicious food to your door, fast and reputable!</div>
        </div>
        <div class="footer-col">
          <h4>About <span class="brand-green">FoodX</span></h4>
          <ul>
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">Terms of Use</a></li>
            <li><a href="#">Contact</a></li>
            <li><a href="#">Blog</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>Connect with us</h4>
          <div class="footer-socials">
            <a href="https://facebook.com/" target="_blank" title="Facebook" aria-label="Facebook">
              <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/facebook.svg" alt="Facebook" class="footer-icon">
            </a>
            <a href="https://zalo.me/" target="_blank" title="Zalo" aria-label="Zalo">
              <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/zalo.svg" alt="Zalo" class="footer-icon">
            </a>
          </div>
          <div class="footer-hotline">
            <span>Hotline:</span> <a href="tel:0123456789">0878 922005</a>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <span>&copy; 2025 FoodShop Delivery. Inspired by Group3Food</span>
      </div>
    </div>
  </footer>
</body>
</html> 