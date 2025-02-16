<html>
<body>
<p>This is text from HTML.</p>
<?php

$mysqli = new mysqli("localhost", "user", "password", "database");

echo $mysqli->host_info . "\n";

$mysqli = new mysqli("127.0.0.1", "user", "password", "database", 3306);

echo $mysqli->host_info . "\n";
</body>
</html>