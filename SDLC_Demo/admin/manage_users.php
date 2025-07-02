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

// Đổi quyền user
if (isset($_POST['change_role'])) {
    $uid = intval($_POST['user_id']);
    $role = intval($_POST['role']);
    if ($uid != 1) { // Không cho đổi quyền user admin gốc
        $stmt = mysqli_prepare($connect, "UPDATE users SET RoleID=? WHERE UserID=?");
        mysqli_stmt_bind_param($stmt, "ii", $role, $uid);
        mysqli_stmt_execute($stmt);
    }
}
// Xóa user
if (isset($_GET['delete'])) {
    $uid = intval($_GET['delete']);
    if ($uid != 1) { // Không cho xóa user admin gốc
        mysqli_query($connect, "DELETE FROM users WHERE UserID = $uid");
    }
}
// Lấy danh sách user
$sql = "SELECT u.*, r.Rolename FROM users u JOIN role r ON u.RoleID = r.RoleID ORDER BY u.UserID DESC";
$result = mysqli_query($connect, $sql);
$users = [];
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý người dùng - Admin</title>
  <link rel="stylesheet" href="../style.css">
  <style>
    .users-table { width: 100%; border-collapse: collapse; margin: 30px 0; }
    .users-table th, .users-table td { padding: 12px; border-bottom: 1px solid #eee; text-align: center; }
    .users-table th { background: #00b14f; color: #fff; }
    .users-table tr:hover { background: #f6fff8; }
    .role-form select { padding: 6px 10px; border-radius: 5px; border: 1px solid #ccc; }
    .role-form button { background: #00b14f; color: #fff; border: none; padding: 7px 16px; border-radius: 5px; cursor: pointer; font-size: 15px; margin-left: 6px; }
    .role-form button:hover { background: #008f3a; }
    .delete-btn { background: #ff424e; color: #fff; border: none; padding: 7px 16px; border-radius: 5px; cursor: pointer; font-size: 15px; }
    .delete-btn:hover { opacity: 0.85; }
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
    <h2 style="margin-top:40px; text-align:center;">Quản lý người dùng</h2>
    <table class="users-table">
      <tr>
        <th>ID</th>
        <th>Họ tên</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Address</th>
        <th>Username</th>
        <th>Role</th>
        <th>Change Role</th>
        <th>Actions</th>
      </tr>
      <?php foreach ($users as $user): ?>
      <tr>
        <td><?php echo $user['UserID']; ?></td>
        <td><?php echo htmlspecialchars($user['Name']); ?></td>
        <td><?php echo htmlspecialchars($user['Email']); ?></td>
        <td><?php echo htmlspecialchars($user['Phone']); ?></td>
        <td><?php echo htmlspecialchars($user['Address']); ?></td>
        <td><?php echo htmlspecialchars($user['Username']); ?></td>
        <td><?php echo htmlspecialchars($user['Rolename']); ?></td>
        <td>
          <?php if ($user['UserID'] != 1): ?>
          <form method="post" class="role-form">
            <input type="hidden" name="user_id" value="<?php echo $user['UserID']; ?>">
            <select name="role">
              <option value="1" <?php if($user['RoleID']==1) echo 'selected'; ?>>Admin</option>
              <option value="2" <?php if($user['RoleID']==2) echo 'selected'; ?>>User</option>
            </select>
            <button type="submit" name="change_role">Update</button>
          </form>
          <?php else: ?>
            <span style="color:#888;">Super Admin</span>
          <?php endif; ?>
        </td>
        <td>
          <?php if ($user['UserID'] != 1): ?>
            <a href="manage_users.php?delete=<?php echo $user['UserID']; ?>" class="delete-btn" onclick="return confirm('Xóa user này?');">Xóa</a>
          <?php else: ?>
            <span style="color:#888;">---</span>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
  </main>
</body>
</html> 