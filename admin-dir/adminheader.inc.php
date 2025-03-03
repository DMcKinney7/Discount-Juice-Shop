<?php
session_start();

// Define an array of navigation items
$navItems = [
    ['label' => 'Home', 'url' => '../index.php'],
    ['label' => 'Products', 'url' => '../products.php'],
    ['label' => 'Create Product', 'url' => '../admin-dir/create.php'],
    ['label' => 'Update Product', 'url' => '../admin-dir/update.php'],
    ['label' => 'Delete Product', 'url' => '../admin-dir/delete.php']
];

// Check if the user is signed in
if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] === true) {
    $navItems[] = ['label' => 'Log out', 'url' => '../logout.php'];
} else {
    $navItems[] = ['label' => 'Sign in', 'url' => '../login.php'];
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
}

nav ul li {
    float: left;
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
</style>

<nav>
  <ul>
    <?php foreach ($navItems as $item): ?>
      <li><a href="<?= htmlspecialchars($item['url']) ?>"><?= htmlspecialchars($item['label']) ?></a></li>
    <?php endforeach; ?>
  </ul>
</nav>