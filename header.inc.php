<?php
// Define an array of navigation items
$navItems = [
    ['label' => 'Home', 'url' => 'index.php'],
    ['label' => 'Product', 'url' => 'products.php'],
    ['label' => 'Sign in', 'url' => 'login.php']
];
?>

<nav>
  <ul>
    <?php foreach ($navItems as $item):?>
      <li><a href="<?= $item['url']?>"><?= $item['label']?></a></li>
    <?php endforeach;?>
  </ul>
</nav>