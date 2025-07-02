<?php
session_start();
if (!isset($_SESSION['user']) || !isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header('Location: ../index.php');
    exit();
}
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "asm2";
$connect = mysqli_connect($servername, $username, $password, $dbname);

// Cập nhật trạng thái đơn hàng
if (isset($_POST['update_status'])) {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];
    $delivery = $_POST['delivery'];
    $stmt = mysqli_prepare($connect, "UPDATE orders SET Status=?, DeliveryStatus=? WHERE OrderID=?");
    mysqli_stmt_bind_param($stmt, "ssi", $status, $delivery, $order_id);
    mysqli_stmt_execute($stmt);
}
// Lấy danh sách đơn hàng
$sql = "SELECT o.*, u.Name as UserName FROM orders o JOIN users u ON o.UserID = u.UserID ORDER BY o.OrderDate DESC";
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
  <title>Quản lý đơn hàng - Admin</title>
  <link rel="stylesheet" href="../style.css">
  <style>
    .orders-table { width: 100%; border-collapse: collapse; margin: 30px 0; }
    .orders-table th, .orders-table td { padding: 12px; border-bottom: 1px solid #eee; text-align: center; }
    .orders-table th { background: #00b14f; color: #fff; }
    .orders-table tr:hover { background: #f6fff8; }
    .update-form select { padding: 6px 10px; border-radius: 5px; border: 1px solid #ccc; }
    .update-form button { background: #00b14f; color: #fff; border: none; padding: 7px 16px; border-radius: 5px; cursor: pointer; font-size: 15px; margin-left: 6px; }
    .update-form button:hover { background: #008f3a; }
    .view-btn { background: #00b14f; color: #fff; border: none; padding: 7px 18px; border-radius: 5px; cursor: pointer; font-size: 15px; }
    .view-btn:hover { background: #008f3a; }
  </style>
</head>
<body>
  <header>
    <div class="container header-flex">
      <img src="../logo-grabfood2.svg" alt="Logo" class="logo">
      <nav>
        <ul>
          <li><a href="index.php">Admin Home</a></li>
          <li><a href="../index.php">User Home</a></li>
          <li><span>Welcome, <b><?php echo htmlspecialchars($_SESSION['user']); ?></b> (Admin)</span></li>
          <li><a href="../logout.php">Log out</a></li>
        </ul>
      </nav>
    </div>
  </header>
  <main>
    <h2 style="margin-top:40px; text-align:center;">Quản lý đơn hàng</h2>
    <table class="orders-table">
      <tr>
        <th>Order ID</th>
        <th>User</th>
        <th>Date</th>
        <th>Status</th>
        <th>Delivery</th>
        <th>Total</th>
        <th>Actions</th>
      </tr>
      <?php foreach ($orders as $order): ?>
      <tr>
        <td><?php echo $order['OrderID']; ?></td>
        <td><?php echo htmlspecialchars($order['UserName']); ?></td>
        <td><?php echo $order['OrderDate']; ?></td>
        <td>
          <form method="post" class="update-form">
            <input type="hidden" name="order_id" value="<?php echo $order['OrderID']; ?>">
            <select name="status">
              <option value="Pending" <?php if($order['Status']==='Pending') echo 'selected'; ?>>Pending</option>
              <option value="Confirmed" <?php if($order['Status']==='Confirmed') echo 'selected'; ?>>Confirmed</option>
              <option value="Cancelled" <?php if($order['Status']==='Cancelled') echo 'selected'; ?>>Cancelled</option>
              <option value="Completed" <?php if($order['Status']==='Completed') echo 'selected'; ?>>Completed</option>
            </select>
        </td>
        <td>
            <select name="delivery">
              <option value="Processing" <?php if($order['DeliveryStatus']==='Processing') echo 'selected'; ?>>Processing</option>
              <option value="Delivering" <?php if($order['DeliveryStatus']==='Delivering') echo 'selected'; ?>>Delivering</option>
              <option value="Delivered" <?php if($order['DeliveryStatus']==='Delivered') echo 'selected'; ?>>Delivered</option>
              <option value="Failed" <?php if($order['DeliveryStatus']==='Failed') echo 'selected'; ?>>Failed</option>
            </select>
            <button type="submit" name="update_status">Update</button>
          </form>
        </td>
        <td><?php echo number_format($order['TotalAmount'], 0, ',', '.'); ?>đ</td>
        <td><a href="../order_detail.php?id=<?php echo $order['OrderID']; ?>" class="view-btn">View</a></td>
      </tr>
      <?php endforeach; ?>
    </table>
  </main>
</body>
</html> 