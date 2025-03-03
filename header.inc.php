<?php
session_start();

// Define an array of navigation items
$navItems = [
    ['label' => 'Home', 'url' => '/Discount-Juice-Shop/index.php'],
    ['label' => 'Product', 'url' => '/Discount-Juice-Shop/products.php']
];

// Check if the user is signed in
if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] === true) {
    if ($_SESSION['username'] === 'admin') {
        $navItems[] = ['label' => 'Admin', 'url' => '/Discount-Juice-Shop/admin-dir/admin.php'];
    }
    $navItems[] = ['label' => 'Log out', 'url' => '/Discount-Juice-Shop/logout.php'];
} else {
    $navItems[] = ['label' => 'Sign in', 'url' => '/Discount-Juice-Shop/login.php'];
}
?>

<style>
nav {
    background-color: #333;
    overflow: hidden;
}

nav ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

nav ul li {
    display: inline;
}

nav ul li a {
    display: block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}

nav ul li a:hover {
    background-color: #111;
}

nav .nav-items {
    display: flex;
    gap: 10px;
}

nav .cart {
    margin-left: auto;
    padding: 14px 16px;
}

nav .cart img {
    max-width: 30px;
    height: auto;
    vertical-align: middle;
}
</style>

<nav>
  <ul>
    <div class="nav-items">
      <?php foreach ($navItems as $item): ?>
        <li><a href="<?= htmlspecialchars($item['url']) ?>"><?= htmlspecialchars($item['label']) ?></a></li>
      <?php endforeach; ?>
    </div>
    <li class="cart">
      <a href="/Discount-Juice-Shop/cart/index.php"><img src="/Discount-Juice-Shop/images/cart.jpg" alt="Cart"></a>
    </li>
  </ul>
</nav>
