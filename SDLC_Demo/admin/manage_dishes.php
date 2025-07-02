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

// Xử lý thêm món
if (isset($_POST['add'])) {
    $name = trim($_POST['name']);
    $desc = trim($_POST['desc']);
    $price = floatval($_POST['price']);
    $image = trim($_POST['image']);
    $user_id = 1; // Admin thêm
    if ($name && $price && $image) {
        $stmt = mysqli_prepare($connect, "INSERT INTO dish (UserID, Name, Description, Price, Image) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "issds", $user_id, $name, $desc, $price, $image);
        mysqli_stmt_execute($stmt);
    }
}
// Xử lý xóa món
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($connect, "DELETE FROM dish WHERE DishID = $id");
}
// Xử lý sửa món
if (isset($_POST['edit'])) {
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);
    $desc = trim($_POST['desc']);
    $price = floatval($_POST['price']);
    $image = trim($_POST['image']);
    if ($name && $price && $image) {
        $stmt = mysqli_prepare($connect, "UPDATE dish SET Name=?, Description=?, Price=?, Image=? WHERE DishID=?");
        mysqli_stmt_bind_param($stmt, "ssdsi", $name, $desc, $price, $image, $id);
        mysqli_stmt_execute($stmt);
    }
}
// Lấy danh sách món ăn
$dishes = [];
$result = mysqli_query($connect, "SELECT * FROM dish ORDER BY DishID DESC");
while ($row = mysqli_fetch_assoc($result)) {
    $dishes[] = $row;
}
// Lấy thông tin món cần sửa nếu có
$edit_dish = null;
if (isset($_GET['edit'])) {
    $eid = intval($_GET['edit']);
    $res = mysqli_query($connect, "SELECT * FROM dish WHERE DishID = $eid");
    $edit_dish = mysqli_fetch_assoc($res);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý món ăn - Admin</title>
  <link rel="stylesheet" href="../style.css">
  <style>
    .dish-table { width: 100%; border-collapse: collapse; margin: 30px 0; }
    .dish-table th, .dish-table td { padding: 12px; border-bottom: 1px solid #eee; text-align: center; }
    .dish-table th { background: #00b14f; color: #fff; }
    .dish-img { width: 80px; border-radius: 8px; }
    .admin-form { max-width: 500px; margin: 30px auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); padding: 28px; }
    .admin-form h3 { color: #00b14f; margin-bottom: 18px; }
    .admin-form input, .admin-form textarea { width: 100%; padding: 10px; margin-bottom: 12px; border-radius: 6px; border: 1px solid #ccc; }
    .admin-form button { background: #00b14f; color: #fff; border: none; padding: 12px 28px; border-radius: 6px; font-size: 16px; font-weight: 600; cursor: pointer; }
    .admin-form button:hover { background: #008f3a; }
    .action-btn { background: #00b14f; color: #fff; border: none; padding: 7px 16px; border-radius: 5px; cursor: pointer; font-size: 15px; margin: 0 2px; }
    .action-btn.delete { background: #ff424e; }
    .action-btn:hover { opacity: 0.85; }
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
    <div class="admin-form">
      <h3><?php echo $edit_dish ? 'Sửa món ăn' : 'Thêm món ăn mới'; ?></h3>
      <form method="post">
        <?php if ($edit_dish): ?>
          <input type="hidden" name="id" value="<?php echo $edit_dish['DishID']; ?>">
        <?php endif; ?>
        <input type="text" name="name" required placeholder="Tên món" value="<?php echo $edit_dish['Name'] ?? ''; ?>">
        <textarea name="desc" placeholder="Mô tả món ăn"><?php echo $edit_dish['Description'] ?? ''; ?></textarea>
        <input type="number" name="price" required min="0" step="0.01" placeholder="Giá" value="<?php echo $edit_dish['Price'] ?? ''; ?>">
        <input type="text" name="image" required placeholder="Link ảnh" value="<?php echo $edit_dish['Image'] ?? ''; ?>">
        <button type="submit" name="<?php echo $edit_dish ? 'edit' : 'add'; ?>"><?php echo $edit_dish ? 'Cập nhật' : 'Thêm mới'; ?></button>
        <?php if ($edit_dish): ?>
          <a href="manage_dishes.php" style="margin-left:18px;">Hủy</a>
        <?php endif; ?>
      </form>
    </div>
    <table class="dish-table">
      <tr>
        <th>ID</th>
        <th>Ảnh</th>
        <th>Tên món</th>
        <th>Mô tả</th>
        <th>Giá</th>
        <th>Hành động</th>
      </tr>
      <?php foreach ($dishes as $dish): ?>
      <tr>
        <td><?php echo $dish['DishID']; ?></td>
        <td><img src="<?php echo htmlspecialchars($dish['Image']); ?>" class="dish-img"></td>
        <td><?php echo htmlspecialchars($dish['Name']); ?></td>
        <td><?php echo htmlspecialchars($dish['Description']); ?></td>
        <td><?php echo number_format($dish['Price'], 0, ',', '.'); ?>đ</td>
        <td>
          <a href="manage_dishes.php?edit=<?php echo $dish['DishID']; ?>" class="action-btn">Sửa</a>
          <a href="manage_dishes.php?delete=<?php echo $dish['DishID']; ?>" class="action-btn delete" onclick="return confirm('Xóa món này?');">Xóa</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
  </main>
</body>
</html>
