<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "asm2";
$connect = mysqli_connect($servername, $username, $password, $dbname);
// Lấy danh sách món ăn
$sql = "SELECT * FROM dish";
$result = mysqli_query($connect, $sql);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FoodX Delivery</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <header>
    <div class="container header-flex">
      <img src="logo-grabfood2.svg" alt="Logo" class="logo">
      <div class="delivery-address">
        <span>Deliver to:</span>
        <input type="text" placeholder="Enter your address..." />
        <button class="address-btn">Update</button>
      </div>
      <nav>
        <ul>
          <?php if(isset($_SESSION['user'])): ?>
            <li><span>Welcome, <b><?php echo htmlspecialchars($_SESSION['user']); ?></b></span></li>
            <li><a href="logout.php">Log out</a></li>
          <?php else: ?>
            <li><a href="login.php">Log in</a></li>
            <li><a href="register.php">Register</a></li>
          <?php endif; ?>
          <li><a href="index.php">Home</a></li>
          <li><a href="cart.php">Shopping Cart <span class="cart-count"><?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?></span></a></li>
        </ul>
      </nav>
    </div>
    <div class="search-bar">
      <input type="text" placeholder="Find food....">
      <button>Search</button>
    </div>
  </header>
  <main>
    <section class="slider-banner">
      <div class="slider">
        <img src="https://inan2h.vn/wp-content/uploads/2022/12/in-banner-quang-cao-do-an-6-1.jpg" alt="Banner 1">
        <img
          src="https://img.freepik.com/premium-psd/food-menu-restaurant-facebook-cover-banner-template_120329-4873.jpg?semt=ais_hybrid"
          alt="Banner 2">
        <img
          src="https://img.freepik.com/premium-psd/banner-food-menu-restaurant-template-food-menu-restaurant-roast-chicken_609989-567.jpg"
          alt="Banner 3">
        <img src="https://th.bing.com/th/id/OIP.ajLuY2Ym0hz3TiNUTEf__gHaEK?rs=1&pid=ImgDetMain" alt="Banner 4">
        <img src="https://th.bing.com/th/id/R.dace44bce79db7e4cf7f52a3e7641a91?rik=DtjV69DPyKdYHg&pid=ImgRaw&r=0"
          alt="Banner 5">
        <!-- Lặp lại -->
        <img src="https://inan2h.vn/wp-content/uploads/2022/12/in-banner-quang-cao-do-an-6-1.jpg" alt="Banner 1">
        <img
          src="https://img.freepik.com/premium-psd/food-menu-restaurant-facebook-cover-banner-template_120329-4873.jpg?semt=ais_hybrid"
          alt="Banner 2">
        <img
          src="https://img.freepik.com/premium-psd/banner-food-menu-restaurant-template-food-menu-restaurant-roast-chicken_609989-567.jpg"
          alt="Banner 3">
        <img src="https://th.bing.com/th/id/OIP.ajLuY2Ym0hz3TiNUTEf__gHaEK?rs=1&pid=ImgDetMain" alt="Banner 4">
        <img src="https://th.bing.com/th/id/R.dace44bce79db7e4cf7f52a3e7641a91?rik=DtjV69DPyKdYHg&pid=ImgRaw&r=0"
          alt="Banner 5">
      </div>
      </div>
    </section>
    <section class="categories">
      <button class="cat-btn active">All</button>
      <button class="cat-btn">Main course</button>
      <button class="cat-btn">Beverage</button>
      <button class="cat-btn">Dessert</button>
      <button class="cat-btn">Combo</button>
    </section>
    <section class="featured">
      <h2>Featured Categories</h2>
      <div class="featured-list">
        <div class="featured-item">
          <img src="https://static.vinwonders.com/2022/10/banh-mi-phu-quoc-1.jpg" alt="Bánh mì">
          <span>Bread</span>
        </div>
        <div class="featured-item">
          <img src="https://th.bing.com/th/id/OIP.kCeAp8d3jsaZmu9nk0SowwAAAA?rs=1&pid=ImgDetMain" alt="Cơm tấm">
          <span>Broken rice</span>
        </div>
        <div class="featured-item">
          <img src="https://toigingiuvedep.vn/wp-content/uploads/2021/06/hinh-anh-tra-sua-tran-chau-dep-mat.jpg"
            alt="Trà sữa">
          <span>Milk tea</span>
        </div>
        <div class="featured-item">
          <img src="https://th.bing.com/th/id/OIP.4925IGh0pykOKRzrFvTGEwHaHa?rs=1&pid=ImgDetMain" alt="Pizza">
          <span>Pizza</span>
        </div>
      </div>
    </section>
    <section class="food-list">
      <h2>Delicious Food Suggestions</h2>
      <div class="foods">
        <?php while($row = mysqli_fetch_assoc($result)): ?>
        <div class="food-card">
          <div class="discount-badge">-10%</div>
          <img src="<?php echo htmlspecialchars($row['Image']); ?>" alt="<?php echo htmlspecialchars($row['Name']); ?>">
          <h3><?php echo htmlspecialchars($row['Name']); ?></h3>
          <div class="rating">⭐ 4.8 | 200+ sold</div>
          <p class="desc"><?php echo htmlspecialchars($row['Description']); ?></p>
          <p class="price"><?php echo number_format($row['Price'], 0, ',', '.'); ?>đ <span class="old-price">109.000đ</span></p>
          <button class="add-cart" data-id="<?php echo $row['DishID']; ?>" onclick="addToCart(<?php echo $row['DishID']; ?>)">Add to cart</button>
        </div>
        <?php endwhile; ?>
      </div>
    </section>
    <section class="about">
      <h2>Why choose FoodShop Delivery?</h2>
      <ul>
        <li>Super fast delivery in 30 minutes</li>
        <li>Diverse dishes, reputable restaurants</li>
        <li>Many attractive offers every day</li>
        <li>Safe and secure payment</li>
      </ul>
    </section>
  </main>
  <footer>
    <div class="footer-main">
      <div class="footer-cols">
        <!-- Cột 1: Logo & slogan -->
        <div class="footer-col footer-col-brand">
          <img src="logo-grabfood2.svg" alt="FoodX Delivery" class="footer-logo">
          <div class="footer-slogan">Deliver delicious food to your door, fast and reputable!</div>
          <div class="footer-apps">
            <a href="#" target="_blank" aria-label="App Store">
              <img src="https://upload.wikimedia.org/wikipedia/commons/6/67/App_Store_%28iOS%29.svg" alt="App Store"
                class="app-icon">
            </a>
            <a href="#" target="_blank" aria-label="Google Play">
              <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg"
                alt="Google Play" class="app-icon">
            </a>
          </div>
        </div>
        <!-- Cột 2: Liên kết -->
        <div class="footer-col">
          <h4>About <span class="brand-green">FoodX</span></h4>
          <ul>
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">Terms of Use</a></li>
            <li><a href="#">Contact</a></li>
            <li><a href="#">Blog</a></li>
          </ul>
        </div>
        <!-- Cột 3: Kết nối -->
        <div class="footer-col">
          <h4>Connect with us</h4>
          <div class="footer-socials">
            <a href="https://facebook.com/" target="_blank" title="Facebook" aria-label="Facebook">
              <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/facebook.svg" alt="Facebook"
                class="footer-icon">
            </a>
            <a href="https://zalo.me/" target="_blank" title="Zalo" aria-label="Zalo">
              <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/zalo.svg" alt="Zalo"
                class="footer-icon">
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
  <script>
    function addToCart(dishId) {
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'add_to_cart.php', true);
      xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
      xhr.onload = function() {
        if (xhr.status === 200) {
          alert('Added to cart!');
          location.reload();
        }
      };
      xhr.send('dish_id=' + dishId);
    }
  </script>
</body>

</html>