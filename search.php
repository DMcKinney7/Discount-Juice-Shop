<?php include("header.inc.php"); ?>
<?php
require_once "/var/www/html/Discount-Juice-Shop/db.inc.php";


// Check the connection
if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
}

// Get the search term from the URL parameter
$searchTerm = $_GET['search'];

// Prepare the SQL query
$sql = "SELECT * FROM products WHERE name LIKE '%$searchTerm%' ORDER BY name";

// Execute the query
$result = $conn->query($sql);

// Check if any results are found
if ($result->num_rows > 0) {
    // Output the search results
    while ($row = $result->fetch_assoc()) {
        echo "<h3>". $row["name"]. "</h3>";
        // Output other product details as needed
    }
} else {
    echo "No results found.";
}

// Close the database connection
$conn->close();
?>
