<?php
// Database connection
require 'db_connect.php';

// Handle add product
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $type = $_POST['type'];
    $unit_price = $_POST['unit_price'];
    $quantity_in_stock = $_POST['quantity_in_stock'];

    $sql = "INSERT INTO products (name, type, unit_price, quantity_in_stock, inventory_value) 
            VALUES ('$name', '$type', '$unit_price', '$quantity_in_stock', '$unit_price' * '$quantity_in_stock')";

    if ($conn->query($sql) === TRUE) {
        echo "New product added successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Handle remove product
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_product'])) {
    $id = $_POST['product_id'];

    $sql = "DELETE FROM products WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "Product removed successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Handle search product
$search_sql = "";
if (isset($_GET['search'])) {
    $search_value = $_GET['search_value'];
    $search_type = $_GET['search_type'];

    if ($search_type == 'name') {
        $search_sql = "WHERE name LIKE '%$search_value%'";
    } else if ($search_type == 'type') {
        $search_sql = "WHERE type LIKE '%$search_value%'";
    }
}

// Fetch products
$sql = "SELECT * FROM products $search_sql";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>Product Inventory System</h1>
</header>

<div class="container">
    <form method="GET" action="inventory.php">
        <input type="text" name="search_value" placeholder="Search by name or type" required>
        <select name="search_type">
            <option value="name">Name</option>
            <option value="type">Type</option>
        </select>
        <button type="submit" name="search">Search</button>
    </form>

    <h2>Add Product</h2>
    <form method="POST" action="inventory.php">
        <input type="text" name="name" placeholder="Product Name" required>
        <input type="text" name="type" placeholder="Product Type" required>
        <input type="number" name="unit_price" placeholder="Unit Price" required>
        <input type="number" name="quantity_in_stock" placeholder="Quantity in Stock" required>
        <button type="submit" name="add_product">Add Product</button>
    </form>

    <h2>Product List</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Type</th>
            <th>Unit Price</th>
            <th>Quantity</th>
            <th>Total Value</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['type']; ?></td>
                <td><?php echo $row['unit_price']; ?></td>
                <td><?php echo $row['quantity_in_stock']; ?></td>
                <td><?php echo $row['inventory_value']; ?></td>
                <td>
                    <form method="POST" action="inventory.php" style="display:inline;">
                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="remove_product">Remove</button>
                    </form>
                    <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>

<?php $conn->close(); ?>
